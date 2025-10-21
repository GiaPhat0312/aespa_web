<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sửa lại tên biến
    $term = $_POST['term'];
    $summary = $_POST['summary'];
    $full_description = $_POST['full_description'];
    $image_name = '';

    // Xử lý ảnh (giữ nguyên)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/lore/"; // Thư mục ảnh mới
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            $message = '<div class="message error">Lỗi upload ảnh.</div>';
        }
    } else {
        $message = '<div class="message error">Vui lòng chọn ảnh đại diện.</div>';
    }

    if (empty($message)) {
        // Sửa lại SQL
        $stmt = $conn->prepare("INSERT INTO lore_entries (term, summary, full_description, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $term, $summary, $full_description, $image_name);
        
        if ($stmt->execute()) {
            header("Location: manage.php");
            exit();
        } else {
            $message = '<div class="message error">Lỗi: ' . $stmt->error . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>Thêm Mục Lore | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="../js/tinymce-init.js"></script>
</head>
<body>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý Lore</a></div>
        <div class="admin-header"><h1>Thêm Mục Lore Mới</h1></div>
        <?= $message; ?>
        <form action="add.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="term">Thuật Ngữ (Term)</label>
                        <input type="text" id="term" name="term" placeholder="Ví dụ: KWANGYA, NAVIS, SYNK..." required>
                    </div>
                    <div class="form-group">
                        <label for="summary">Tóm Tắt Ngắn</label>
                        <textarea id="summary" name="summary" rows="3" placeholder="Một câu tóm tắt hiển thị trên thẻ..." required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="full_description">Giải Thích Chi Tiết</label>
                        <textarea id="content" name="full_description" rows="20"></textarea>
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Đăng Bài</h3>
                        <button type="submit" class="form-button">Lưu Mục Lore</button>
                    </div>
                    <div class="sidebar-box">
                        <h3>Ảnh Đại Diện</h3>
                        <div class="image-preview" id="imagePreview">...</div>
                        <input type="file" id="image" name="image" class="image-input" required>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../../js/image-preview.js"></script>
</body>
</html>