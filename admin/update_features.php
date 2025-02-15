<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์เข้าถึง']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $features = $_POST['features'] ?? [];
        
        // อัพเดทข้อมูลในฐานข้อมูล
        $stmt = $conn->prepare("UPDATE page_content SET content = ?, updated_at = NOW() WHERE section_name = 'features'");
        $features_json = json_encode($features, JSON_UNESCAPED_UNICODE);
        $stmt->bind_param("s", $features_json);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Update features error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
} 