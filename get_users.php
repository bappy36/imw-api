<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// আপনার ডাটাবেস ডিটেইলস এখানে দিন
$host = "আপনার_হোস্ট_নাম"; // যেমন: containers-us-west-xx.railway.app
$user = "আপনার_ইউজার";
$pass = "আপনার_পাসওয়ার্ড";
$db   = "আপনার_ডাটাবেস_নাম";
$port = "আপনার_পোর্ট"; // রেলওয়েতে সাধারণত পোর্ট থাকে, যেমন: 5432 বা 3306

// কানেকশন তৈরি
$conn = new mysqli($host, $user, $pass, $db, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die(json_encode(["error" => "Connection failed: " . $conn->connect_error]));
}

// ইউজার টেবিল থেকে নাম নিয়ে আসা
$sql = "SELECT first_name, last_name FROM users";
$result = $conn->query($sql);

$users = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

echo json_encode($users);
$conn->close();
?>
