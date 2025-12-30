<?php
header("Content-Type: application/json");
include 'setup.php'; // Database connection file

$sql = "SELECT * FROM orders ORDER BY id DESC";
$result = $conn->query($sql);

$orders = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $orders[] = $row;
    }
}
echo json_encode($orders);
?>