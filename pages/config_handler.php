<?php
require "config.php";

$json = file_get_contents('php://input');
$data = json_decode($json);

$message = array("response" => "Success");
$key = "api_key";
function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function get_review($place, $api_key)
{
    $base_url = "https://maps.googleapis.com/maps/api/place/findplacefromtext/json?input=";
    $url = $base_url . urlencode($place) . "&inputtype=textquery&fields=rating,user_ratings_total&language=fr&key=" . $api_key;
    $ch = curl_init();
    try {
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $data = curl_exec($ch);
        if (curl_errno($ch)) {
            echo curl_error($ch);
            die();
        }
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($http_code == intval(200)){
            $json = json_decode($data, true);
            return array("rating"=> $json['candidates'][0]['rating'], "nb_review"=> $json['candidates'][0]['user_ratings_total']);
        } else {
            echo "Ressource introuvable : " . $http_code;
        }
    } catch (\Throwable $th) {
        throw $th;
    } finally {
        curl_close($ch);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $message["response"] = "Error";
        echo json_encode($message);
        return;
    }

    if (strcmp($data->action, "create") == 0) {
        $data->name = test_input($data->name);
        $sql = "INSERT INTO configuration (user_id, config_name) VALUES ('$data->user_id', '$data->name')";
        if ($conn->query($sql) === TRUE) {
            $last_id = $conn->insert_id;
            foreach ($data->places as $place) {
                $place->name = test_input($place->name);
                $place->display_name = test_input($place->display_name);
                $sql = "INSERT INTO config_place (config_id, place_name, place_display_name) VALUES ('$last_id', '$place->name', '$place->display_name')";
                if ($conn->query($sql) === FALSE) {
                    $message["response"] = "Error";
                    echo json_encode($message);
                    return;
                }
            }
        } else {
            $message["response"] = "Error";
            echo json_encode($message);
            return;
        }
    } elseif (strcmp($data->action, "edit") == 0) {
        $data->name = test_input($data->name);
        $sql = "DELETE FROM config_place WHERE config_id=(SELECT config_id FROM configuration WHERE config_name='$data->name' AND user_id='$data->user_id')";
        if ($conn->query($sql) === TRUE) {
            foreach ($data->places as $place) {
                $place->name = test_input($place->name);
                $place->display_name = test_input($place->display_name);
                $sql = "INSERT INTO config_place (config_id, place_name, place_display_name) VALUES ((SELECT config_id FROM configuration WHERE config_name='$data->name' AND user_id='$data->user_id'), '$place->name', '$place->display_name')";
                if ($conn->query($sql) === FALSE) {
                    $message["response"] = "Error";
                    echo json_encode($message);
                    return;
                }
            }
        } else {
            $message["response"] = "Error";
            echo json_encode($message);
            return;
        }
    } elseif (strcmp($data->action, "delete") == 0) {
        $data->name = test_input($data->name);
        $sql = "DELETE FROM config_place WHERE config_id=(SELECT config_id FROM configuration WHERE config_name='$data->name' AND user_id='$data->user_id')";
        if ($conn->query($sql) === TRUE) {
            $sql = "DELETE FROM configuration WHERE config_name='$data->name' AND user_id='$data->user_id'";
            if ($conn->query($sql) === FALSE) {
                $message["response"] = "Error";
                echo json_encode($message);
                return;
            }
        } else {
            $message["response"] = "Error";
            echo json_encode($message);
            return;
        }
    }

    $conn->close();

    echo json_encode($message);
    return;
} elseif ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Create connection
    $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $message["response"] = "Error";
        echo json_encode($message);
        return;
    }
    if (strcmp(test_input($_GET["action"]), "get_conf") == 0) {
        $user_id = test_input($_GET["user_id"]);
        $config_name = test_input($_GET["config_name"]);
        $sql = "SELECT place_name, place_display_name FROM config_place WHERE config_id=(SELECT config_id FROM configuration WHERE config_name='$config_name' AND user_id='$user_id')";
        $result = $conn->query($sql);
        $message["response"] = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                array_push($message["response"], array("place_name" => $row["place_name"], "display_name" => $row["place_display_name"]));
            }
        }
        echo json_encode($message);
    } elseif (strcmp(test_input($_GET["action"]), "get_conf_details") == 0) {
        $user_id = test_input($_GET["user_id"]);
        $config_name = test_input($_GET["config_name"]);
        $sql = "SELECT place_name, place_display_name FROM config_place WHERE config_id=(SELECT config_id FROM configuration WHERE config_name='$config_name' AND user_id='$user_id')";
        $result = $conn->query($sql);
        $message["response"] = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                if (str_contains($row["place_name"], "&#039;")) {
                    $row["place_name"] = htmlspecialchars_decode($row["place_name"], ENT_QUOTES);
                }
                $info = get_review($row["place_name"], $key);
                array_push($message["response"], array("place_name" => $row["place_name"], "display_name" => $row["place_display_name"], "rating" => $info["rating"], "nb_review" => $info["nb_review"]));
            }
        }
        echo json_encode($message);
    }

    $conn->close();
}
