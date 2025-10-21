<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

// 1. Kiểm tra ID vật phẩm
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage.php");
    exit();
}
$item_id = intval($_GET['id']);

// 2. Xử lý khi người dùng nhấn "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $release_date = !empty($_POST['release_date']) ? $_POST['release_date'] : null;
    $old_image = $_POST['old_image'];
    $image_name = $old_image;

    // Xử lý nếu có ảnh mới
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/merch/";
        $image_name = time() . '_' . uniqid() . '_' . basename($_FILES["image"]["name"]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
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
        $stmt = $conn->prepare("UPDATE merchandise SET name = ?, category = ?, description = ?, image = ?, release_date = ? WHERE id = ?");
        $stmt->bind_param("sssssi", $name, $category, $description, $image_name, $release_date, $item_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật vật phẩm thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $stmt->error . '</div>';
        }
    }
}

// 3. Lấy thông tin hiện tại của vật phẩm
$stmt = $conn->prepare("SELECT name, category, description, image, release_date FROM merchandise WHERE id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: manage.php");
    exit();
}
$item = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa Vật Phẩm | Admin</title>
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
            <h1>Sửa Vật Phẩm</h1>
        </div>
        <?= $message; ?>

        <form action="edit.php?id=<?= $item_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="name">Tên vật phẩm</label>
                        <input type="text" id="name" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="category">Phân loại</label>
                        <select id="category" name="category" required>
                            <option value="Lightstick" <?= ($item['category'] == 'Lightstick') ? 'selected' : '' ?>>Lightstick</option>
                            <option value="Photocard" <?= ($item['category'] == 'Photocard') ? 'selected' : '' ?>>Photocard</option>
                            <option value="Album Merch" <?= ($item['category'] == 'Album Merch') ? 'selected' : '' ?>>Album Merch</option>
                            <option value="Apparel" <?= ($item['category'] == 'Apparel') ? 'selected' : '' ?>>Trang Phục</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="release_date">Ngày phát hành</label>
                        <input type="date" id="release_date" name="release_date" value="<?= htmlspecialchars($item['release_date']) ?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Mô tả</label>
                        <textarea id="description" name="description" rows="5"><?= htmlspecialchars($item['description']) ?></textarea>
                    </div>
                </div>

                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Cập nhật</h3>
                        <button type="submit" class="form-button">Lưu Thay Đổi</button>
                    </div>
                    <div class="sidebar-box">
                        <h3>Ảnh Đại Diện</h3>
                        <div class="image-preview" id="imagePreview">
                            <img src="<?= !empty($item['image']) ? '../../images/merch/' . htmlspecialchars($item['image']) : '' ?>" alt="Xem trước ảnh" class="image-preview-image" style="<?= !empty($item['image']) ? 'display:block;' : '' ?>">
                            <span class="image-preview-text" style="<?= !empty($item['image']) ? 'display:none;' : '' ?>">Chưa có ảnh</span>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" class="image-input">
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($item['image']) ?>">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="../../js/app.js"></script>
    <script>
        // --- LOGIC XEM TRƯỚC ẢNH LIVE ---
        const imageInput = document.getElementById('image');
        const previewContainer = document.getElementById('imagePreview');
        const previewImage = previewContainer.querySelector('.image-preview-image');
        const previewText = previewContainer.querySelector('.image-preview-text');

        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                previewText.style.display = 'none';
                previewImage.style.display = 'block';
                reader.addEventListener('load', function() {
                    previewImage.setAttribute('src', this.result);
                });
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>

</html>