from playwright.sync_api import sync_playwright
import dateparser
import re
import time

def check_exists_by_class(page, html_class):
    return page.evaluate(f"document.getElementsByClassName('{html_class}')[0] != undefined")

class Scraper:
    def __init__(self, page, server, client):
        self.page = page
        self.server = server
        self.client = client

    def _google_review(self):
        self.page.get_by_role("button", name=re.compile("avis Google", re.IGNORECASE)).first.click()
        self.page.get_by_role("option", name="Tous les avis").click()
        self.page.get_by_role("radio", name="Les plus récents").click()
        time.sleep(0.2)
        self.page.screenshot(path="example.png")

    def _pass_google_consent_handler(self):
        if (check_exists_by_class(self.page, "vUd4jb")):
            self.page.get_by_role("button", name="Tout accepter").click()

    def _get_last_date(self):
        index = self.page.evaluate("document.getElementsByClassName('gws-localreviews__google-review').length") - 1
        relative_date = self.page.evaluate(f"document.getElementsByClassName('gws-localreviews__google-review')[{index}].getElementsByClassName('dehysf')[0].innerText")
        return dateparser.parse(relative_date)

    def _load_review(self, mode, nb_review, last_date, max_review):
        self.server.send_message(self.client, "step_3")
        while ((mode == 1 and self.page.evaluate("document.getElementsByClassName('gws-localreviews__google-review').length")  < nb_review) or (mode == 0 and self._get_last_date().strftime("%Y-%m-%d") > last_date.strftime("%Y-%m-%d"))):
            if (self.page.evaluate("document.getElementsByClassName('gws-localreviews__google-review').length") == max_review):
                return
            self.page.evaluate("document.getElementsByClassName('review-dialog-list')[0].scrollTo(0, document.getElementsByClassName('review-dialog-list')[0].scrollHeight);")

    def _fill_array(self, array, mode, nb_review, last_date):
        self.server.send_message(self.client, "step_4")
        reviews = self.page.evaluate(
            '''var element = {
                length: document.getElementsByClassName('gws-localreviews__google-review').length,
                names: [],
                ratings: [],
                dates: [],
                texts: [],
            };
            for (let e of document.getElementsByClassName('TSUbDb')) {
                element.names.push(e.innerText);
            };
            for (let e of document.getElementsByClassName('EBe2gf')) {
                element.ratings.push(e.getAttribute('aria-label').split(' ')[1].replace(',', '.'));
            };
            for (let e of document.getElementsByClassName('dehysf')) {
                element.dates.push(e.innerText);
            };
            for (let e of document.getElementsByClassName('review-more-link')) {
                e.click();
            };
            for (let e of document.getElementsByClassName('Jtu6Td')) {
                element.texts.push(e.innerText);
            };
            element;''')
        self.server.send_message(self.client, "step_5")
        for i in range(reviews["length"]):
            if (mode == 0 and dateparser.parse(reviews["dates"][i]).strftime("%Y-%m-%d") == last_date.strftime("%Y-%m-%d")):
                return
            if (mode == 1 and i == nb_review):
                return
            new_review = {}
            new_review["author_name"] = reviews["names"][i]
            new_review["rating"] = float(reviews["ratings"][i])
            new_review["relative_time_description"] = reviews["dates"][i]
            new_review["text"] = reviews["texts"][i]
            array.append(new_review)

    def get_review(self, query, mode, nb_review = None, time_review = None):
        if (nb_review is None):
            nb_review = -1
        if (time_review is None):
            time_review = ""
        self.page.goto("https://www.google.com/search?q=" + query)
        array = []
        first_load = 0
        # Pass the google consent
        self._pass_google_consent_handler()
        # Go to the google review
        max_review = self.page.evaluate("document.getElementsByClassName('hqzQac')[0].innerText")
        max_review = int(''.join(re.findall(r'\d+', max_review)))
        self.server.send_message(self.client, "step_2")
        self._google_review()
        time_review = dateparser.parse(time_review)
        # Load review
        self._load_review(mode, nb_review, time_review, max_review)
        # Get review
        self._fill_array(array, mode, nb_review, time_review)
        return array

if __name__ == "__main__":
    nb_review = 50
    time_review = "il y a un mois"
    option = 0
    query = "Get Out Angers"
    json = {
        "result": {
            "reviews": [],
        },
        "status": "OK",
    }

    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()
        scraper = Scraper(page, None, None)

        json["result"]["reviews"] = scraper.get_review(query, option, nb_review, time_review)

        print(json["result"]["reviews"])

        browser.close()
