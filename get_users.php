<?php
// সব ধরণের এরর দেখানোর জন্য (ডিবাগিং)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// আপনার ডাটাবেস ডিটেইলস দিন (আপনার Railway Dashboard থেকে পাবেন)
$host = "আপনার_হোস্ট_এখানে"; 
$user = "আপনার_ইউজার_এখানে";
$pass = "আপনার_পাসওয়ার্ড_এখানে";
$db   = "আপনার_ডাটাবেস_নাম";
$port = 3306; // রেলওয়েতে সাধারণত ৩৩০৬ হয়

try {
    // কানেকশন তৈরি
    $conn = new mysqli($host, $user, $pass, $db, $port);

    // কানেকশন চেক
    if ($conn->connect_error) {
        echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
        exit;
    }

    // টেবিল থেকে ডাটা নিয়ে আসা
    $sql = "SELECT first_name, last_name FROM users";
    $result = $conn->query($sql);

    if (!$result) {
        echo json_encode(["error" => "Query failed: " . $conn->error]);
        exit;
    }

    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

    echo json_encode($users);
    $conn->close();

} catch (Exception $e) {
    echo json_encode(["error" => "Server Error: " . $e->getMessage()]);
}
?>
