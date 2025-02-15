<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Debug session
echo "Session data in admin.php:<br>";
var_dump($_SESSION);

// ตรวจสอบ session อย่างละเอียด
if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    echo "<br>Session invalid or empty. Redirecting to login...<br>";
    // ล้าง session เก่า
    session_destroy();
    // Redirect ด้วย JavaScript
    echo "<script>window.location.href = 'login.php';</script>";
    exit();
}

require_once 'config/database.php';

// เชื่อมต่อฐานข้อมูล
$db = new Database();
$conn = $db->getConnection();

// ดึงข้อมูลสถิติ
$stats = [
    'members' => $conn->query("SELECT COUNT(*) FROM members")->fetchColumn(),
    'active_promotions' => $conn->query("SELECT COUNT(*) FROM promotions WHERE status = 'active'")->fetchColumn(),
    'new_reviews' => $conn->query("SELECT COUNT(*) FROM testimonials WHERE status = 'pending'")->fetchColumn(),
    'gallery_images' => $conn->query("SELECT COUNT(*) FROM gallery")->fetchColumn()
];

// ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่
$user = $conn->query("SELECT * FROM users WHERE id = " . $_SESSION['user_id'])->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการเนื้อหาเว็บไซต์ - Sripong Park</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- เพิ่ม TinyMCE สำหรับแก้ไขข้อความแบบ Rich Text -->
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea.rich-editor',
            height: 300,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
        });
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">ระบบจัดการเว็บไซต์</a>
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="logout.php">ออกจากระบบ</a>
            </div>
        </div>
    </nav>

    <div class="container my-4">
        <h2>จัดการเนื้อหาหน้าแรก</h2>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <?php foreach ($contents as $content): ?>
                <div class="mb-4">
                    <label class="form-label">
                        <?php
                        $label = str_replace('_', ' ', $content['section_name']);
                        echo ucwords($label);
                        ?>
                    </label>
                    <textarea 
                        name="content_<?php echo $content['section_name']; ?>" 
                        class="form-control rich-editor"
                    ><?php echo htmlspecialchars($content['content']); ?></textarea>
                </div>
            <?php endforeach; ?>
            
            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 