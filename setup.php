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
echo "Connected successfully!<br><br>";

// ১. users টেবিল তৈরির কুয়েরি
$sql_users = "CREATE TABLE IF NOT EXISTS users (
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

if ($conn->query($sql_users) === TRUE) {
    echo "Table 'users' checked/created successfully!<br>";
} else {
    echo "Error creating 'users' table: " . $conn->error . "<br>";
}

// ২. attendance টেবিল তৈরির কুয়েরি (আপনার রিকোয়ারমেন্ট অনুযায়ী)
$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(50) NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    department VARCHAR(100),
    date DATE NOT NULL,
    check_in VARCHAR(20),
    check_out VARCHAR(20),
    status VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_daily_attendance (employee_id, date) 
)";

if ($conn->query($sql_attendance) === TRUE) {
    echo "Table 'attendance' checked/created successfully!<br>";
} else {
    echo "Error creating 'attendance' table: " . $conn->error . "<br>";
}

$conn->close();
echo "<br>Setup process completed.";
?>
