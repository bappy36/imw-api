<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

$action = $_POST['action'] ?? $_GET['action'] ?? '';

// ১. সাইনআপ (ডিফল্ট স্ট্যাটাস 'pending')
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
    $status = 'pending'; // নতুন ইউজার পেন্ডিং থাকবে

    $sql = "INSERT INTO users (first_name, last_name, department, access_type, employee_id, phone, email, password, pin, status) 
            VALUES ('$first_name', '$last_name', '$department', '$access_type', '$employee_id', '$phone', '$email', '$password', '$pin', '$status')";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "Registration successful. Waiting for admin approval."]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
}

// ২. লগইন
elseif ($action == 'login') {
    $employee_id = $_POST['employee_id'];
    $pin = $_POST['pin'];

    $sql = "SELECT * FROM users WHERE employee_id = '$employee_id' AND pin = '$pin'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // স্ট্যাটাসও রেসপন্সে পাঠাতে হবে যাতে অ্যাপ চেক করতে পারে
        echo json_encode([
            "success" => true, 
            "user" => [
                "employee_id" => $user['employee_id'],
                "first_name" => $user['first_name'],
                "last_name" => $user['last_name'],
                "department" => $user['department'],
                "access_type" => $user['access_type'],
                "status" => $user['status'] // 'pending' না 'approved' তা চেক করার জন্য
            ]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid ID or PIN"]);
    }
}

// ৩. এডমিনের জন্য পেন্ডিং ইউজার লিস্ট দেখা
elseif ($action == 'get_pending_users') {
    $sql = "SELECT first_name, last_name, employee_id, department FROM users WHERE status = 'pending'";
    $result = $conn->query($sql);
    
    $users = [];
    while($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode(["success" => true, "users" => $users]);
}

// ৪. ইউজার এপ্রুভ করা
elseif ($action == 'approve_user') {
    $employee_id = $_POST['employee_id'];
    $sql = "UPDATE users SET status = 'approved' WHERE employee_id = '$employee_id'";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "User approved successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => $conn->error]);
    }
}

// ৫. অটো চেক-আউট
elseif ($action == 'auto_check_out') {
    $employee_id = $_POST['employee_id'];
    $date = $_POST['date'];
    $check_out = $_POST['check_out'];
    $status = $_POST['status'];

    $sql = "UPDATE attendance SET check_out='$check_out', status='$status' 
            WHERE employee_id='$employee_id' AND date='$date' AND check_out IS NULL";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $conn->error]);
    }
}
// ৬. ইউজার আপডেট করা
elseif ($action == 'update_user') {
    $employee_id = $_POST['employee_id'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $department = $_POST['department'];
    $access_type = $_POST['access_type'];

    $sql = "UPDATE users SET 
            first_name = '$first_name', 
            last_name = '$last_name', 
            email = '$email', 
            phone = '$phone', 
            department = '$department', 
            access_type = '$access_type' 
            WHERE employee_id = '$employee_id'";

    if ($conn->query($sql)) {
        echo json_encode(["success" => true, "message" => "User updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Update failed: " . $conn->error]);
    }
}


$conn->close();
?>
