<?php
session_start();
require_once 'config/database.php';
require_once 'includes/functions.php';
require_once 'includes/db_functions.php';

// ตรวจสอบการล็อกอินทันที
if (!isLoggedIn()) {
    header('Location: home.php');
    exit;
}

try {
    $user = getUserProfile($pdo, $_SESSION['user_id']);
    $pointHistory = [];
    if ($user['total_transactions'] > 0) {
        $pointHistory = getPointHistory($pdo, $_SESSION['user_id']);
    }
} catch (PDOException $e) {
    error_log("Database error: " . $e->getMessage());
    header('Location: error.php');
    exit;
}

// Cache control headers
header('Cache-Control: private, must-revalidate');
header('Expires: -1');
header('Pragma: no-cache');
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ - ศรีพงษ์พาร์ค</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Kanit', sans-serif;
        }
        .profile-section {
            background: white;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 2rem;
            margin-top: 2rem;
        }
        .points-card {
            background: linear-gradient(45deg, #FFD700, #FFA500);
            color: white;
            border-radius: 10px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .transaction-item {
            border-left: 3px solid;
            padding-left: 1rem;
            margin-bottom: 1rem;
        }
        .transaction-item.earn {
            border-color: #28a745;
        }
        .transaction-item.redeem {
            border-color: #dc3545;
        }
        .nav-pills .nav-link.active {
            background-color: #ffc107;
            color: black;
        }
        .nav-pills .nav-link {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="home.php">
                <img src="./images/logo.png" alt="Logo" height="40">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="home.php">หน้าแรก</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">ออกจากระบบ</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-section">
                    <div class="text-center mb-4">
                        <img src="https://via.placeholder.com/150" class="rounded-circle mb-3" alt="Profile Picture">
                        <h4><?php echo htmlspecialchars($user['name']); ?></h4>
                        <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                    </div>
                    <div class="points-card">
                        <h5 class="mb-2">คะแนนสะสม</h5>
                        <h2 class="mb-0"><?php echo number_format($user['points']); ?> คะแนน</h2>
                    </div>
                    <div class="list-group">
                        <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="list">
                            <i class="fas fa-user me-2"></i> ข้อมูลส่วนตัว
                        </a>
                        <a href="#points" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fas fa-star me-2"></i> ประวัติคะแนน
                        </a>
                        <a href="#settings" class="list-group-item list-group-item-action" data-bs-toggle="list">
                            <i class="fas fa-cog me-2"></i> ตั้งค่า
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="tab-content">
                    <!-- ข้อมูลส่วนตัว -->
                    <div class="tab-pane fade show active" id="profile">
                        <div class="profile-section">
                            <h4 class="mb-4">ข้อมูลส่วนตัว</h4>
                            <form id="updateProfileForm">
                                <div class="mb-3">
                                    <label class="form-label">ชื่อ-นามสกุล</label>
                                    <input type="text" class="form-control" name="name" 
                                           value="<?php echo htmlspecialchars($user['name']); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" name="email" 
                                           value="<?php echo htmlspecialchars($user['email']); ?>" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">เบอร์โทรศัพท์</label>
                                    <input type="tel" class="form-control" name="phone" 
                                           value="<?php echo htmlspecialchars($user['phone']); ?>">
                                </div>
                                <button type="submit" class="btn btn-warning">บันทึกการเปลี่ยนแปลง</button>
                            </form>
                        </div>
                    </div>

                    <!-- ประวัติคะแนน -->
                    <div class="tab-pane fade" id="points">
                        <div class="profile-section">
                            <h4 class="mb-4">ประวัติการใช้คะแนน</h4>
                            <?php if (!empty($pointHistory)): ?>
                                <?php foreach ($pointHistory as $transaction): ?>
                                    <div class="transaction-item <?php echo $transaction['transaction_type']; ?>">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($transaction['description']); ?></h6>
                                                <small class="text-muted">
                                                    <?php echo date('d/m/Y H:i', strtotime($transaction['created_at'])); ?>
                                                </small>
                                            </div>
                                            <div class="<?php echo $transaction['transaction_type'] === 'earn' ? 'text-success' : 'text-danger'; ?>">
                                                <?php echo $transaction['transaction_type'] === 'earn' ? '+' : '-'; ?>
                                                <?php echo number_format($transaction['points']); ?> คะแนน
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-muted text-center">ยังไม่มีประวัติการใช้คะแนน</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- ตั้งค่า -->
                    <div class="tab-pane fade" id="settings">
                        <div class="profile-section">
                            <h4 class="mb-4">เปลี่ยนรหัสผ่าน</h4>
                            <form id="changePasswordForm">
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่านปัจจุบัน</label>
                                    <input type="password" class="form-control" name="current_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">รหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" name="new_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                                    <input type="password" class="form-control" name="confirm_password" required>
                                </div>
                                <button type="submit" class="btn btn-warning">เปลี่ยนรหัสผ่าน</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // อัพเดทโปรไฟล์
            document.getElementById('updateProfileForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                try {
                    const formData = new FormData(this);
                    const response = await fetch('update_profile.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        await Swal.fire({
                            title: 'สำเร็จ!',
                            text: 'อัพเดทข้อมูลเรียบร้อยแล้ว',
                            icon: 'success'
                        });
                        location.reload();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'ข้อผิดพลาด!',
                        text: error.message || 'เกิดข้อผิดพลาดในการอัพเดทข้อมูล',
                        icon: 'error'
                    });
                }
            });

            // เปลี่ยนรหัสผ่าน
            document.getElementById('changePasswordForm')?.addEventListener('submit', async function(e) {
                e.preventDefault();
                try {
                    const formData = new FormData(this);
                    const response = await fetch('change_password.php', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await response.json();
                    if (data.success) {
                        await Swal.fire({
                            title: 'สำเร็จ!',
                            text: 'เปลี่ยนรหัสผ่านเรียบร้อยแล้ว',
                            icon: 'success'
                        });
                        this.reset();
                    } else {
                        throw new Error(data.message);
                    }
                } catch (error) {
                    Swal.fire({
                        title: 'ข้อผิดพลาด!',
                        text: error.message || 'เกิดข้อผิดพลาดในการเปลี่ยนรหัสผ่าน',
                        icon: 'error'
                    });
                }
            });
        });
    </script>
</body>
</html> 