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
    $section = $_POST['section'] ?? '';
    $content = $_POST;
    
    try {
        // จัดการอัพโหลดรูปภาพ
        if (isset($_FILES['background_image']) && $_FILES['background_image']['error'] === 0) {
            $upload_dir = '../images/';
            $file_name = uniqid() . '_' . $_FILES['background_image']['name'];
            move_uploaded_file($_FILES['background_image']['tmp_name'], $upload_dir . $file_name);
            $content['background_image'] = 'images/' . $file_name;
        }

        // อัพเดทข้อมูลในฐานข้อมูล
        $stmt = $conn->prepare("UPDATE page_content SET content = ?, updated_at = NOW() WHERE section_name = ?");
        $content_json = json_encode($content, JSON_UNESCAPED_UNICODE);
        $stmt->bind_param("ss", $content_json, $section);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Update error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
} 