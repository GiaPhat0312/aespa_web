<?php
// Luôn gọi "lính gác" đầu tiên để bảo vệ trang
require_once 'auth.php';
require_once '../config/database.php';

// Truy vấn để lấy tất cả album, sắp xếp theo ngày phát hành mới nhất
$sql = "SELECT id, title, type, release_date FROM albums ORDER BY release_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Album | Admin</title>
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
            <a href="index.php" class="back-link">Về Dashboard</a>
        </div>
        <h1>Quản Lý Album</h1>

        <div class="admin-action">
            <a href="add_album.php" class="button-add">+ Thêm Album Mới</a>
        </div>

        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tên Album</th>
                    <th>Loại</th>
                    <th>Ngày phát hành</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($album = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($album['title']) . "</td>";
                        echo "<td>" . htmlspecialchars($album['type']) . "</td>";
                        echo "<td>" . date("d/m/Y", strtotime($album['release_date'])) . "</td>";
                        echo '<td class="actions">';
                        // Các nút Sửa/Xóa sẽ được thêm chức năng sau
                        echo '  <a href="edit_album.php?id=' . $album['id'] . '" class="action-btn edit">Sửa</a>';
                        echo '  <a href="delete_albums.php?id=' . $album['id'] . '" class="action-btn delete" onclick="return confirm(\'Bạn có chắc chắn muốn xóa album này? MỌI BÀI HÁT TRONG ALBUM SẼ BỊ XÓA!\');">Xóa</a>';
                        echo '</td>';
                        echo "</tr>";
                    }
                } else {
                    echo '<tr><td colspan="4">Chưa có album nào.</td></tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="../js/app.js"></script>
</body>

</html>
<?php $conn->close(); ?>