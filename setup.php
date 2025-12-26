<?php
// এরর ডিবাগিং চালু করা হলো
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Attempting to connect to database...<br>";

// আপনার রেলওয়ে ক্রেডেনশিয়াল
$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

// কানেকশন তৈরি
$conn = new mysqli($host, $user, $pass, $db, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!<br>";

// users টেবিল তৈরির কুয়েরি
$sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    department VARCHAR(50),
    access_type VARCHAR(20),
    employee_id VARCHAR(50) UNIQUE,
    phone VARCHAR(20),
    email VARCHAR(100) UNIQUE,
    password VARCHAR(255),
    pin VARCHAR(4)
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'users' created successfully!";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>
