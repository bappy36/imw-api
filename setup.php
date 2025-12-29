<?php
// এরর ডিবাগিং চালু করা হলো
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Attempting to connect to database...<br>";

// আপনার রেলওয়ে ক্রেডেনশিয়াল (দয়া করে নিশ্চিত করুন এখানে কোনো টাইপো নেই)
$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
$db   = "railway";

// কানেকশন তৈরি (ভেরিয়েবলগুলো সব ছোট হাতের অক্ষরে নিশ্চিত করা হয়েছে)
$conn = new mysqli($host, $user, $pass, $db, $port);

// কানেকশন চেক
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!<br><br>";

// ১. users টেবিল তৈরি
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
$conn->query($sql_users);

// ২. attendance টেবিল তৈরি
$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(50) NOT NULL,
    user_name VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    check_in VARCHAR(20),
    check_out VARCHAR(20),
    status VARCHAR(50),
    UNIQUE KEY unique_daily_attendance (employee_id, date) 
)";
$conn->query($sql_attendance);

// ৩. orders টেবিল তৈরি (অর্ডার আপলোড করার জন্য)
$sql_orders = "CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    local_id INT,
    account_name VARCHAR(100),
    date DATE,
    time_of_deadline VARCHAR(100),
    order_code VARCHAR(100),
    fiverr_order_no VARCHAR(100),
    buyer_username VARCHAR(100),
    buyer_type VARCHAR(100),
    buyer_location VARCHAR(100),
    received_by VARCHAR(100),
    handled_by VARCHAR(100),
    status VARCHAR(100),
    project_deal_price VARCHAR(100),
    date_of_completion VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql_orders) === TRUE) {
    echo "All tables (users, attendance, orders) are ready!<br>";
} else {
    echo "Error creating tables: " . $conn->error . "<br>";
}

$conn->close();
echo "<br>Setup completed successfully.";
?>
