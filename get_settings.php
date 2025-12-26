<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

// Railway Credentials
$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(json_encode(["success" => false, "error" => $conn->connect_error]));
}

$result = $conn->query("SELECT usd_to_bdt FROM settings LIMIT 1");

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode(["success" => true, "usd_to_bdt" => $row['usd_to_bdt']]);
} else {
    echo json_encode(["success" => false, "message" => "No data found"]);
}
$conn->close();
?>