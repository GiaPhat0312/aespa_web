<?php
require_once 'auth.php';
require_once '../config/database.php';
$message = '';

// 1. Kiểm tra ID bài viết có hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_news.php");
    exit();
}
$post_id = intval($_GET['id']);

// 2. Xử lý khi người dùng nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $title = $_POST['title'];
    $content = $_POST['content'];
    $old_image = $_POST['old_image'];
    $image_name = $old_image; // Mặc định giữ lại ảnh cũ

    // Xử lý nếu có ảnh mới được upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/news/";
        $image_name = time() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            // Xóa ảnh cũ nếu upload ảnh mới thành công và ảnh cũ tồn tại
            if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                unlink($target_dir . $old_image);
            }
        } else {
            $message = '<div class="message error">Có lỗi khi upload ảnh mới.</div>';
            $image_name = $old_image; // Nếu lỗi, quay lại dùng ảnh cũ
        }
    }

    // Cập nhật bài viết trong database
    if (empty($message)) {
        $stmt = $conn->prepare("UPDATE news SET title = ?, content = ?, image = ? WHERE id = ?");
        $stmt->bind_param("sssi", $title, $content, $image_name, $post_id);
        if ($stmt->execute()) {
            $message = '<div class="message success">Cập nhật bài viết thành công!</div>';
        } else {
            $message = '<div class="message error">Lỗi: ' . $stmt->error . '</div>';
        }
        $stmt->close();
    }
}

// 3. Lấy thông tin hiện tại của bài viết để hiển thị ra form
$stmt = $conn->prepare("SELECT title, content, image FROM news WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: manage_news.php"); // Nếu không tìm thấy, quay về trang quản lý
    exit();
}
$post = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa Bài Viết | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="../images/favicon.png">
</head>

<body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="../videos/1021.mp4" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="back-link-container">
            <a href="manage_news.php" class="back-link">Quay lại Quản lý Tin tức</a>
        </div>
        <h1>Sửa Bài Viết</h1>
        <?= $message ?>

        <form action="edit_news.php?id=<?= $post_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-group">
                <label for="title">Tiêu đề:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="content">Nội dung:</label>
                <textarea id="content" name="content" rows="15" required><?= htmlspecialchars($post['content']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="image">Ảnh minh họa (để trống nếu không muốn đổi):</label>
                <input type="file" id="image" name="image" accept="image/*">
                <input type="hidden" name="old_image" value="<?= htmlspecialchars($post['image']) ?>">

                <?php if (!empty($post['image'])): ?>
                    <div class="current-image-info">
                        <p>Ảnh hiện tại:</p>
                        <img src="../images/news/<?= htmlspecialchars($post['image']) ?>" alt="Ảnh hiện tại" class="current-image-preview">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="form-button">Cập Nhật Bài Viết</button>
        </form>
    </div>
</body>

</html>