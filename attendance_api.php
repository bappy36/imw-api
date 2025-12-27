<?php
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
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!$data) {
        echo json_encode(["status" => "error", "message" => "No data provided"]);
        exit;
    }

    $employee_id = $data['employee_id'];
    $user_name = $data['user_name'];
    $department = $data['department'];
    $date = $data['date'];
    $check_in = isset($data['check_in']) ? $data['check_in'] : null;
    $check_out = isset($data['check_out']) ? $data['check_out'] : null;
    $status = $data['status'];

    // Check if record exists
    $check_sql = "SELECT id FROM attendance WHERE employee_id = ? AND date = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("ss", $employee_id, $date);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update
        $row = $result->fetch_assoc();
        $id = $row['id'];
        
        $update_parts = [];
        $params = [];
        $types = "";

        if ($check_in) {
            $update_parts[] = "check_in = ?";
            $params[] = $check_in;
            $types .= "s";
        }
        if ($check_out) {
            $update_parts[] = "check_out = ?";
            $params[] = $check_out;
            $types .= "s";
        }
        $update_parts[] = "status = ?";
        $params[] = $status;
        $types .= "s";

        $types .= "i";
        $params[] = $id;

        $update_sql = "UPDATE attendance SET " . implode(", ", $update_parts) . " WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param($types, ...$params);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Attendance updated"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    } else {
        // Insert
        $insert_sql = "INSERT INTO attendance (employee_id, user_name, department, date, check_in, check_out, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sssssss", $employee_id, $user_name, $department, $date, $check_in, $check_out, $status);
        
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Attendance saved"]);
        } else {
            echo json_encode(["status" => "error", "message" => $stmt->error]);
        }
    }
} else if ($method == 'GET') {
    $employee_id = $_GET['employee_id'] ?? '';
    if ($employee_id) {
        $sql = "SELECT * FROM attendance WHERE employee_id = ? ORDER BY date DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];
        while($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }
        echo json_encode($rows);
    }
}

$conn->close();
?>
