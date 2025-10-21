<?php
// 1. Yêu cầu đăng nhập (auth.php)
// Sửa lại đường dẫn: file auth.php nằm cùng cấp
require_once 'auth.php'; 

// 2. Kết nối CSDL (database.php)
// Sửa lại đường dẫn: file config nằm ở cấp cha (../)
require_once '../config/database.php';

// 3. Kiểm tra ID album từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_albums.php"); // Sửa lại: Quay về manage_albums.php
    exit();
}

$album_id = intval($_GET['id']);

// --- Bắt đầu quy trình xóa an toàn ---

// 4. Lấy tên file ảnh bìa TRƯỚC KHI xóa
$image_file = null;
$stmt = $conn->prepare("SELECT cover_image FROM albums WHERE id = ?");
$stmt->bind_param("i", $album_id);
$stmt->execute();
$result = $stmt->get_result();
if ($row = $result->fetch_assoc()) {
    $image_file = $row['cover_image'];
}
$stmt->close();

// 5. XÓA CÁC BÀI HÁT (tracks) liên quan
$stmt_tracks = $conn->prepare("DELETE FROM tracks WHERE album_id = ?");
$stmt_tracks->bind_param("i", $album_id);
$stmt_tracks->execute();
$stmt_tracks->close();

// 6. XÓA ALBUM khỏi database
$stmt_album = $conn->prepare("DELETE FROM albums WHERE id = ?");
$stmt_album->bind_param("i", $album_id);
$stmt_album->execute();
$stmt_album->close();

// 7. XÓA FILE ẢNH VẬT LÝ khỏi server
if ($image_file) {
    // Sửa lại đường dẫn: đi lên 1 cấp (../) rồi vào images/albums/
    $image_path = "../images/albums/" . $image_file; 
    if (file_exists($image_path)) {
        unlink($image_path); // Hàm unlink() dùng để xóa file
    }
}

// 8. Quay lại trang quản lý
header("Location: manage_albums.php"); // Sửa lại: Quay về manage_albums.php
exit();
?>