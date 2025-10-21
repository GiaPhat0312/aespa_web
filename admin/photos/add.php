<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = intval($_POST['member_id']);
    $caption = $_POST['caption'];
    $image_name = '';

    // Kiểm tra đã chọn thành viên chưa
    if (empty($member_id)) {
        $message = '<div class="message error">Vui lòng chọn thành viên.</div>';
    }
    // Kiểm tra đã chọn ảnh chưa
    else if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/photos/"; // Thư mục lưu ảnh gallery cá nhân
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

        $image_name = time() . '_' . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            // Upload ảnh thành công, lưu vào DB
            $stmt = $conn->prepare("INSERT INTO photos (member_id, caption, image_url) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $member_id, $caption, $image_name);

            if ($stmt->execute()) {
                header("Location: manage.php");
                exit();
            } else {
                $message = '<div class="message error">Lỗi database: ' . $stmt->error . '</div>';
            }
            $stmt->close();
        } else {
            $message = '<div class="message error">Lỗi upload file ảnh.</div>';
        }
    } else {
        $message = '<div class="message error">Vui lòng chọn một file ảnh.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Thêm Ảnh Mới | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="../../images/favicon.png">
</head>

<body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="../../videos/1021.mp4" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý</a></div>
        <div class="admin-header">
            <h1>Thêm Ảnh Mới Vào Gallery</h1>
        </div>
        <?= $message; ?>
        <form action="add.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="member_id">Gắn ảnh cho thành viên</label>
                        <select id="member_id" name="member_id" required>
                            <option value="">-- Chọn thành viên --</option>
                            <option value="1">Karina</option>
                            <option value="2">Giselle</option>
                            <option value="3">Winter</option>
                            <option value="4">Ningning</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caption">Chú thích (Tùy chọn)</label>
                        <input type="text" id="caption" name="caption" placeholder="Ví dụ: Sân bay Incheon 20/10...">
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Ảnh</h3>
                        <div class="image-preview" id="imagePreview">
                            <span class="image-preview-text">Xem trước</span>
                        </div>
                        <input type="file" id="image" name="image" class="image-input" accept="image/*" required>
                    </div>
                    <div class="sidebar-box">
                        <h3>Đăng Ảnh</h3>
                        <button type="submit" class="form-button">Lưu Ảnh</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../../js/image-preview.js"></script>
</body>

</html>