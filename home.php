<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
// Database connection
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'config/constants.php';
require_once 'includes/messenger.php';

// ฟังก์ชันสำหรับดึงข้อมูลผู้ใช้
function getUserData() {
    global $pdo;
    if (isLoggedIn()) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            echo "<script>
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการดึงข้อมูลผู้ใช้',
                    icon: 'error'
                });
            </script>";
            return null;
        }
    }
    return null;
}

// ดึงข้อมูลผู้ใช้ถ้ามีการล็อกอิน
$user = null;
if (isLoggedIn()) {
    $user = getUserData();
}

// ดึงข้อมูลโปรโมชั่น
$promotion = getActivePromotion();

// ดึงข้อมูลรูปภาพโปรโมชั่น
$promotionImages = [];
try {
    $stmt = $pdo->query("
        SELECT * FROM promotion_images 
        WHERE active = TRUE 
        ORDER BY created_at DESC
    ");
    $promotionImages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log("Error fetching promotion images: " . $e->getMessage());
}

// ฟังก์ชันสำหรับดึงข้อมูลโปรโมชั่น
function getPromotionImages($pdo) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM promotion_images 
            WHERE active = TRUE 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>
            Swal.fire({
                title: 'ข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการดึงข้อมูลโปรโมชั่น',
                icon: 'error'
            });
        </script>";
        return [];
    }
}

// เพิ่มฟังก์ชันสำหรับดึงข้อมูลโปรโมชั่นที่กำลังใช้งาน
function getActivePromotion() {
    global $pdo;
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM promotions 
            WHERE active = TRUE 
            AND start_date <= CURRENT_DATE 
            AND (end_date >= CURRENT_DATE OR end_date IS NULL)
            ORDER BY created_at DESC 
            LIMIT 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "<script>
            Swal.fire({
                title: 'ข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการดึงข้อมูลโปรโมชั่น',
                icon: 'error'
            });
        </script>";
        return null;
    }
}

// เพิ่มฟังก์ชันเพื่อดึงข้อมูลเนื้อหาต่างๆ
$heroContent = getPageContent('hero');
$featuresContent = getPageContent('features') ?? [
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
];

// เพิ่มฟังก์ชันนี้ต่อจากฟังก์ชันที่มีอยู่
function getPageContent($section_name) {
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT content FROM page_content WHERE section_name = ?");
        $stmt->execute([$section_name]);
        $data = $stmt->fetch();
        
        return $data ? json_decode($data['content'], true) : null;
    } catch (PDOException $e) {
        echo "<script>
            Swal.fire({
                title: 'ข้อผิดพลาด!',
                text: 'เกิดข้อผิดพลาดในการดึงข้อมูลเนื้อหา',
                icon: 'error'
            });
        </script>";
        return null;
    }
}

// เพิ่มฟังก์ชันนี้ในส่วนบนของไฟล์ หลังจาก require database
function formatPromotionPeriod($promo) {
    $start = isset($promo['start_date']) ? date('d/m/Y', strtotime($promo['start_date'])) : '';
    $end = isset($promo['end_date']) ? date('d/m/Y', strtotime($promo['end_date'])) : '';
    
    if ($start && $end) {
        return "ตั้งแต่ $start ถึง $end";
    } else if ($start) {
        return "เริ่ม $start";
    } else if ($end) {
        return "ถึง $end";
    }
    return "ไม่มีกำหนด";
}

