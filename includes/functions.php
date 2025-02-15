<?php
// ฟังก์ชันตรวจสอบสิทธิ์ admin
function isAdmin() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// ฟังก์ชันตรวจสอบการล็อกอิน
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// ฟังก์ชันแสดงข้อความแจ้งเตือน
function showAlert() {
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
        unset($_SESSION['error']);
    }
} 