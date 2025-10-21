<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

// Lấy ID ảnh từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage.php"); 
    exit();
}
$photo_id = intval($_GET['id']);

// Xử lý khi nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = intval($_POST['member_id']);
    $caption = $_POST['caption'];
    $old_image = $_POST['old_image'];
    $image_name = $old_image;

    // Xử lý nếu có upload ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/photos/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            // Xóa ảnh cũ
            if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        } else {
            $message = '<div class="message error">Lỗi upload ảnh mới.</div>';
            $image_name = $old_image; // Nếu lỗi thì giữ lại ảnh cũ
        }
    }

    // Cập nhật database
    if (empty($message)) {
        $stmt = $conn->prepare("UPDATE photos SET member_id = ?, caption = ?, image_url = ? WHERE id = ?");
        $stmt->bind_param("issi", $member_id, $caption, $image_name, $photo_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật ảnh thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi database: ' . $stmt->error . '</div>';
        }
    }
}

// Lấy thông tin hiện tại của ảnh để hiển thị trong form
$stmt = $conn->prepare("SELECT member_id, caption, image_url FROM photos WHERE id = ?");
$stmt->bind_param("i", $photo_id);
$stmt->execute();
$result = $stmt->get_result();
$photo = $result->fetch_assoc();
if (!$photo) { // Nếu không tìm thấy ảnh
    header("Location: manage.php"); 
    exit(); 
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Ảnh | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="../../images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý</a></div>
        <div class="admin-header"><h1>Sửa Ảnh Gallery</h1></div>
        <?= $message; ?>
        <form action="edit.php?id=<?= $photo_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="member_id">Gắn ảnh cho thành viên</label>
                        <select id="member_id" name="member_id" required>
                            <option value="1" <?= $photo['member_id'] == 1 ? 'selected' : '' ?>>Karina</option>
                            <option value="2" <?= $photo['member_id'] == 2 ? 'selected' : '' ?>>Giselle</option>
                            <option value="3" <?= $photo['member_id'] == 3 ? 'selected' : '' ?>>Winter</option>
                            <option value="4" <?= $photo['member_id'] == 4 ? 'selected' : '' ?>>Ningning</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caption">Chú thích</label>
                        <input type="text" id="caption" name="caption" value="<?= htmlspecialchars($photo['caption']) ?>">
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Ảnh Đại Diện</h3>
                        <div class="image-preview" id="imagePreview">
                            <img src="../../images/photos/<?= htmlspecialchars($photo['image_url']) ?>" class="image-preview-image" style="display:block;">
                        </div>
                        <input type="file" id="image" name="image" class="image-input" accept="image/*">
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($photo['image_url']) ?>">
                        <small>Chọn ảnh mới nếu bạn muốn thay thế.</small>
                    </div>
                    <div class="sidebar-box">
                        <h3>Cập nhật</h3>
                        <button type="submit" class="form-button">Lưu Thay Đổi</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../../js/image-preview.js"></script>
</body>
</html>