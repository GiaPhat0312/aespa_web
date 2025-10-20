<?php
require_once 'auth.php';
require_once '../config/database.php';
$result = $conn->query("SELECT id, title, created_at FROM news ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Tin Tức | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css">
</head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <div class="back-link-container">
            <a href="index.php" class="back-link">Về Dashboard</a>
        </div>
        <h1>Quản Lý Tin Tức</h1>
        <div class="admin-action">
            <a href="add_news.php" class="button-add">Thêm Bài Viết Mới</a>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Ngày đăng</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= date("d/m/Y H:i", strtotime($row['created_at'])) ?></td>
                    <td class="actions">
                        <a href="edit_news.php?id=<?= $row['id'] ?>" class="action-btn edit">Sửa</a>
                        <a href="delete_news.php?id=<?= $row['id'] ?>" class="action-btn delete" onclick="return confirm('Bạn có chắc chắn muốn xóa bài viết này không?');">Xóa</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>