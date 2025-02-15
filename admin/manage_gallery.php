<?php
session_start();
require_once '../config/database.php';

header('Content-Type: application/json');

// ตรวจสอบสิทธิ์ admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'ไม่มีสิทธิ์เข้าถึง']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'upload':
            handleUpload();
            break;
        case 'delete':
            handleDelete();
            break;
        case 'edit':
            handleEdit();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handleUpload() {
    global $pdo;
    
    if (!isset($_FILES['images'])) {
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => 'กรุณาเลือกไฟล์รูปภาพ',
            'icon' => 'error'
        ]);
        return;
    }

    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if (!$pdo) {
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้ กรุณาลองใหม่อีกครั้ง',
            'icon' => 'error'
        ]);
        return;
    }

    $upload_dir = '../images/activity/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $success_count = 0;
    $errors = [];

    try {
        // เตรียม SQL statement
        $stmt = $pdo->prepare("
            INSERT INTO gallery_images (
                filename,
                title,
                description,
                active
            ) VALUES (?, ?, ?, 1)
        ");
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            if ($_FILES['images']['error'][$key] === 0) {
                $original_filename = $_FILES['images']['name'][$key];
                $file_extension = pathinfo($original_filename, PATHINFO_EXTENSION);
                $new_filename = uniqid() . '.' . $file_extension;
                $title = pathinfo($original_filename, PATHINFO_FILENAME);
                
                if (move_uploaded_file($tmp_name, $upload_dir . $new_filename)) {
                    try {
                        if ($stmt->execute([
                            $new_filename,
                            $title,
                            '', // description เป็นค่าว่าง
                        ])) {
                            $success_count++;
                        } else {
                            $errors[] = $original_filename;
                            unlink($upload_dir . $new_filename);
                        }
                    } catch (PDOException $e) {
                        $errors[] = $original_filename;
                        unlink($upload_dir . $new_filename);
                        error_log("Database error details: " . $e->getMessage());
                    }
                } else {
                    $errors[] = $original_filename;
                    error_log("Failed to move uploaded file: " . error_get_last()['message']);
                }
            } else {
                error_log("File upload error code: " . $_FILES['images']['error'][$key]);
            }
        }

        if ($success_count > 0) {
            echo json_encode([
                'success' => true,
                'title' => 'สำเร็จ!',
                'message' => "อัพโหลดสำเร็จ $success_count รูป" . 
                            (count($errors) > 0 ? "\nผิดพลาด " . count($errors) . " รูป" : ""),
                'icon' => 'success'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'title' => 'ข้อผิดพลาด!',
                'message' => 'ไม่สามารถอัพโหลดรูปภาพได้ กรุณาลองใหม่อีกครั้ง',
                'icon' => 'error'
            ]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode([
            'success' => false,
            'title' => 'ข้อผิดพลาด!',
            'message' => 'เกิดข้อผิดพลาดในการบันทึกข้อมูล กรุณาลองใหม่อีกครั้ง',
            'icon' => 'error'
        ]);
    }
}

function handleDelete() {
    global $pdo;
    
    $filename = $_POST['filename'] ?? '';
    if (empty($filename)) {
        echo json_encode(['success' => false, 'message' => 'ไม่พบชื่อไฟล์']);
        return;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM gallery_images WHERE filename = ?");
        if ($stmt->execute([$filename])) {
            $file_path = '../images/activity/' . basename($filename);
            if (file_exists($file_path)) {
                unlink($file_path);
            }
            echo json_encode(['success' => true]);
        } else {
            throw new PDOException('ไม่สามารถลบข้อมูลจากฐานข้อมูลได้');
        }
    } catch (PDOException $e) {
        error_log("Delete error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'ไม่สามารถลบข้อมูลได้']);
    }
}

function handleEdit() {
    global $pdo;
    
    try {
        if (empty($_POST['id'])) {
            throw new Exception('ไม่พบข้อมูลรูปภาพ');
        }

        $sql = "UPDATE gallery_images SET 
                title = ?, 
                description = ?";
        $params = [
            $_POST['title'],
            $_POST['description'] ?? ''
        ];

        // ถ้ามีการอัพโหลดรูปภาพใหม่
        if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
            $file = $_FILES['image'];
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid() . '.' . $ext;
            $upload_path = '../images/activity/' . $filename;

            if (move_uploaded_file($file['tmp_name'], $upload_path)) {
                $sql .= ", filename = ?";
                $params[] = $filename;

                // ลบรูปเก่า
                $stmt = $pdo->prepare("SELECT filename FROM gallery_images WHERE id = ?");
                $stmt->execute([$_POST['id']]);
                $old_image = $stmt->fetchColumn();
                if ($old_image) {
                    $old_path = '../images/activity/' . $old_image;
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
                'message' => 'อัพเดทข้อมูลรูปภาพเรียบร้อยแล้ว',
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