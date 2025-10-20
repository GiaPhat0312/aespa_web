<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $category = $_POST['category'];
    $description = $_POST['description'];
    $release_date = !empty($_POST['release_date']) ? $_POST['release_date'] : null;
    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../images/merch/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $image_name = time() . '_' . uniqid() . '_' . basename($_FILES["image"]["name"]);

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name)) {
            $message = '<div class="message error">Lỗi upload ảnh.</div>';
        }
    } else {
        $message = '<div class="message error">Vui lòng chọn ảnh cho vật phẩm.</div>';
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO merchandise (name, category, description, image, release_date) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $category, $description, $image_name, $release_date);

        if ($stmt->execute()) {
            header("Location: manage.php"); 
            exit();
        } else {
            $message = '<div class="message error">Lỗi database: ' . $stmt->error . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Vật Phẩm Mới | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="../../images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý</a></div>
        <div class="admin-header"><h1>Thêm Vật Phẩm Mới</h1></div>
        <?= $message; ?>
        <div class="form-container">
            <form action="add.php" method="POST" enctype="multipart/form-data" class="admin-form">
                <div class="form-group">
                    <label for="name">Tên vật phẩm</label>
                    <input type="text" id="name" name="name" placeholder="Ví dụ: aespa Official Light Stick" required>
                </div>
                <div class="form-group">
                    <label for="category">Phân loại</label>
                    <select id="category" name="category" required>
                        <option value="" disabled selected>-- Chọn một loại --</option>
                        <option value="Lightstick">Lightstick</option>
                        <option value="Photocard">Photocard</option>
                        <option value="Album Merch">Album Merch</option>
                        <option value="Apparel">Trang Phục</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="release_date">Ngày phát hành (Tùy chọn)</label>
                    <input type="date" id="release_date" name="release_date">
                </div>
                <div class="form-group">
                    <label for="description">Mô tả</label>
                    <textarea id="description" name="description" rows="5" placeholder="Mô tả ngắn về vật phẩm..."></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Ảnh vật phẩm</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                </div>
                <button type="submit" class="form-button">Thêm Vật Phẩm</button>
            </form>
        </div>
    </div>
    <script src="../../js/app.js"></script>
</body>
</html>