// เพิ่มฟังก์ชันนี้หลังจากฟังก์ชัน formatPromotionPeriod
function getActivityImages() {
    global $pdo;
    $images = [];
    
    try {
        $stmt = $pdo->query("
            SELECT id, filename, title, description, upload_date 
            FROM gallery_images 
            WHERE active = 1 
            ORDER BY upload_date DESC
        ");
        while ($row = $stmt->fetch()) {
            $images[] = [
                'id' => $row['id'],
                'filename' => $row['filename'],
                'title' => $row['title'],
                'description' => $row['description']
            ];
        }
    } catch (PDOException $e) {
        error_log("Error getting images: " . $e->getMessage());
    }
    
    return $images;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ศรีพงษ์พาร์ค | สวนแห่งความสุข</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        @font-face {
            font-family: 'FC Defragment';
            src: url('./fonts/FC Defragment/FC Defragment Bold.ttf') format('truetype');
        }

        * {
            font-family: 'FC Defragment', sans-serif;
        }

        /* Hero Section */
        .hero {
            position: relative;
            background: url('./images/hero.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.4);
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-logo {
            width: 200px;
            margin-bottom: 2rem;
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }

        /* Features Section */
        .feature-card {
            padding: 2rem;
            border-radius: 15px;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            font-size: 3rem;
            color: #ff9800;
            margin-bottom: 1rem;
        }

        /* Promotion Section */
        .promotion-section {
            background: linear-gradient(135deg, #ff9800, #ff5722);
            color: white;
            padding: 4rem 0;
        }

        /* Gallery Section */
        .gallery-section {
            background: #f8f9fa;
            padding: 80px 0;
        }

        .swiper {
            width: 100%;
            padding-bottom: 50px;
        }

        .gallery-card {
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }

        .gallery-card img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .gallery-card:hover img {
            transform: scale(1.1);
        }

        .gallery-caption {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            color: white;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .gallery-card:hover .gallery-caption {
            opacity: 1;
        }

        .gallery-caption h5 {
            margin: 0;
            font-size: 1.1rem;
        }

        #galleryModal .gallery-card {
            cursor: pointer;
        }

        #galleryModal .modal-body {
            padding: 20px;
        }

        /* Swiper Navigation Styles */
        .swiper-button-next,
        .swiper-button-prev {
            color: #ff9800;
            background: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .swiper-button-next:after,
        .swiper-button-prev:after {
            font-size: 20px;
        }

        .swiper-pagination-bullet {
            background: #ff9800;
        }

        /* Contact Section */
        .contact-section {
            background: #f8f9fa;
            padding: 4rem 0;
        }

        .social-link {
            display: inline-block;
            width: 50px;
            height: 50px;
            line-height: 50px;
            text-align: center;
            border-radius: 50%;
            background: #ff9800;
            color: white;
            margin: 0 10px;
            transition: transform 0.3s ease;
        }

        .social-link:hover {
            transform: scale(1.1);
            color: white;
        }

        /* เพิ่มในส่วน style ที่มีอยู่ */
        html {
            scroll-behavior: smooth;
            scroll-padding-top: 80px; /* ให้เลื่อนแล้วไม่ทับ navbar */
        }

        /* ปรับแต่ง active state ของ nav-link */
        .nav-link.active {
            color: #ff9800 !important;
            font-weight: bold;
        }

        /* Admin Edit Styles */
        .admin-edit-btn {
            position: absolute;
            right: 10px;
            top: 10px;
            z-index: 100;
            display: none; /* ซ่อนไว้ก่อน */
        }

        .editable-section:hover .admin-edit-btn {
            display: block; /* แสดงเมื่อ hover */
        }

        .editable-section {
            position: relative;
        }

        /* Modal Styles for Edit Forms */
        .edit-modal .form-group {
            margin-bottom: 1rem;
        }

        .edit-modal .image-preview {
            max-width: 200px;
            margin-top: 10px;
        }

        .gallery-actions {
            position: absolute;
            top: 10px;
            right: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            gap: 5px;
        }

        .gallery-card:hover .gallery-actions {
            opacity: 1;
        }

        .gallery-actions button {
            padding: 5px 10px;
            z-index: 10;
            border-radius: 5px;
        }

        .promotion-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .promotion-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .promotion-card .btn {
            z-index: 2;
        }

        #promotion-image {
            width: 100%;
            height: auto;
            object-fit: contain;
        }

        .modal-body img {
            max-width: 100%;
            height: auto;
        }

        /* Map styles */
        .contact-section iframe {
            border-radius: 10px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }

        .ratio-16x9 {
            --bs-aspect-ratio: 56.25%;
            max-width: 800px;
            margin: 0 auto;
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 15px;
            border: 1px solid rgba(0, 0, 0, 0.1);
        }

        /* เพิ่มพื้นที่รอบๆ section แผนที่ */
        .contact-section .map-container {
            padding: 20px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 
                0 10px 20px rgba(0, 0, 0, 0.1),
                0 0 0 1px rgba(0, 0, 0, 0.05);
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <?php if (isLoggedIn()): ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="./images/logo.png" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
                    <?php if (isset($user) && isset($user['name'])): ?>
                        <span class="nav-item nav-link">ยินดีต้อนรับ, <?php echo htmlspecialchars($user['name']); ?></span>
                    <?php endif; ?>
                    <a class="nav-item nav-link" href="profile.php">โปรไฟล์</a>
                    <a class="nav-item nav-link" href="logout.php">ออกจากระบบ</a>
                </div>
            </div>
        </div>
    </nav>
    <?php else: ?>
    <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <img src="./images/logo.png" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">บริการ</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#promotions">โปรโมชั่น</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#gallery">แกลเลอรี่</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">ติดต่อเรา</a>
                    </li>
                </ul>
                <div class="navbar-nav">
                    <!-- <button class="btn btn-warning me-2" data-bs-toggle="modal" data-bs-target="#membershipModal">
                        สมัครสมาชิก
                    </button> -->
                    <a class="btn btn-outline-warning" href="#" data-bs-toggle="modal" data-bs-target="#loginModal">เข้าสู่ระบบ</a>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Hero Section -->
    <section id="home" class="hero editable-section" style="background-image: url('<?php echo $heroContent['background_image'] ?? './images/hero.jpg'; ?>')">
        <?php if (isAdmin()): ?>
        <button class="btn btn-warning admin-edit-btn" data-bs-toggle="modal" data-bs-target="#editHeroModal">
            <i class="fas fa-edit"></i> แก้ไข
        </button>
        <?php endif; ?>
        <div class="container text-center hero-content">
            <img src="./images/logo.png" alt="Logo" class="hero-logo">
            <h1 class="display-4 mb-4"><?php echo htmlspecialchars($heroContent['title'] ?? 'ยินดีต้อนรับสู่ศรีพงษ์พาร์ค'); ?></h1>
            <p class="lead mb-4"><?php echo htmlspecialchars($heroContent['description'] ?? 'สวนแห่งความสุขสำหรับทุกครอบครัว'); ?></p>
            <a href="#features" class="btn btn-warning btn-lg px-5">สำรวจเพิ่มเติม</a>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">บริการของเรา</h2>
            <div class="row g-4">
                <?php if ($featuresContent): foreach ($featuresContent as $feature): ?>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card text-center">
                        <div class="feature-icon"><?php echo htmlspecialchars($feature['icon']); ?></div>
                        <h4><?php echo htmlspecialchars($feature['title']); ?></h4>
                        <p><?php echo htmlspecialchars($feature['description']); ?></p>
                    </div>
                </div>
                <?php endforeach; endif; ?>
            </div>
        </div>
    </section>

    <!-- Promotions Section -->
    <section id="promotions" class="promotions-section py-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0" data-aos="fade-up">โปรโมชั่นพิเศษ</h2>
                <?php if (isAdmin()): ?>
                <button type="button" 
                        class="btn btn-primary d-flex align-items-center gap-2" 
                        data-bs-toggle="modal" 
                        data-bs-target="#managePromotionModal">
                    <i class="fas fa-cog"></i>
                    <span>จัดการโปรโมชั่น</span>
                </button>
                <?php endif; ?>
            </div>

            <!-- แสดงรายการโปรโมชั่น -->
            <div class="row g-4">
                <?php if (isset($promotionImages) && !empty($promotionImages)): ?>
                    <?php foreach ($promotionImages as $image): ?>
                    <div class="col-md-4" data-aos="fade-up">
                        <div class="promotion-card shadow-sm rounded overflow-hidden" 
                             style="cursor: pointer;"
                             onclick="showPromotionDetail(this, event)"
                             data-title="<?php echo htmlspecialchars($image['title']); ?>"
                             data-description="<?php echo htmlspecialchars($image['description']); ?>"
                             data-start="<?php echo date('d/m/Y', strtotime($image['start_date'])); ?>"
                             data-end="<?php echo date('d/m/Y', strtotime($image['end_date'])); ?>"
                             data-image="<?php echo './images/promotions/' . htmlspecialchars($image['filename']); ?>">
                            <div class="position-relative">
                                <img src="<?php echo './images/promotions/' . htmlspecialchars($image['filename']); ?>" 
                                     alt="<?php echo htmlspecialchars($image['title']); ?>"
                                     class="img-fluid w-100"
                                     style="height: 250px; object-fit: cover;">
                                <?php if (isAdmin()): ?>
                                <button type="button" 
                                        class="btn btn-sm btn-danger delete-promo" 
                                        data-id="<?php echo $image['id']; ?>">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                            <div class="p-3">
                                <h5 class="mb-2"><?php echo htmlspecialchars($image['title']); ?></h5>
                                <p class="text-muted mb-0">
                                    <small>
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        <?php 
                                        $start_date = new DateTime($image['start_date']);
                                        $end_date = new DateTime($image['end_date']);
                                        echo $start_date->format('d/m/Y H:i') . ' - ' . $end_date->format('d/m/Y H:i'); 
                                        ?>
                                    </small>
                                </p>
                                <?php if (!empty($image['description'])): ?>
                                <p class="mt-2 mb-0"><?php echo htmlspecialchars($image['description']); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center">
                        <p class="text-muted">ยังไม่มีโปรโมชั่นในขณะนี้</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Promotion Management Modal -->
    <?php if (isAdmin()): ?>
    <div class="modal fade" id="managePromotionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">จัดการโปรโมชั่น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <!-- Upload Form -->
                    <form id="addPromotionForm" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="upload">
                        <div class="mb-3">
                            <label class="form-label">ชื่อโปรโมชั่น</label>
                            <input type="text" class="form-control" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียด</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่เริ่มต้น</label>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <input type="date" class="form-control" name="start_date" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" class="form-control" name="start_time" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่สิ้นสุด</label>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <input type="date" class="form-control" name="end_date" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" class="form-control" name="end_time" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รูปภาพ</label>
                            <input type="file" class="form-control" name="image" accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </form>

                    <!-- Current Promotions -->
                    <div class="mt-4">
                        <h6>โปรโมชั่นทั้งหมด</h6>
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>รูปภาพ</th>
                                        <th>ชื่อโปรโมชั่น</th>
                                        <th>วันที่เริ่ม</th>
                                        <th>วันที่สิ้นสุด</th>
                                        <th>สถานะ</th>
                                        <th>จัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($promotionImages) && !empty($promotionImages)): ?>
                                        <?php foreach ($promotionImages as $promo): ?>
                                        <tr>
                                            <td>
                                                <img src="<?php echo './images/promotions/' . htmlspecialchars($promo['filename']); ?>" 
                                                     alt="<?php echo htmlspecialchars($promo['title']); ?>"
                                                     style="height: 50px; width: 50px; object-fit: cover;">
                                            </td>
                                            <td><?php echo htmlspecialchars($promo['title']); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($promo['start_date'])); ?></td>
                                            <td><?php echo date('Y-m-d H:i', strtotime($promo['end_date'])); ?></td>
                                            <td>
                                                <?php 
                                                $today = new DateTime();
                                                $end_date = new DateTime($promo['end_date']);
                                                $start_date = new DateTime($promo['start_date']);
                                                if ($today > $end_date) {
                                                    echo '<span class="badge bg-danger">หมดเวลา</span>';
                                                } elseif ($today < $start_date) {
                                                    echo '<span class="badge bg-warning">รอเริ่ม</span>';
                                                } else {
                                                    echo '<span class="badge bg-success">กำลังใช้งาน</span>';
                                                }
                                                ?>
                                            </td>
                                            <td>
                                                <button type="button" 
                                                        class="btn btn-sm btn-warning edit-promo"
                                                        data-id="<?php echo htmlspecialchars($promo['id']); ?>"
                                                        data-title="<?php echo htmlspecialchars($promo['title']); ?>"
                                                        data-description="<?php echo htmlspecialchars($promo['description']); ?>"
                                                        data-start="<?php echo date('Y-m-d H:i', strtotime($promo['start_date'])); ?>"
                                                        data-end="<?php echo date('Y-m-d H:i', strtotime($promo['end_date'])); ?>">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" 
                                                        class="btn btn-sm btn-danger delete-promo" 
                                                        data-id="<?php echo $promo['id']; ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center">ไม่พบข้อมูลโปรโมชั่น</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Promotion Modal -->
    <div class="modal fade" id="editPromotionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขโปรโมชั่น</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editPromotionForm" enctype="multipart/form-data">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">ชื่อโปรโมชั่น</label>
                            <input type="text" class="form-control" name="title" id="edit_title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รายละเอียด</label>
                            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่เริ่มต้น</label>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <input type="date" class="form-control" name="start_date" id="edit_start_date" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" class="form-control" name="start_time" id="edit_start_time" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">วันที่สิ้นสุด</label>
                                    <div class="row">
                                        <div class="col-md-7">
                                            <input type="date" class="form-control" name="end_date" id="edit_end_date" required>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="time" class="form-control" name="end_time" id="edit_end_time" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รูปภาพใหม่ (ไม่จำเป็น)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <script>
    // ฟังก์ชันสำหรับจัดการ modal backdrop
    function removeModalBackdrop() {
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }
        document.body.style.overflow = '';
        document.body.classList.remove('modal-open');
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Edit Buttons
        document.querySelectorAll('.edit-promo').forEach(button => {
            button.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('editPromotionModal'));
                
                // แยกวันที่และเวลา
                const startDateTime = new Date(this.dataset.start);
                const endDateTime = new Date(this.dataset.end);
                
                // Fill form with promotion data
                document.getElementById('edit_id').value = this.dataset.id;
                document.getElementById('edit_title').value = this.dataset.title;
                document.getElementById('edit_description').value = this.dataset.description;
                document.getElementById('edit_start_date').value = startDateTime.toISOString().split('T')[0];
                document.getElementById('edit_start_time').value = startDateTime.toTimeString().slice(0,5);
                document.getElementById('edit_end_date').value = endDateTime.toISOString().split('T')[0];
                document.getElementById('edit_end_time').value = endDateTime.toTimeString().slice(0,5);
                
                modal.show();
            });
        });

        // Initialize Full Image View
        const fullImageModal = new bootstrap.Modal(document.getElementById('fullImageModal'));
        const fullImage = document.getElementById('fullImage');

        // Add click event to all gallery images
        document.querySelectorAll('.gallery-card img').forEach(img => {
            img.addEventListener('click', function(e) {
                e.stopPropagation(); // ป้องกันการ bubble ของ event
                fullImage.src = this.src;
                fullImageModal.show();
            });
        });

        // Prevent image click when clicking edit/delete buttons
        document.querySelectorAll('.gallery-actions button').forEach(button => {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        });

        // Initialize AOS
        AOS.init();
    });
    </script>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery-section py-5 bg-light">
        <div class="container">
            <h2 class="text-center mb-5" data-aos="fade-up">ภาพกิจกรรม</h2>
            
            <?php if (isAdmin()): ?>
            <div class="admin-controls mb-4">
                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#manageGalleryModal">
                    <i class="fas fa-images"></i> จัดการรูปภาพ
                </button>
            </div>
            <?php endif; ?>

            <!-- Swiper -->
            <div class="swiper gallerySwiper" data-aos="fade-up">
                <div class="swiper-wrapper">
                    <?php
                    $activityImages = getActivityImages();
                    if ($activityImages):
                        foreach ($activityImages as $image):
                    ?>
                    <div class="swiper-slide">
                        <div class="gallery-card">
                            <img src="<?php echo './images/activity/' . htmlspecialchars($image['filename']); ?>" 
                                 alt="<?php echo htmlspecialchars($image['title']); ?>"
                                 onclick="showFullImage(this.src)"
                                 loading="lazy">
                        </div>
                    </div>
                    <?php 
                        endforeach;
                    else:
                    ?>
                    <div class="text-center w-100">
                        <p>ยังไม่มีรูปภาพกิจกรรม</p>
                    </div>
                    <?php endif; ?>
                </div>
                
                <!-- Add Navigation -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
                
                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>

            <?php if ($activityImages): ?>
            <!-- ปุ่มดูรูปภาพทั้งหมด -->
            <div class="text-center mt-5">
                <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#galleryModal">
                    ดูรูปภาพทั้งหมด
                </button>
            </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Promotion Section -->
    <?php if ($promotion && !empty($promotion)): ?>
    <section class="promotion-section">
        <div class="container text-center">
            <h2 class="mb-4">🎉 <?php echo htmlspecialchars($promotion['title']); ?></h2>
            <p class="lead mb-4"><?php echo htmlspecialchars($promotion['description']); ?></p>
            <button class="btn btn-light btn-lg" data-bs-toggle="modal" data-bs-target="#membershipModal">
                รับสิทธิพิเศษ
            </button>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container text-center">
            <h2 class="mb-5">ติดต่อเรา</h2>
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <p class="lead mb-4">
                        📍 ศรีพงษ์พาร์ค อุตรดิตถ์<br>
                        📞 082-644-6466<br>
                        ✉️ sriponggroup2010@gmail.com
                    </p>
                    <div class="social-links mt-4">
                        <a href="https://line.me/R/ti/p/%40sriponggroup" class="social-link">
                            <i class="fab fa-line"></i>
                        </a>
                        <a href="https://www.facebook.com/SripongG" class="social-link">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    </div>
                    <div class="map-container">
                        <h4 class="mb-4">แผนที่ศรีพงษ์พาร์ค</h4>
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3802.2944085361496!2d100.08682097517068!3d17.636209783294056!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30df302fb1c6ff0b%3A0x9bc1394475f0dea7!2z4Lio4Lij4Lie4Lie4LiH4Lip4LmM4Lie4Liy4Lij4LmM4LiE!5e0!3m2!1sth!2sth!4v1739437198397!5m2!1sth!2sth" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                        <p class="mt-3 text-muted">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            เลขที่ 123 ถ.พาดวารี ต.ท่าอิฐ อ.เมือง จ.อุตรดิตถ์ 53000
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Membership Modal -->
    <div class="modal fade" id="membershipModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">รับสิทธิพิเศษ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="membershipForm">
                        <div class="mb-3">
                            <label class="form-label">ชื่อ-นามสกุล</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">อีเมล (ไม่บังคับ)</label>
                            <input type="email" class="form-control" name="email">
                        </div>
                        <button type="submit" class="btn btn-warning w-100">รับสิทธิพิเศษ</button>
                        <div class="mt-3 small text-muted text-center">
                            * เราจะส่งรายละเอียดโปรโมชั่นไปยังเบอร์โทรศัพท์ของท่าน
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Modal -->
    <div class="modal fade" id="loginModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เข้าสู่ระบบ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="loginForm">
                        <div class="mb-3">
                            <label class="form-label">อีเมล</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รหัสผ่าน</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100 mb-3">เข้าสู่ระบบ</button>
                        <div class="text-center">
                            <small>ยังไม่มีบัญชี? <a href="#" data-bs-toggle="modal" data-bs-target="#membershipModal" data-bs-dismiss="modal">สมัครสมาชิก</a></small>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal fade" id="galleryModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แกลเลอรี่ภาพกิจกรรม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-4">
                        <?php foreach ($activityImages as $image): ?>
                        <div class="col-md-4">
                            <div class="gallery-card">
                                <img src="<?php echo './images/activity/' . htmlspecialchars($image['filename']); ?>" 
                                     alt="<?php echo htmlspecialchars($image['title']); ?>"
                                     onclick="showFullImage(this.src)"
                                     class="img-fluid">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Management Modal -->
    <div class="modal fade" id="manageGalleryModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">จัดการรูปภาพกิจกรรม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="uploadImagesForm" class="mb-4">
                        <div class="mb-3">
                            <label class="form-label">อัพโหลดรูปภาพ</label>
                            <input type="file" class="form-control" name="images[]" multiple accept="image/*" required>
                        </div>
                        <button type="submit" class="btn btn-primary">อัพโหลด</button>
                    </form>
                    
                    <div class="current-images">
                        <h6>รูปภาพทั้งหมด</h6>
                        <div class="row g-3" id="imagesList">
                            <?php foreach ($activityImages as $image): ?>
                            <div class="col-md-4">
                                <div class="gallery-card">
                                    <img src="<?php echo './images/activity/' . htmlspecialchars($image['filename']); ?>" 
                                         alt="<?php echo htmlspecialchars($image['title']); ?>"
                                         onclick="showFullImage(this.src)"
                                         class="img-fluid">
                                    <div class="gallery-actions">
                                        <button class="btn btn-warning btn-sm edit-image" 
                                                data-id="<?php echo htmlspecialchars($image['id']); ?>"
                                                data-title="<?php echo htmlspecialchars($image['title']); ?>"
                                                data-description="<?php echo htmlspecialchars($image['description'] ?? ''); ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn btn-danger btn-sm delete-image" 
                                                data-filename="<?php echo htmlspecialchars($image['filename']); ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">แก้ไขข้อมูลรูปภาพ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editImageForm">
                        <input type="hidden" name="action" value="edit">
                        <input type="hidden" name="id" id="edit_image_id">
                        <div class="mb-3">
                            <label class="form-label">ชื่อรูปภาพ</label>
                            <input type="text" class="form-control" name="title" id="edit_image_title" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">คำอธิบาย</label>
                            <textarea class="form-control" name="description" id="edit_image_description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">รูปภาพใหม่ (ไม่จำเป็น)</label>
                            <input type="file" class="form-control" name="image" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Full Image Modal -->
    <div class="modal fade" id="fullImageModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content bg-transparent border-0">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img src="" id="fullImage" class="img-fluid" style="max-height: 90vh;">
                </div>
            </div>
        </div>
    </div>

    <!-- Promotion Detail Modal -->
    <div class="modal fade" id="promotionDetailModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promotion-title"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <img id="promotion-image" src="" alt="" class="img-fluid rounded" style="max-height: 400px;">
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h6 class="text-muted mb-3">
                                <i class="fas fa-calendar-alt me-2"></i>
                                ระยะเวลาโปรโมชั่น: <span id="promotion-period"></span>
                            </h6>
                            <div id="promotion-description" class="mb-4"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Facebook SDK -->
    <div id="fb-root"></div>
    <script>
        window.fbAsyncInit = function() {
            FB.init({
                xfbml: true,
                version: 'v18.0'
            });
        };
    </script>
    <script async defer crossorigin="anonymous" 
        src="https://connect.facebook.net/th_TH/sdk/xfbml.customerchat.js">
    </script>
    
    <?php echo renderMessengerPlugin(); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/c3bc32620a.js" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    // ฟังก์ชันสำหรับแสดงรายละเอียดโปรโมชั่น
    function showPromotionDetail(element, event) {
        // ป้องกันการ trigger event เมื่อคลิกที่ปุ่มแก้ไข/ลบ
        if (event.target.closest('.btn')) {
            return;
        }

        const modal = new bootstrap.Modal(document.getElementById('promotionDetailModal'));
        
        // ดึงข้อมูลจาก data attributes
        document.getElementById('promotion-title').textContent = element.dataset.title;
        document.getElementById('promotion-description').textContent = element.dataset.description;
        document.getElementById('promotion-period').textContent = 
            `${element.dataset.start} - ${element.dataset.end}`;
        document.getElementById('promotion-image').src = element.dataset.image;
        
        modal.show();
    }

    // เพิ่มฟังก์ชัน helper สำหรับแสดง error
    function showError(message) {
        Swal.fire({
            title: 'ข้อผิดพลาด!',
            text: message,
            icon: 'error'
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize AOS
        AOS.init();

        // Navigation
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        window.addEventListener('scroll', () => {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (pageYOffset >= (sectionTop - 150)) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href && href.slice(1) === current) {
                    link.classList.add('active');
                }
            });
        });

        // Close mobile menu when clicking links
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                const navbarCollapse = document.querySelector('.navbar-collapse');
                if (navbarCollapse?.classList.contains('show')) {
                    bootstrap.Collapse.getInstance(navbarCollapse)?.hide();
                }
            });
        });

        // Initialize Swiper
        new Swiper('.gallerySwiper', {
            slidesPerView: 1,
            spaceBetween: 30,
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                640: { slidesPerView: 2 },
                1024: { slidesPerView: 3 }
            }
        });

        // Forms
        const membershipForm = document.getElementById('membershipForm');
        membershipForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('register_promotion.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showSuccess('ลงทะเบียนรับสิทธิพิเศษสำเร็จ! เราจะส่งรายละเอียดไปยังเบอร์โทรศัพท์ของท่าน');
                } else {
                    showError(data.message || 'เกิดข้อผิดพลาด');
                }
            });
        });

        const loginForm = document.getElementById('loginForm');
        loginForm?.addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('auth/login.php', {
                method: 'POST',
                body: new FormData(this)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    showError(data.message || 'อีเมลหรือรหัสผ่านไม่ถูกต้อง');
                }
            });
        });

        // Image Upload and Management
        if (document.getElementById('uploadImagesForm')) {
            document.getElementById('uploadImagesForm').addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                formData.append('action', 'upload');

                try {
                    const response = await fetch('admin/manage_gallery.php', {
                        method: 'POST',
                        body: formData
                    });

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }

                    const data = await response.json();
                    
                    if (data.success) {
                        await Swal.fire({
                            title: data.title,
                            text: data.message,
                            icon: data.icon
                        });
                        location.reload();
                    } else {
                        Swal.fire({
                            title: data.title,
                            text: data.message,
                            icon: data.icon
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'ข้อผิดพลาด!',
                        text: 'เกิดข้อผิดพลาดในการอัพโหลด: ' + error.message,
                        icon: 'error'
                    });
                }
            });

            // Edit Image Buttons
            document.querySelectorAll('.edit-image').forEach(button => {
                button.addEventListener('click', function() {
                    const modal = new bootstrap.Modal(document.getElementById('editImageModal'));
                    
                    // Fill form with image data
                    document.getElementById('edit_image_id').value = this.dataset.id;
                    document.getElementById('edit_image_title').value = this.dataset.title;
                    document.getElementById('edit_image_description').value = this.dataset.description;
                    
                    modal.show();
                });
            });
        }

        // Edit Image Form Handler
        document.getElementById('editImageForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch('admin/manage_gallery.php', {
                    method: 'POST',
                    body: new FormData(this)
                });

                const data = await response.json();
                
                if (data.success) {
                    await Swal.fire({
                        title: data.title || 'สำเร็จ!',
                        text: data.message,
                        icon: data.icon || 'success'
                    });
                    
                    // ปิด modal ก่อนที่จะ reload หน้า
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editImageModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    location.reload();
                } else {
                    Swal.fire({
                        title: data.title || 'ข้อผิดพลาด!',
                        text: data.message,
                        icon: data.icon || 'error'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล',
                    icon: 'error'
                });
            }
        });

        // Promotion Management
        document.querySelectorAll('form[action="admin/manage_promotion.php"]').forEach(form => {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                try {
                    const formData = new FormData(this);
                    
                    // ถ้าเป็นการลบ ให้แสดง SweetAlert2 ถามก่อน
                    if (formData.get('action') === 'delete') {
                        const result = await Swal.fire({
                            title: 'ยืนยันการลบ?',
                            text: "คุณต้องการลบโปรโมชั่นนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'ใช่, ลบเลย!',
                            cancelButtonText: 'ยกเลิก'
                        });
                        
                        if (!result.isConfirmed) {
                            return;
                        }
                    }

                    const response = await fetch(this.action, {
                        method: 'POST',
                        body: formData
                    });

                    const data = await response.json();
                    
                    if (data.success) {
                        await Swal.fire({
                            title: data.title || 'สำเร็จ!',
                            text: data.message,
                            icon: data.icon || 'success'
                        });
                        
                        // ปิด modal ถ้ามี
                        const modalElement = this.closest('.modal');
                        if (modalElement) {
                            const modal = bootstrap.Modal.getInstance(modalElement);
                            if (modal) {
                                modal.hide();
                            }
                        }
                        
                        // รีโหลดหน้าเว็บ
                        setTimeout(() => {
                            window.location.href = window.location.pathname;
                        }, 500);
                    } else {
                        Swal.fire({
                            title: data.title || 'ข้อผิดพลาด!',
                            text: data.message || 'เกิดข้อผิดพลาดในการดำเนินการ',
                            icon: data.icon || 'error'
                        });
                    }
                } catch (error) {
                    console.error('Error:', error);
                    Swal.fire({
                        title: 'ข้อผิดพลาด!',
                        text: 'เกิดข้อผิดพลาดในการดำเนินการ กรุณาลองใหม่อีกครั้ง',
                        icon: 'error'
                    });
                }
            });
        });

        // เพิ่ม event listener สำหรับการปิด modal
        ['managePromotionModal', 'editPromotionModal', 'manageGalleryModal', 'editImageModal'].forEach(modalId => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('hidden.bs.modal', function () {
                    removeModalBackdrop();
                });
            }
        });

        // Edit Promotion Form Handler
        document.getElementById('editPromotionForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('admin/manage_promotion.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                if (data.success) {
                    await Swal.fire({
                        title: data.title || 'สำเร็จ!',
                        text: data.message,
                        icon: data.icon || 'success'
                    });
                    
                    // ปิด modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editPromotionModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // รีโหลดหน้าเว็บ
                    window.location.reload();
                } else {
                    Swal.fire({
                        title: data.title || 'ข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล',
                        icon: data.icon || 'error'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล กรุณาลองใหม่อีกครั้ง',
                    icon: 'error'
                });
            }
        });

        // Add Promotion Form Handler
        document.getElementById('addPromotionForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const formData = new FormData(this);
                
                const response = await fetch('admin/manage_promotion.php', {
                    method: 'POST',
                    body: formData
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                
                if (data.success) {
                    await Swal.fire({
                        title: data.title || 'สำเร็จ!',
                        text: data.message,
                        icon: data.icon || 'success'
                    });
                    
                    // ปิด modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById('managePromotionModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // รีโหลดหน้าเว็บ
                    setTimeout(() => {
                        window.location.href = window.location.pathname;
                    }, 500);
                } else {
                    Swal.fire({
                        title: data.title || 'ข้อผิดพลาด!',
                        text: data.message || 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล',
                        icon: data.icon || 'error'
                    });
                }
            } catch (error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'ข้อผิดพลาด!',
                    text: 'เกิดข้อผิดพลาดในการเพิ่มข้อมูล กรุณาลองใหม่อีกครั้ง',
                    icon: 'error'
                });
            }
        });

        // Delete Promotion Handler
        document.querySelectorAll('.delete-promo').forEach(button => {
            button.addEventListener('click', async function() {
                const result = await Swal.fire({
                    title: 'ยืนยันการลบ?',
                    text: "คุณต้องการลบโปรโมชั่นนี้ใช่หรือไม่? การกระทำนี้ไม่สามารถย้อนกลับได้",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ใช่, ลบเลย!',
                    cancelButtonText: 'ยกเลิก'
                });

                if (result.isConfirmed) {
                    try {
                        const formData = new FormData();
                        formData.append('action', 'delete');
                        formData.append('id', this.dataset.id);

                        const response = await fetch('admin/manage_promotion.php', {
                            method: 'POST',
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            await Swal.fire({
                                title: data.title || 'สำเร็จ!',
                                text: data.message,
                                icon: data.icon || 'success'
                            });
                            location.reload();
                        } else {
                            throw new Error(data.message || 'เกิดข้อผิดพลาดในการลบข้อมูล');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire({
                            title: 'ข้อผิดพลาด!',
                            text: error.message,
                            icon: 'error'
                        });
                    }
                }
            });
        });
    });
    </script>
</body>
</html> 