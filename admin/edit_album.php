<?php
require_once 'auth.php';
require_once '../config/database.php';
$message = '';

// 1. Kiểm tra ID album có hợp lệ không
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_albums.php");
    exit();
}
$album_id = intval($_GET['id']);

// 2. Xử lý khi người dùng nhấn nút "Cập nhật"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $conn->begin_transaction();
    try {
        // Lấy dữ liệu từ form
        $title = $_POST['title'];
        $type = $_POST['type'];
        $release_date = $_POST['release_date'];
        $old_cover_image = $_POST['old_cover_image'];
        $cover_image_name = $old_cover_image; // Mặc định giữ ảnh cũ

        // Xử lý nếu có ảnh mới được upload
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
            $target_dir = "../images/";
            $unique_prefix = time() . '_' . uniqid();
            $cover_image_name = $unique_prefix . '_' . basename($_FILES["cover_image"]["name"]);
            $target_file = $target_dir . $cover_image_name;
            if (!move_uploaded_file($_FILES["cover_image"]["tmp_name"], $target_file)) {
                throw new Exception("Có lỗi khi upload ảnh mới.");
            }
        }

        // Cập nhật thông tin album trong DB
        $stmt_album = $conn->prepare("UPDATE albums SET title = ?, type = ?, release_date = ?, cover_image = ? WHERE id = ?");
        $stmt_album->bind_param("ssssi", $title, $type, $release_date, $cover_image_name, $album_id);
        $stmt_album->execute();
        $stmt_album->close();

        // Xóa tracklist cũ để cập nhật lại
        $stmt_delete_tracks = $conn->prepare("DELETE FROM tracks WHERE album_id = ?");
        $stmt_delete_tracks->bind_param("i", $album_id);
        $stmt_delete_tracks->execute();
        $stmt_delete_tracks->close();

        // Thêm lại tracklist mới
        if (!empty($_POST['tracks'])) {
            $stmt_track = $conn->prepare("INSERT INTO tracks (album_id, title, is_title_track) VALUES (?, ?, ?)");
            foreach ($_POST['tracks'] as $index => $track_title) {
                if (!empty($track_title)) {
                    $is_title_track = (isset($_POST['is_title_track']) && $_POST['is_title_track'] == $index);
                    $stmt_track->bind_param("isi", $album_id, $track_title, $is_title_track);
                    $stmt_track->execute();
                }
            }
            $stmt_track->close();
        }

        $conn->commit();
        $message = '<div class="message success">Cập nhật album thành công!</div>';
    } catch (Exception $e) {
        $conn->rollback();
        $message = '<div class="message error">Đã xảy ra lỗi: ' . $e->getMessage() . '</div>';
    }
}

// 3. Lấy thông tin hiện tại của album để hiển thị ra form
$stmt_get_album = $conn->prepare("SELECT * FROM albums WHERE id = ?");
$stmt_get_album->bind_param("i", $album_id);
$stmt_get_album->execute();
$result_album = $stmt_get_album->get_result();
if ($result_album->num_rows === 0) {
    header("Location: manage_albums.php"); // Nếu không tìm thấy album, quay về trang quản lý
    exit();
}
$album = $result_album->fetch_assoc();

// Lấy tracklist hiện tại
$stmt_get_tracks = $conn->prepare("SELECT * FROM tracks WHERE album_id = ? ORDER BY id ASC");
$stmt_get_tracks->bind_param("i", $album_id);
$stmt_get_tracks->execute();
$result_tracks = $stmt_get_tracks->get_result();
$tracks = $result_tracks->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Sửa Album | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>

<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="back-link-container">
            <a href="manage_albums.php" class="back-link">Quay lại Quản lý Album</a>
        </div>
        <h1>Sửa Album: <?= htmlspecialchars($album['title']) ?></h1>

        <?php echo $message; ?>

        <form action="edit_album.php?id=<?= $album_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-group">
                <label for="title">Tên Album:</label>
                <input type="text" id="title" name="title" value="<?= htmlspecialchars($album['title']) ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Loại Album:</label>
                <select id="type" name="type" required>
                    <option value="Full Album" <?= $album['type'] == 'Full Album' ? 'selected' : '' ?>>Full Album</option>
                    <option value="Mini Album" <?= $album['type'] == 'Mini Album' ? 'selected' : '' ?>>Mini Album</option>
                    <option value="Single" <?= $album['type'] == 'Single' ? 'selected' : '' ?>>Single</option>
                    <option value="Digital Single" <?= $album['type'] == 'Digital Single' ? 'selected' : '' ?>>Digital Single</option>
                </select>
            </div>
            <div class="form-group">
                <label for="release_date">Ngày phát hành:</label>
                <input type="date" id="release_date" name="release_date" value="<?= htmlspecialchars($album['release_date']) ?>" required>
            </div>
            <div class="form-group">
                <label for="cover_image">Ảnh bìa mới (để trống nếu không muốn đổi):</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*">

                <div class="current-image-info">
                    <p>Ảnh hiện tại:</p>
                    <img src="../images/<?= htmlspecialchars($album['cover_image']) ?>" alt="Ảnh bìa hiện tại" class="current-image-preview">
                </div>

                <input type="hidden" name="old_cover_image" value="<?= htmlspecialchars($album['cover_image']) ?>">
            </div>

            <hr class="form-separator">

            <div class="form-group">
                <label>Tracklist:</label>
                <div id="tracklist-container">
                    <?php foreach ($tracks as $index => $track): ?>
                        <div class="track-item">
                            <input type="text" name="tracks[]" placeholder="Tên bài hát" value="<?= htmlspecialchars($track['title']) ?>" required>
                            <label class="radio-label">
                                <input type="radio" name="is_title_track" value="<?= $index ?>" <?= $track['is_title_track'] ? 'checked' : '' ?>>
                                <span>Ca khúc chủ đề</span>
                            </label>
                            <button type="button" class="remove-track-btn">&times;</button>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button type="button" id="add-track-btn" class="button-add-track">+ Thêm bài hát</button>
            </div>

            <button type="submit" class="form-button">Cập Nhật Album</button>
        </form>
    </div>

    <script src="../js/app.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('tracklist-container');
            const addBtn = document.getElementById('add-track-btn');
            // Cần lấy index cuối cùng từ PHP để không bị trùng
            let trackIndex = <?= count($tracks) ?>;

            // Hàm xóa track item
            function addRemoveListener(button) {
                button.addEventListener('click', function() {
                    this.parentElement.remove();
                });
            }

            // Gắn sự kiện xóa cho các nút đã có sẵn
            document.querySelectorAll('.remove-track-btn').forEach(addRemoveListener);

            // Hàm thêm track item mới
            addBtn.addEventListener('click', function() {
                const trackDiv = document.createElement('div');
                trackDiv.className = 'track-item';

                trackDiv.innerHTML = `
                    <input type="text" name="tracks[]" placeholder="Tên bài hát #${trackIndex + 1}" required>
                    <label class="radio-label">
                        <input type="radio" name="is_title_track" value="${trackIndex}">
                        <span>Ca khúc chủ đề</span>
                    </label>
                    <button type="button" class="remove-track-btn">&times;</button>
                `;

                container.appendChild(trackDiv);
                addRemoveListener(trackDiv.querySelector('.remove-track-btn'));
                trackIndex++;
            });
        });
    </script>
</body>

</html>