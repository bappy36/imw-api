
echo "Attempting to connect to database...<br>";

// আপনার রেলওয়ে ক্রেডেনশিয়াল
$host = "centerbeam.proxy.rlwy.net";
// আপনার রেলওয়ে ক্রেডেনশিয়াল$host = "centerbeam.proxy.rlwy.net";
$port = 24312;
$user = "root";
$pass = "ADwnbbasvyjzFpIFcicWDVgZJtwKVNLY";
@@ -21,7 +20,7 @@
}
echo "Connected successfully!<br><br>";

// ১. users টেবিল তৈরির কুয়েরি
// ১. users টেবিল
$sql_users = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50),
@@ -41,7 +40,7 @@
    echo "Error creating 'users' table: " . $conn->error . "<br>";
}

// ২. attendance টেবিল তৈরির কুয়েরি (আপনার রিকোয়ারমেন্ট অনুযায়ী)
// ২. attendance টেবিল
$sql_attendance = "CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(50) NOT NULL,
@@ -61,6 +60,33 @@
    echo "Error creating 'attendance' table: " . $conn->error . "<br>";
}

// ৩. orders টেবিল (অর্ডার ডিটেইলস এর জন্য নতুন)
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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql_orders) === TRUE) {
    echo "Table 'orders' checked/created successfully!<br>";
} else {
    echo "Error creating 'orders' table: " . $conn->error . "<br>";
}

$conn->close();
echo "<br>Setup process completed.";
?>
