<?php
require_once '../auth.php';
require_once '../../config/database.php';

// Lấy ảnh, JOIN với bảng members để lấy tên thành viên
$sql = "
    SELECT p.id, p.image_url, p.caption, m.stage_name 
    FROM photos p
    JOIN members m ON p.member_id = m.id
    ORDER BY p.uploaded_at DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Ảnh Gallery | Admin</title>
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
        <div class="back-link-container">
            <a href="../index.php" class="back-link">Về Dashboard</a>
        </div>

        <div class="admin-header">
            <h1>Quản Lý Ảnh Gallery</h1>
            <a href="add.php" class="button-add">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                <span>Thêm Ảnh Mới</span>
            </a>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Ảnh</th>
                        <th style="width: 25%;">Thành Viên</th>
                        <th style="width: 40%;">Chú Thích</th>
                        <th style="width: 20%; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="../../images/photos/<?= htmlspecialchars($row['image_url']) ?>" alt="<?= htmlspecialchars($row['caption']) ?>" class="table-thumbnail">
                                </td>
                                <td><?= htmlspecialchars($row['stage_name']) ?></td>
                                <td><?= htmlspecialchars($row['caption']) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn edit">Sửa</a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="action-btn delete" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này không?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-cell">Chưa có ảnh nào trong gallery.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../../js/app.js"></script>
</body>

</html>