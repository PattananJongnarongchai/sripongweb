<?php
try {
    $host = 'localhost';
    $dbname = 'sripongweb';
    $username = 'root';
    $password = 'qlalf789513';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    error_log("Connection failed: " . $e->getMessage());
    die("Connection failed: " . $e->getMessage());
}

// สร้างฐานข้อมูลถ้ายังไม่มี
$sql = "CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
$pdo->exec($sql);

// เลือกฐานข้อมูล
$pdo->exec("USE $dbname");

// ตั้งค่า charset
$pdo->exec("SET NAMES utf8mb4");

// สร้างตารางที่จำเป็น
$tables = [
    "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        role ENUM('admin', 'user') DEFAULT 'user',
        points INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS promotions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        start_date DATE,
        end_date DATE,
        is_new BOOLEAN DEFAULT TRUE,
        active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS activities (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        image_url VARCHAR(255),
        event_date DATE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE IF NOT EXISTS points_transactions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        points INT NOT NULL,
        transaction_type ENUM('earn', 'redeem') NOT NULL,
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",
    
    "CREATE TABLE IF NOT EXISTS page_content (
        id INT AUTO_INCREMENT PRIMARY KEY,
        section_name VARCHAR(100) NOT NULL UNIQUE,
        title VARCHAR(255),
        content TEXT,
        image_url VARCHAR(255),
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS gallery_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        filename VARCHAR(255) NOT NULL,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        active BOOLEAN DEFAULT TRUE,
        upload_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )",

    "CREATE TABLE IF NOT EXISTS promotion_images (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        description TEXT,
        filename VARCHAR(255) NOT NULL,
        start_date DATETIME,
        end_date DATETIME,
        active BOOLEAN DEFAULT TRUE,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )"
];

// สร้างตาราง
foreach ($tables as $sql) {
    $pdo->exec($sql);
}

// เพิ่มข้อมูลเริ่มต้น (ถ้ายังไม่มี)
$initial_data = [
    // เพิ่มผู้ดูแลระบบเริ่มต้น
    "INSERT INTO users (name, email, password, phone, role) 
     SELECT 'Admin', 'admin@sripongpark.com', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', '0826446466', 'admin'
     WHERE NOT EXISTS (SELECT 1 FROM users WHERE email = 'admin@sripongpark.com')",

    // เพิ่มโปรโมชั่นเริ่มต้น
    "INSERT INTO promotions (title, description, active) 
     SELECT 'ลด 20% สำหรับเครื่องเขียน', 'เมื่อซื้อสินค้าครบ 500 บาท รับส่วนลดทันที', 1
     WHERE NOT EXISTS (SELECT 1 FROM promotions WHERE title = 'โปรโมชั่นต้อนรับสมาชิกใหม่')",

    // เพิ่มเนื้อหาหน้าเว็บเริ่มต้น
    "INSERT INTO page_content (section_name, title, content) 
     SELECT 'hero', 'ยินดีต้อนรับสู่ศรีพงษ์ปาร์ค', 'สวนแห่งความสุขสำหรับทุกครอบครัว'
     WHERE NOT EXISTS (SELECT 1 FROM page_content WHERE section_name = 'hero')",

    // เพิ่มข้อมูล Hero Section
    "INSERT INTO page_content (section_name, title, content) 
     SELECT 'hero', 'Hero Section', '" . json_encode([
         'title' => 'ยินดีต้อนรับสู่ศรีพงษ์ปาร์ค',
         'description' => 'สวนแห่งความสุขสำหรับทุกครอบครัว',
         'background_image' => './images/hero.jpg'
     ], JSON_UNESCAPED_UNICODE) . "'
     WHERE NOT EXISTS (SELECT 1 FROM page_content WHERE section_name = 'hero')",

    // เพิ่มข้อมูล Features Section
    "INSERT INTO page_content (section_name, title, content) 
     SELECT 'features', 'Features Section', '" . json_encode([
         [
             'icon' => '📚',
             'title' => 'ร้านหนังสือ & เครื่องเขียน',
             'description' => 'ครบครันด้วยหนังสือและอุปกรณ์การเรียนคุณภาพ'
         ],
         [
             'icon' => '🎨',
             'title' => 'กิจกรรมสร้างสรรค์',
             'description' => 'เวิร์คช็อปและกิจกรรมสนุกๆ สำหรับทุกวัย'
         ],
         [
             'icon' => '🎁',
             'title' => 'โปรโมชั่นพิเศษ',
             'description' => 'ข้อเสนอสุดพิเศษและส่วนลดมากมาย'
         ]
     ], JSON_UNESCAPED_UNICODE) . "'
     WHERE NOT EXISTS (SELECT 1 FROM page_content WHERE section_name = 'features')"
];

// เพิ่มข้อมูลเริ่มต้น
foreach ($initial_data as $sql) {
    $pdo->exec($sql);
}

// ฟังก์ชันสำหรับการจัดการข้อผิดพลาด
function handleDatabaseError($error) {
    error_log("Database Error: " . $error);
    return ['success' => false, 'message' => 'เกิดข้อผิดพลาดกับฐานข้อมูล กรุณาลองใหม่อีกครั้ง'];
}

class Database {
    private $host = "localhost";
    private $db_name = "sripongweb";
    private $username = "root";
    private $password = "qlalf789513";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // ทดสอบการเชื่อมต่อ
            $test = $this->conn->query("SELECT 1");
            if($test) {
                echo "เชื่อมต่อฐานข้อมูลสำเร็จ!<br>";
            }
            
        } catch(PDOException $e) {
            echo "การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $e->getMessage() . "<br>";
            die();
        }

        return $this->conn;
    }

    // เพิ่มฟังก์ชันทดสอบการเชื่อมต่อ
    public function testConnection() {
        try {
            $conn = $this->getConnection();
            echo "รายละเอียดการเชื่อมต่อ:<br>";
            echo "Host: " . $this->host . "<br>";
            echo "Database: " . $this->db_name . "<br>";
            echo "Username: " . $this->username . "<br>";
            
            // ทดสอบการ query
            $stmt = $conn->query("SHOW TABLES");
            echo "ตารางในฐานข้อมูล:<br>";
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print_r($row);
                echo "<br>";
            }
            
            return true;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }
}

// ทดสอบการเชื่อมต่อทันที
if(isset($_GET['test'])) {
    $db = new Database();
    $db->testConnection();
}
?> 