<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// আপনার দেওয়া Railway ডিটেইলস
$host = "centerbeam.proxy.rlwy.net"; 
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY"; 
$db   = "railway";
$port = 24312; 

try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    if ($conn->connect_error) {
        echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
        exit;
    }

    // ইউজার টেবিল থেকে নাম নিয়ে আসা
    $sql = "SELECT first_name, last_name FROM users";
    $result = $conn->query($sql);

    $users = [];
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    echo json_encode($users);
    $conn->close();
} catch (Exception $e) {
    echo json_encode(["error" => "Server Error: " . $e->getMessage()]);
}
?>
