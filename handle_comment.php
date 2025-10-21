<?php
require_once 'config/database.php';
session_start(); // Dùng session để lưu thông báo

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Lấy dữ liệu an toàn
    $page_type = $_POST['page_type'];
    $item_id = intval($_POST['item_id']);
    $author_name = trim($_POST['author_name']);
    $comment_text = trim($_POST['comment_text']);

    // 2. Kiểm tra dữ liệu cơ bản
    if (empty($author_name) || empty($comment_text) || empty($page_type) || $item_id <= 0) {
        $_SESSION['comment_error'] = "Vui lòng nhập đầy đủ tên và nội dung bình luận.";
    } else {
        // 3. Chuẩn bị và lưu vào database
        $stmt = $conn->prepare("INSERT INTO comments (page_type, item_id, author_name, comment_text) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siss", $page_type, $item_id, $author_name, $comment_text);

        if (!$stmt->execute()) {
            $_SESSION['comment_error'] = "Có lỗi xảy ra, vui lòng thử lại.";
        }
        $stmt->close();
    }
}

// 4. Quay lại trang trước đó
header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?>