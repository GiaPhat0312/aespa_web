<?php
require_once '../auth.php';
require_once '../../config/database.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Bước 1: Lấy tên ảnh để xóa file
    $stmt = $conn->prepare("SELECT image_url FROM photos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['image_url'])) {
            $image_path = "../../images/photos/" . $row['image_url']; // Sửa đúng đường dẫn
            if (file_exists($image_path)) {
                unlink($image_path); // Xóa file ảnh vật lý
            }
        }
    }
    $stmt->close();

    // Bước 2: Xóa mục khỏi database
    $stmt = $conn->prepare("DELETE FROM photos WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Quay lại trang quản lý
header("Location: manage.php");
exit();
?>