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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Create connection
    $conn = new mysqli($servername, $serverusername, $serverpassword, $dbname);
    // Check connection
    if ($conn->connect_error) {
        $message["response"] = "Error";
        echo json_encode($message);
        return;
    }

    if (strcmp($data->action, "get_reviews") == 0) {
        $data->name = test_input($data->name);
        $sql = "SELECT author_name, rating, relative_time_description, text FROM review WHERE session_id='$data->session_id' AND user_id='$data->user_id'";
        $result = $conn->query($sql);
        $message["response"] = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                array_push($message["response"], array("author_name" => $row["author_name"], "rating" => $row["rating"],
                    "relative_time_description" => $row["relative_time_description"], "text" => $row["text"]));
            }
        }
    }

    $conn->close();

    echo json_encode($message);
    return;
}