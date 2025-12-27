<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// টাইমজোন বাংলাদেশ সেট করা হলো
date_default_timezone_set('Asia/Dhaka'); 

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

if ($method == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) { echo json_encode(["status" => "error", "message" => "No data"]); exit; }

    $emp_id = $data['employee_id'];
    $u_name = $data['user_name'];
    $dept = $data['department'];
    $date = $data['date'];
    $c_in = $data['check_in'] ?? null;
    $c_out = $data['check_out'] ?? null;
    $status = $data['status'];

    $check = $conn->query("SELECT id FROM attendance WHERE employee_id = '$emp_id' AND date = '$date'");
    if ($check->num_rows > 0) {
        $sql = "UPDATE attendance SET check_out='$c_out', status='$status' WHERE employee_id='$emp_id' AND date='$date'";
    } else {
        $sql = "INSERT INTO attendance (employee_id, user_name, department, date, check_in, status) VALUES ('$emp_id', '$u_name', '$dept', '$date', '$c_in', '$status')";
    }
    
    if ($conn->query($sql)) { echo json_encode(["status" => "success"]); } 
    else { echo json_encode(["status" => "error", "message" => $conn->error]); }

} else if ($method == 'GET') {
    
    // ১. ডেক্সটপ রিপোর্টের জন্য সব হিস্ট্রি
    if (isset($_GET['all_history'])) {
        $res = $conn->query("SELECT * FROM attendance ORDER BY date DESC");
        $rows = [];
        while($r = $res->fetch_assoc()) { $rows[] = $r; }
        echo json_encode($rows);
    } 
    
    // ২. মোবাইলের জন্য নির্দিষ্ট ইউজারের হিস্ট্রি
    else if (isset($_GET['employee_id'])) {
        $emp_id = $_GET['employee_id'];
        $res = $conn->query("SELECT * FROM attendance WHERE employee_id = '$emp_id' ORDER BY date DESC");
        $rows = [];
        while($r = $res->fetch_assoc()) { $rows[] = $r; }
        echo json_encode($rows);
    } 
    
    // ৩. আজকের প্রেজেন্ট/অ্যাবসেন্ট সামারি (মোবাইল ও ডেক্সটপ উভয়ের জন্য)
    else if (isset($_GET['summary_today'])) {
        $today = date('Y-m-d');
        
        $present_res = $conn->query("SELECT * FROM attendance WHERE date = '$today'");
        $present = [];
        $present_ids = [];
        while($r = $present_res->fetch_assoc()) {
            $present[] = $r;
            $present_ids[] = $r['employee_id'];
        }

        $absent = [];
        $users_res = $conn->query("SELECT employee_id, first_name, last_name FROM users");
        while($u = $users_res->fetch_assoc()) {
            if (!in_array($u['employee_id'], $present_ids)) {
                $absent[] = $u;
            }
        }

        echo json_encode([
            "present" => $present,
            "absent" => $absent
        ]);
    }
}

$conn->close();
?>
