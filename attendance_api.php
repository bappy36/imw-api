<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'POST') {
    // আগের মতো POST লজিক থাকবে (Check-in/Out সেভ করার জন্য)
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
    
    // ১. নির্দিষ্ট এমপ্লয়ির হিস্ট্রি দেখার জন্য
    if (isset($_GET['employee_id'])) {
        $emp_id = $_GET['employee_id'];
        $res = $conn->query("SELECT * FROM attendance WHERE employee_id = '$emp_id' ORDER BY date DESC");
        $rows = [];
        while($r = $res->fetch_assoc()) { $rows[] = $r; }
        echo json_encode($rows);
    } 
    
    // ২. অ্যাডমিন সামারি (Present/Absent) দেখার জন্য
    else if (isset($_GET['summary_today'])) {
        $today = date('Y-m-d');
        
        // আজকে যারা উপস্থিত (Present)
        $present_res = $conn->query("SELECT * FROM attendance WHERE date = '$today'");
        $present = [];
        $present_ids = [];
        while($r = $present_res->fetch_assoc()) {
            $present[] = $r;
            $present_ids[] = $r['employee_id'];
        }

        // যারা অনুপস্থিত (Absent) - Users টেবিল থেকে তুলনা করে
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
