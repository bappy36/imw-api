<?php
$host = "centerbeam.proxy.rlwy.net";$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$dbname = "railway"; 

$conn = new mysqli($host, $user, $pass, $dbname, $port);

if ($conn->connect_error) {
    // Error holeo JSON return korbe jate Flutter bujhte pare
    header('Content-Type: application/json');
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}
?>
