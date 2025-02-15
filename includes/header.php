<?php
session_start();
require_once 'config/database.php';

// ตรวจสอบการล็อกอิน
if (!isset($_SESSION['user_id']) && !strpos($_SERVER['REQUEST_URI'], 'login.php')) {
    header('Location: login.php');
    exit();
}

$database = new Database();
$db = $database->getConnection();
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Sripong Park</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet">
    <link href="assets/css/admin.css" rel="stylesheet">
    <meta property="fb:app_id" content="your_app_id" />
    <meta property="fb:pages" content="<?php echo FACEBOOK_PAGE_ID; ?>" />
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container-fluid">
            <button class="btn btn-dark" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>
            <a class="navbar-brand ms-2" href="#">Sripong Park Admin</a>
            
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user fa-fw"></i>
                        <?php echo $_SESSION['username'] ?? 'Admin'; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#profileModal">
                            <i class="fas fa-user fa-sm fa-fw me-2"></i>โปรไฟล์
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" id="logoutBtn">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i>ออกจากระบบ
                        </a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Wrapper -->
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="active">
            <div class="sidebar-header">
                <img src="assets/images/logo.png" alt="Logo" class="img-fluid">
            </div>
            
            <ul class="list-unstyled components">
                <li class="active">
                    <a href="#dashboard" class="nav-link">
                        <i class="fas fa-tachometer-alt"></i>
                        <span>แดชบอร์ด</span>
                    </a>
                </li>
                <li>
                    <a href="#members" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>จัดการสมาชิก</span>
                    </a>
                </li>
                <li>
                    <a href="#promotions" class="nav-link">
                        <i class="fas fa-tags"></i>
                        <span>โปรโมชั่น</span>
                    </a>
                </li>
                <li>
                    <a href="#testimonials" class="nav-link">
                        <i class="fas fa-star"></i>
                        <span>รีวิว</span>
                    </a>
                </li>
                <li>
                    <a href="#gallery" class="nav-link">
                        <i class="fas fa-images"></i>
                        <span>แกลเลอรี่</span>
                    </a>
                </li>
                <li>
                    <a href="#settings" class="nav-link">
                        <i class="fas fa-cog"></i>
                        <span>ตั้งค่า</span>
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">