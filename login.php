<?php
// เริ่มต้น session และเคลียร์ค่าเก่า
session_start();
session_destroy();
session_start();

require_once 'config/database.php';

// เพิ่ม debug
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ถ้ามีการ submit form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $db = new Database();
    $conn = $db->getConnection();

    try {
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Debug user data
        echo "User data from database:<br>";
        var_dump($user);
        echo "<br>";

        if ($user && password_verify($password, $user['password'])) {
            // เซ็ต session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            
            // Debug session after set
            echo "Session after set:<br>";
            var_dump($_SESSION);
            echo "<br>";

            // ตรวจสอบว่า session ถูกเซ็ตจริงๆ
            if(isset($_SESSION['user_id'])) {
                echo "Session set successfully, redirecting...<br>";
                header("Location: admin.php");
                exit();
            } else {
                $error = "เกิดข้อผิดพลาดในการสร้าง session";
            }
        } else {
            $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง";
        }
    } catch(PDOException $e) {
        $error = "เกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล: " . $e->getMessage();
    }
}

// Debug current session
echo "Current session data:<br>";
var_dump($_SESSION);

// ทดสอบการเชื่อมต่อฐานข้อมูล
$db = new Database();
try {
    $conn = $db->getConnection();
    if($conn) {
        echo "สถานะการเชื่อมต่อฐานข้อมูล: เชื่อมต่อสำเร็จ<br>";
        
        // ทดสอบการ query ตาราง users
        $stmt = $conn->query("SELECT COUNT(*) as total FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "จำนวนผู้ใช้ในระบบ: " . $result['total'] . "<br>";
    }
} catch(PDOException $e) {
    echo "สถานะการเชื่อมต่อฐานข้อมูล: ล้มเหลว<br>";
    echo "Error: " . $e->getMessage() . "<br>";
    die();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - Sripong Park</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-center mb-4">เข้าสู่ระบบผู้ดูแล</h2>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="mb-3">
                    <label for="username" class="form-label">ชื่อผู้ใช้</label>
                    <input type="text" class="form-control" id="username" name="username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">รหัสผ่าน</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">เข้าสู่ระบบ</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 