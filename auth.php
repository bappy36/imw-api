<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

$action = $_POST['action'] ?? '';

if ($action == 'signup') {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $department = $_POST['department'];
    $access_type = $_POST['access_type'];
    $employee_id = $_POST['employee_id'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $pin = $_POST['pin'];

    $sql = "INSERT INTO users (first_name, last_name, department, access_type, employee_id, phone, email, password, pin) 
            VALUES ('$first_name', '$last_name', '$department', '$access_type', '$employee_id', '$phone', '$email', '$password', '$pin')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Account created"]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
}

if ($action == 'login') {
    $employee_id = $_POST['employee_id'];
    $pin = $_POST['pin'];

    $sql = "SELECT * FROM users WHERE employee_id = '$employee_id' AND pin = '$pin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        echo json_encode(["success" => true, "user" => $user]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid ID or PIN"]);
    }
}
$conn->close();
?>
