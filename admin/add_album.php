<?php
require_once 'auth.php';
require_once '../config/database.php';
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Bắt đầu một transaction để đảm bảo cả album và tracks đều được thêm thành công
    $conn->begin_transaction();

    try {
        // 1. Lấy và thêm thông tin album
        $title = $_POST['title'];
        $type = $_POST['type'];
        $release_date = $_POST['release_date'];

        // 2. Xử lý upload ảnh
        $cover_image_name = '';
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $target_dir = "../images/";
            $unique_prefix = time() . '_' . uniqid();
            $cover_image_name = $unique_prefix . '_' . basename($_FILES["cover_image"]["name"]);
            $target_file = $target_dir . $cover_image_name;
            if (!move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                throw new Exception("Có lỗi khi upload ảnh.");
            }
        }

        // Thêm album vào database
        $stmt_album = $conn->prepare("INSERT INTO albums (title, type, release_date, cover_image) VALUES (?, ?, ?, ?)");
        $stmt_album->bind_param("ssss", $title, $type, $release_date, $cover_image_name);
        $stmt_album->execute();

        // Lấy ID của album vừa được tạo
        $album_id = $conn->insert_id;
        $stmt_album->close();

        // 3. Xử lý và thêm danh sách bài hát (tracks)
        if (!empty($_POST['tracks'])) {
            $stmt_track = $conn->prepare("INSERT INTO tracks (album_id, title, is_title_track) VALUES (?, ?, ?)");
            
            foreach ($_POST['tracks'] as $index => $track_title) {
                if (!empty($track_title)) {
                    // Kiểm tra xem bài hát này có phải là bài hát chủ đề không
                    $is_title_track = (isset($_POST['is_title_track']) && $_POST['is_title_track'] == $index);
                    $stmt_track->bind_param("isi", $album_id, $track_title, $is_title_track);
                    $stmt_track->execute();
                }
            }
            $stmt_track->close();
        }

        // Nếu mọi thứ thành công, commit transaction
        $conn->commit();
        $message = '<div class="message success">Thêm album và tracklist thành công!</div>';

    } catch (Exception $e) {
        // Nếu có lỗi, rollback lại tất cả thay đổi
        $conn->rollback();
        $message = '<div class="message error">Đã xảy ra lỗi: ' . $e->getMessage() . '</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Album Mới | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="back-link-container">
            <a href="manage_albums.php" class="back-link">Quay lại Quản lý Album</a>
        </div>
        <h1>Thêm Album Mới</h1>

        <?php echo $message; ?>

        <form action="add_album.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-group">
                <label for="title">Tên Album:</label>
                <input type="text" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="type">Loại Album:</label>
                <select id="type" name="type" required>
                    <option value="Full Album">Full Album</option>
                    <option value="Mini Album">Mini Album</option>
                    <option value="Single">Single</option>
                    <option value="Digital Single">Digital Single</option>
                </select>
            </div>
            <div class="form-group">
                <label for="release_date">Ngày phát hành:</label>
                <input type="date" id="release_date" name="release_date" required>
            </div>
            <div class="form-group">
                <label for="cover_image">Ảnh bìa:</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
            </div>

            <hr class="form-separator">

            <div class="form-group">
                <label>Tracklist:</label>
                <div id="tracklist-container">
                    </div>
                <button type="button" id="add-track-btn" class="button-add-track">+ Thêm bài hát</button>
            </div>
            <button type="submit" class="form-button">Lưu Album và Tracklist</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="../js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('tracklist-container');
            const addBtn = document.getElementById('add-track-btn');
            let trackIndex = 0;

            function addTrackField(isFirst = false) {
                const trackDiv = document.createElement('div');
                trackDiv.className = 'track-item';
                
                trackDiv.innerHTML = `
                    <input type="text" name="tracks[]" placeholder="Tên bài hát #${trackIndex + 1}" required>
                    <label class="radio-label">
                        <input type="radio" name="is_title_track" value="${trackIndex}" ${isFirst ? 'checked' : ''}>
                        <span>Ca khúc chủ đề</span>
                    </label>
                    <button type="button" class="remove-track-btn">&times;</button>
                `;
                
                container.appendChild(trackDiv);
                trackIndex++;

                // Thêm sự kiện xóa cho nút mới
                trackDiv.querySelector('.remove-track-btn').addEventListener('click', function() {
                    trackDiv.remove();
                });
            }

            // Thêm một bài hát khi nhấn nút
            addBtn.addEventListener('click', function() {
                addTrackField();
            });

            // Tự động thêm một ô nhập liệu đầu tiên khi tải trang
            addTrackField(true);
        });
    </script>
</body>
</html>