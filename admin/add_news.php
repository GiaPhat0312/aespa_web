<?php
require_once 'auth.php';
require_once '../config/database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $content = $_POST['content']; // TinyMCE sẽ tự động gửi HTML
    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/news/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);
        $image_name = time() . '_' . uniqid() . '_' . basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;

        if (!move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $message = '<div class="message error">Lỗi upload ảnh.</div>';
        }
    }

    if (empty($message)) {
        $stmt = $conn->prepare("INSERT INTO news (title, content, image) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $title, $content, $image_name);
        if ($stmt->execute()) {
            header("Location: manage_news.php");
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
    <title>Viết Bài Mới | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="../images/favicon.png">
    <script src="https://cdn.tiny.cloud/1/hjowki370hh43n6kth3994fybjp6j101fdvq2uj8fq1ipc9z/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
</head>

<body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="../videos/1021.mp4" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="back-link-container">
            <a href="manage_news.php" class="back-link">Quay lại Quản lý</a>
        </div>

        <div class="admin-header">
            <h1>Viết Bài Mới</h1>
        </div>

        <?= $message; ?>

        <form action="add_news.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="title">Tiêu đề bài viết</label>
                        <input type="text" id="title" name="title" placeholder="Ví dụ: aespa công bố lịch trình comeback..." required>
                    </div>
                    <div class="form-group">
                        <label for="content">Nội dung</label>
                        <textarea id="content" name="content" rows="20"></textarea>
                    </div>
                </div>

                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Đăng bài viết</h3>
                        <button type="submit" class="form-button">Đăng Ngay</button>
                    </div>
                    <div class="sidebar-box">
                        <h3>Ảnh đại diện</h3>
                        <div class="image-preview" id="imagePreview">
                            <img src="" alt="Xem trước ảnh" class="image-preview-image">
                            <span class="image-preview-text">Chưa chọn ảnh</span>
                        </div>
                        <input type="file" id="image" name="image" accept="image/*" class="image-input">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="../js/app.js"></script>
    <script>
        // --- 1. KHỞI TẠO TRÌNH SOẠN THẢO VĂN BẢN ---
        tinymce.init({
            selector: '#content',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            skin: 'oxide-dark',
            content_css: 'dark'
        });

        // --- 2. LOGIC XEM TRƯỚC ẢNH LIVE ---
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
            } else {
                previewText.style.display = null;
                previewImage.style.display = null;
                previewImage.setAttribute('src', '');
            }
        });
    </script>
</body>

</html>