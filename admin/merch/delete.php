<?php
// Đường dẫn đúng cho cấu trúc thư mục mới
require_once '../auth.php';
require_once '../../config/database.php';

// Kiểm tra xem ID có tồn tại và hợp lệ không
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    // Bước 1: Lấy tên file ảnh để xóa file vật lý
    $stmt = $conn->prepare("SELECT image FROM merchandise WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        if (!empty($row['image'])) {
            // Đường dẫn đúng để xóa ảnh, đi lên 2 cấp về thư mục gốc
            $image_path = "../../images/merch/" . $row['image'];
            if (file_exists($image_path)) {
                unlink($image_path); // Xóa file ảnh
            }
        }
    }
    $stmt->close();

    // Bước 2: Xóa vật phẩm khỏi cơ sở dữ liệu
    $stmt = $conn->prepare("DELETE FROM merchandise WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Chuyển hướng về trang quản lý trong cùng thư mục
header("Location: manage.php");
exit();
?>