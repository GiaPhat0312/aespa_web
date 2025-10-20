<?php
session_start();
// Nếu không tồn tại session user_id (chưa đăng nhập)
if (!isset($_SESSION['user_id'])) {
    // Chuyển hướng người dùng về lại trang login
    header("Location: ../login.php");
    exit();
}
?>