<?php
session_start();
require_once '../config/database.php';
require_once '../includes/functions.php';

header('Content-Type: application/json; charset=utf-8');

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode([
        'success' => false,
        'title' => 'ข้อผิดพลาด!',
        'message' => 'ไม่มีสิทธิ์เข้าถึง',
        'icon' => 'error'
    ]);
    exit;
}

// ลบโปรโมชั่นที่หมดอายุอัตโนมัติ
$stmt = $pdo->prepare("UPDATE promotion_images SET active = FALSE WHERE end_date < CURRENT_DATE");
$stmt->execute();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'upload':
            handleUpload();
            break;
        case 'edit':
            handleEdit();
            break;
        case 'delete':
            handleDelete();
            break;
        default:
            echo json_encode([
                'success' => false,
                'title' => 'ข้อผิดพลาด!',
                'message' => 'Invalid action',
                'icon' => 'error'
            ]);
    }
}

function handleUpload() {
    global $pdo;
    
    try {
        // ตรวจสอบข้อมูลที่จำเป็น
        $required_fields = ['title', 'start_date', 'start_time', 'end_date', 'end_time'];
        foreach ($required_fields as $field) {
            if (empty($_POST[$field])) {
                throw new Exception('กรุณากรอกข้อมูลให้ครบถ้วน');
            }
        }

        // รวมวันที่และเวลา
        $start_datetime = $_POST['start_date'] . ' ' . $_POST['start_time'];
        $end_datetime = $_POST['end_date'] . ' ' . $_POST['end_time'];

        // ตรวจสอบไฟล์รูปภาพ
        if (!isset($_FILES['image']) || $_FILES['image']['error'] !== 0) {
            throw new Exception('กรุณาเลือกรูปภาพ');
        }

        // สร้างชื่อไฟล์ใหม่
        $file = $_FILES['image'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $upload_path = '../images/promotions/' . $filename;

        // ตรวจสอบและสร้างโฟลเดอร์ถ้ายังไม่มี
        if (!file_exists('../images/promotions/')) {
            mkdir('../images/promotions/', 0777, true);
        }

        // อัพโหลดไฟล์
        if (!move_uploaded_file($file['tmp_name'], $upload_path)) {
            throw new Exception('ไม่สามารถอัพโหลดไฟล์ได้');
        }

        // บันทึกข้อมูลลงฐานข้อมูล
        $stmt = $pdo->prepare("
            INSERT INTO promotion_images (title, description, filename, start_date, end_date, active) 
            VALUES (?, ?, ?, ?, ?, 1)
        ");

        if ($stmt->execute([
            $_POST['title'],
            $_POST['description'] ?? '',
            $filename,
            $start_datetime,
            $end_datetime
        ])) {
            echo json_encode([
                'success' => true,
                'title' => 'สำเร็จ!',
                'message' => 'เพิ่มโปรโมชั่นเรียบร้อยแล้ว',
                'icon' => 'success'
            ]);
        } else {
            throw new Exception('ไม่สามารถบันทึกข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Upload error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => $e->getMessage(),
            'icon' => 'error'
        ]);
    }
}

function handleEdit() {
    global $pdo;
    
    try {
        if (empty($_POST['id'])) {
            throw new Exception('ไม่พบข้อมูลโปรโมชั่น');
        }

        // รวมวันที่และเวลา
        $start_datetime = $_POST['start_date'] . ' ' . $_POST['start_time'];
        $end_datetime = $_POST['end_date'] . ' ' . $_POST['end_time'];

        $sql = "UPDATE promotion_images SET 
                title = ?, 
                description = ?, 
                start_date = ?, 
                end_date = ?";
        $params = [
            $_POST['title'],
            $_POST['description'] ?? '',
            $start_datetime,
            $end_datetime
        ];

        // ถ้ามีการอัพโหลดรูปภาพใหม่
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_path = '../images/promotions/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $sql .= ", filename = ?";
                $params[] = $filename;

                // ลบรูปเก่า
                $stmt = $pdo->prepare("SELECT filename FROM promotion_images WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $old_image = $stmt->fetchColumn();
                if ($old_image) {
                    $old_path = '../images/promotions/' . $old_image;
                    if (file_exists($old_path)) {
                        unlink($old_path);
                    }
                }
            }
        }

        $sql .= " WHERE id = ?";
        $params[] = $_POST['id'];

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute($params)) {
            echo json_encode([
                'success' => true,
                'title' => 'สำเร็จ!',
                'message' => 'อัพเดทโปรโมชั่นเรียบร้อยแล้ว',
                'icon' => 'success'
            ]);
        } else {
            throw new Exception('ไม่สามารถอัพเดทข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Edit error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => $e->getMessage(),
            'icon' => 'error'
        ]);
    }
}

function handleDelete() {
    global $pdo;
    
    try {
        if (empty($_POST['id'])) {
            throw new Exception('ไม่พบข้อมูลโปรโมชั่น');
        }

        // ลบรูปภาพ
        $stmt = $pdo->prepare("SELECT filename FROM promotion_images WHERE id = ?");
        $stmt->execute([$_POST['id']]);
        $image = $stmt->fetchColumn();

        if ($image) {
            $file_path = '../images/promotions/' . $image;
            if (file_exists($file_path)) {
                unlink($file_path);
            }
        }

        // ลบข้อมูลจากฐานข้อมูล
        $stmt = $pdo->prepare("DELETE FROM promotion_images WHERE id = ?");
        if ($stmt->execute([$_POST['id']])) {
            echo json_encode([
                'success' => true,
                'title' => 'สำเร็จ!',
                'message' => 'ลบโปรโมชั่นเรียบร้อยแล้ว',
                'icon' => 'success'
            ]);
        } else {
            throw new Exception('ไม่สามารถลบข้อมูลได้');
        }
    } catch (Exception $e) {
        error_log("Delete error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => $e->getMessage(),
            'icon' => 'error'
        ]);
    }
}

// เพิ่มฟังก์ชันดึงข้อมูลโปรโมชั่น
function getPromotions($pdo) {
    $stmt = $pdo->query("
        SELECT * FROM promotion_images 
        WHERE active = TRUE 
        ORDER BY created_at DESC
    ");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
} 