<?php
require_once '../auth.php';
require_once '../../config/database.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Bước 1: Lấy tên ảnh để xóa file
    $stmt = $conn->prepare("SELECT image FROM lore_entries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['image'])) {
            // Sửa đường dẫn thư mục ảnh
            $image_path = "../../images/lore/" . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
    }
    $stmt->close();

    // Bước 2: Xóa mục khỏi database
    $stmt = $conn->prepare("DELETE FROM lore_entries WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

header("Location: manage.php");
exit();
?>