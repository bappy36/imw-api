<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost"; 
$db_name = "আপনার_ডাটাবেস_নাম";
$username = "আপনার_ইউজারনাম";
$password = "আপনার_পাসওয়ার্ড";

try {
    $conn = new PDO("mysql:host=" . $host . ";dbname=" . $db_name, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT first_name, last_name FROM users"; 
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);

} catch(PDOException $e) {
    echo json_encode(array("error" => $e->getMessage()));
}
?>
