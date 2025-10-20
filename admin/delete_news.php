<?php
require_once 'auth.php';
require_once '../config/database.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Lấy tên file ảnh để xóa
    $stmt = $conn->prepare("SELECT image FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['image'])) {
            unlink("../images/news/" . $row['image']);
        }
    }

    // Xóa bài viết khỏi CSDL
    $stmt = $conn->prepare("DELETE FROM news WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}
header("Location: manage_news.php");
exit();
?>