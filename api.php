<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// ডেটাবেস কানেকশন
$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Connection failed']);
    exit();
}

$action = $_POST['action'] ?? '';

// ১. সকল অর্ডার সার্ভার থেকে আনা
if ($action == 'get_all_orders') {
    $sql = "SELECT * FROM orders ORDER BY date DESC";
    $result = $conn->query($sql);
    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[] = [
            'id' => $row['id'],
            'local_id' => (int)$row['local_id'],
            'account_name' => $row['account_name'],
            'date' => $row['date'],
            'time_of_deadline' => $row['time_of_deadline'],
            'order_code' => $row['order_code'],
            'fiverr_order_no' => $row['fiverr_order_no'],
            'buyer_username' => $row['buyer_username'],
            'buyer_type' => $row['buyer_type'],
            'buyer_location' => $row['buyer_location'],
            'received_by' => $row['received_by'],
            'handled_by' => $row['handled_by'],
            'status' => $row['status'],
            'project_deal_price' => $row['project_deal_price'],
            'date_of_completion' => $row['date_of_completion']
        ];
    }
    echo json_encode($orders);
}

// ২. লোকাল থেকে সার্ভারে অর্ডার সিঙ্ক করা
elseif ($action == 'sync_order') {
    $local_id = $_POST['local_id'];
    $account_name = $_POST['account_name'];
    
    // ডাটা রিসিভ করা (যেহেতু JSON ডাটা অ্যাপ থেকে ম্যাপ হিসেবে আসছে)
    $date = $_POST['date'] ?? '';
    $time_of_deadline = $_POST['time_of_deadline'] ?? '';
    $order_code = $_POST['order_code'] ?? '';
    $fiverr_order_no = $_POST['fiverr_order_no'] ?? '';
    $buyer_username = $_POST['buyer_username'] ?? '';
    $buyer_type = $_POST['buyer_type'] ?? '';
    $buyer_location = $_POST['buyer_location'] ?? '';
    $received_by = $_POST['received_by'] ?? '';
    $handled_by = $_POST['handled_by'] ?? '';
    $status = $_POST['status'] ?? '';
    $project_deal_price = $_POST['project_deal_price'] ?? '';
    $date_of_completion = $_POST['date_of_completion'] ?? '';

    // চেক করা অর্ডারটি আগে থেকেই আছে কিনা
    $check_sql = "SELECT id FROM orders WHERE local_id = '$local_id' AND account_name = '$account_name'";
    $check_res = $conn->query($check_sql);

    if ($check_res->num_rows > 0) {
        // আপডেট করা
        $sql = "UPDATE orders SET 
                date='$date', time_of_deadline='$time_of_deadline', order_code='$order_code',
                fiverr_order_no='$fiverr_order_no', buyer_username='$buyer_username',
                buyer_type='$buyer_type', buyer_location='$buyer_location', 
                received_by='$received_by', handled_by='$handled_by', status='$status',
                project_deal_price='$project_deal_price', date_of_completion='$date_of_completion'
                WHERE local_id = '$local_id' AND account_name = '$account_name'";
    } else {
        // নতুন ইনসার্ট করা
        $sql = "INSERT INTO orders (local_id, account_name, date, time_of_deadline, order_code, fiverr_order_no, buyer_username, buyer_type, buyer_location, received_by, handled_by, status, project_deal_price, date_of_completion) 
                VALUES ('$local_id', '$account_name', '$date', '$time_of_deadline', '$order_code', '$fiverr_order_no', '$buyer_username', '$buyer_type', '$buyer_location', '$received_by', '$handled_by', '$status', '$project_deal_price', '$date_of_completion')";
    }

    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
}

$conn->close();
?>
