<?php
header("Content-Type: application/json");
// টাইমজোন বাংলাদেশ সেট করা হলো যাতে অ্যাপের সাথে মিলে যায়
date_default_timezone_set('Asia/Dhaka'); 

error_reporting(E_ALL);
ini_set('display_errors', 1);

// ... বাকি ডাটাবেস ক্রেডেনশিয়াল ...
$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed"]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET' && isset($_GET['summary_today'])) {
    // এখন এটি বাংলাদেশের সময় অনুযায়ী আজকের তারিখ নিবে (2025-12-28)
    $today = date('Y-m-d'); 
    
    // Present List
    $present_res = $conn->query("SELECT * FROM attendance WHERE date = '$today'");
    $present = [];
    $present_ids = [];
    while($r = $present_res->fetch_assoc()) {
        $present[] = $r;
        $present_ids[] = $r['employee_id'];
    }

    // Absent List (Users টেবিল থেকে যারা আজকের প্রেজেন্ট লিস্টে নেই)
    $absent = [];
    $users_res = $conn->query("SELECT employee_id, first_name, last_name FROM users");
    while($u = $users_res->fetch_assoc()) {
        if (!in_array($u['employee_id'], $present_ids)) {
            $absent[] = $u;
        }
    }

    echo json_encode([
        "present" => $present,
        "absent" => $absent,
        "server_date" => $today // ডিবাগিং এর জন্য তারিখটি পাঠানো হলো
    ]);
    exit;
}

// ... বাকি POST এবং GET লজিক ...
