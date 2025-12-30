<?php
header("Content-Type: application/json");
include 'setup.php';

$data = json_decode(file_get_contents("php://input"), true);

if ($data && isset($data['id'])) {
    $id = $data['id'];
    unset($data['id']); // ID update field theke soriye fela

    $update_parts = [];
    foreach ($data as $key => $value) {
        $update_parts[] = "$key = '$value'";
    }
    $update_string = implode(", ", $update_parts);

    $sql = "UPDATE orders SET $update_string WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => $conn->error]);
    }
}
?>
