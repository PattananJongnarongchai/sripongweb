<?php
require_once 'config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    
    if (empty($name) || empty($phone)) {
        echo json_encode(['success' => false, 'message' => 'กรุณากรอกข้อมูลให้ครบถ้วน']);
        exit;
    }

    try {
        // เช็คว่าเคยลงทะเบียนด้วยเบอร์โทรนี้แล้วหรือไม่
        $stmt = $conn->prepare("SELECT id FROM promotion_registrations WHERE phone = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            echo json_encode(['success' => false, 'message' => 'เบอร์โทรนี้เคยลงทะเบียนแล้วในวันนี้']);
            exit;
        }

        // บันทึกข้อมูลการลงทะเบียน
        $stmt = $conn->prepare("INSERT INTO promotion_registrations (name, phone, email) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $email);
        
        if ($stmt->execute()) {
            echo json_encode(['success' => true]);
        } else {
            throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Registration error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
} 