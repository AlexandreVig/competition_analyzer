import os
import requests
import mysql.connector as mysql
from websocket_server import WebsocketServer
from playwright.sync_api import sync_playwright
from scraper_v2 import Scraper
import json
from dotenv import load_dotenv
import time
import hashlib
import logging

load_dotenv()
HOST = os.environ["MYSQL_HOST"]
DATABASE = os.environ["MYSQL_DB"]
USER = os.environ["MYSQL_USER"]
PASSWORD = os.environ["MYSQL_PASSWD"]

def get_session_id(user_id):
    time_stamp = str(time.time())
    str2hash = time_stamp + user_id
    return hashlib.md5(str2hash.encode()).hexdigest()

def is_json(myjson):
  try:
    json.loads(myjson)
  except ValueError as e:
    return False
  return True

# Called for every client connecting (after handshake)
def new_client(client, server):
    print("New client connected and was given id %d" % client['id'])
    server.send_message(client, "Successfully connected")

# Called when a client sends a message
def message_received(client, server, message):
    print("Client(%d) search:\n%s" % (client['id'], message))
    if (is_json(message)):
        res = json.loads(message)
        if (res['action'] == 'get_review'):
            db_connection = mysql.connect(host=HOST, database=DATABASE, user=USER, password=PASSWORD)
            cur_insert = db_connection.cursor(buffered=True)
            with sync_playwright() as p:
                browser = p.chromium.launch()
                page = browser.new_page()
                scraper = Scraper(page, server, client)
                server.send_message(client, "step_1")

                session_id = get_session_id(res['user_id'])
                nb_review = 0
                time_review = ''
                reviews = []
                response = {
                    "result": {
                        "user_id": res['user_id'],
                        "session_id": session_id,
                        "nb_review": 0
                    },
                    "status": "OK",
                }
                if (res['option']['select_by'] == 'date'):
                    time_review = res['option']['select_value']
                    option = 0
                else:
                    nb_review = res['option']['select_value']
                    option = 1
                try:
                    reviews = scraper.get_review(res['option']['place_name'], option, nb_review, time_review)
                except:
                    server.send_message(client, json.dumps({
                        "status": "ERROR"
                    }))
                    browser.close()
                    return
                insert_review = (
                    "INSERT INTO review (user_id, session_id, author_name, rating, relative_time_description, text) "
                    "VALUES (%s, %s, %s, %s, %s, %s)")
                i = 0
                server.send_message(client, "step_6")
                for review in reviews:
                    cur_insert.execute(insert_review, (
                    res['user_id'], session_id, review['author_name'], review['rating'],
                    review['relative_time_description'], review['text']))
                    i += 1
                response['result']['nb_review'] = i
                db_connection.commit()
                server.send_message(client, json.dumps(response))

                browser.close()
                db_connection.close()


PORT = 5000
IP = requests.get("https://ident.me").text

server = WebsocketServer(host=IP, port=PORT, loglevel=logging.INFO, key="/etc/letsencrypt/live/competition-analyzer.alexvig.ovh/privkey.pem", cert="/etc/letsencrypt/live/competition-analyzer.alexvig.ovh/fullchain.pem")
server.set_fn_new_client(new_client)
server.set_fn_message_received(message_received)
server.run_forever()
