<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage.php");
    exit();
}
$entry_id = intval($_GET['id']);

// Xử lý khi nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $term = $_POST['term'];
    $summary = $_POST['summary'];
    $full_description = $_POST['full_description'];
    $old_image = $_POST['old_image'];
    $image_name = $old_image;

    // Xử lý ảnh mới (nếu có)
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/lore/"; // Sửa thư mục ảnh
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            // Xóa ảnh cũ
            if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        } else {
            $message = '<div class="message error">Lỗi upload ảnh mới.</div>';
            $image_name = $old_image;
        }
    }

    // Cập nhật database
    if (empty($message)) {
        $stmt = $conn->prepare("UPDATE lore_entries SET term = ?, summary = ?, full_description = ?, image = ? WHERE id = ?");
        $stmt->bind_param("ssssi", $term, $summary, $full_description, $image_name, $entry_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật mục lore thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $stmt->error . '</div>';
        }
    }
}

// Lấy thông tin hiện tại của mục lore
$stmt = $conn->prepare("SELECT term, summary, full_description, image FROM lore_entries WHERE id = ?");
$stmt->bind_param("i", $entry_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: manage.php");
    exit();
}
$entry = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Mục Lore | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script src="../js/tinymce-init.js"></script>
</head>
<body>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý Lore</a></div>
        <div class="admin-header"><h1>Sửa Mục Lore</h1></div>
        <?= $message; ?>
        <form action="edit.php?id=<?= $entry_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="term">Thuật Ngữ (Term)</label>
                        <input type="text" id="term" name="term" value="<?= htmlspecialchars($entry['term']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="summary">Tóm Tắt Ngắn</label>
                        <textarea id="summary" name="summary" rows="3" required><?= htmlspecialchars($entry['summary']) ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="full_description">Giải Thích Chi Tiết</label>
                        <textarea id="content" name="full_description" rows="20"><?= htmlspecialchars($entry['full_description']) ?></textarea>
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Cập Nhật</h3>
                        <button type="submit" class="form-button">Lưu Thay Đổi</button>
                    </div>
                    <div class="sidebar-box">
                        <h3>Ảnh Đại Diện</h3>
                        <div class="image-preview" id="imagePreview">
                            <img src="../../images/lore/<?= htmlspecialchars($entry['image']) ?>" alt="Xem trước" class="image-preview-image" style="display:block;">
                        </div>
                        <input type="file" id="image" name="image" class="image-input" accept="image/*">
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($entry['image']) ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../../js/image-preview.js"></script>
</body>
</html>