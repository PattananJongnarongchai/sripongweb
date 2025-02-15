<?php
require_once '../config/database.php';
session_start();

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'กรุณาเข้าสู่ระบบ']);
    exit();
}

$db = new Database();
$conn = $db->getConnection();

switch($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        try {
            $stmt = $conn->query("SELECT * FROM members ORDER BY created_at DESC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode(['status' => 'success', 'data' => $members]);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาดในการดึงข้อมูล']);
        }
        break;

    case 'POST':
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            $stmt = $conn->prepare("INSERT INTO members (name, email, phone, address) VALUES (?, ?, ?, ?)");
            $stmt->execute([$data['name'], $data['email'], $data['phone'], $data['address']]);
            
            echo json_encode([
                'status' => 'success', 
                'message' => 'เพิ่มสมาชิกเรียบร้อยแล้ว'
            ]);
        } catch(PDOException $e) {
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล'
            ]);
        }
        break;
}
?> 