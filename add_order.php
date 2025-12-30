<?php
header("Content-Type: application/json");header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

include 'setup.php';

// JSON data receive kora
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($data) {
    // Flutter theke asha keys gulo database column-er sathe map kora
    $account_name = $conn->real_escape_string($data['account_name'] ?? '');
    $date = $conn->real_escape_string($data['date'] ?? '');
    $time_of_deadline = $conn->real_escape_string($data['time_of_deadline'] ?? '');
    $order_code = $conn->real_escape_string($data['order_code'] ?? '');
    $fiverr_order_no = $conn->real_escape_string($data['fiverr_order_no'] ?? '');
    $buyer_username = $conn->real_escape_string($data['buyer_username'] ?? '');
    $buyer_type = $conn->real_escape_string($data['buyer_type'] ?? '');
    $buyer_location = $conn->real_escape_string($data['buyer_location'] ?? '');
    $received_by = $conn->real_escape_string($data['received_by'] ?? '');
    $handled_by = $conn->real_escape_string($data['handled_by'] ?? '');
    $status = $conn->real_escape_string($data['status'] ?? 'Not Assigned');
    $project_deal_price = $conn->real_escape_string($data['project_deal_price'] ?? '');
    $date_of_completion = $conn->real_escape_string($data['date_of_completion'] ?? '');

    $sql = "INSERT INTO orders (account_name, date, time_of_deadline, order_code, fiverr_order_no, buyer_username, buyer_type, buyer_location, received_by, handled_by, status, project_deal_price, date_of_completion) 
            VALUES ('$account_name', '$date', '$time_of_deadline', '$order_code', '$fiverr_order_no', '$buyer_username', '$buyer_type', '$buyer_location', '$received_by', '$handled_by', '$status', '$project_deal_price', '$date_of_completion')";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success", "id" => $conn->insert_id]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid JSON data received"]);
}

$conn->close();
?>
