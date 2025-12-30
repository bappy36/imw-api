<?php
header("Content-Type: application/json");
include 'setup.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $account_name = $data['account_name'];
    $date = $data['date'];
    $status = $data['status'];
    // Bakita apnar table columns onujayi hobe

    $sql = "INSERT INTO orders (account_name, date, status) VALUES ('$account_name', '$date', '$status')";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
