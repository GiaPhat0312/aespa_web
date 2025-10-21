<?php
require_once '../auth.php';
require_once '../../config/database.php';
$result = $conn->query("SELECT id, name, category, image FROM merchandise ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Vật Phẩm | Admin</title>
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
            <h1>Quản Lý Vật Phẩm</h1>
            <a href="add.php" class="button-add">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path d="M19,13H13V19H11V13H5V11H11V5H13V11H19V13Z" />
                </svg>
                <span>Thêm Vật Phẩm Mới</span>
            </a>
        </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">Ảnh</th>
                        <th style="width: 45%;">Tên Vật Phẩm</th>
                        <th style="width: 25%;">Phân Loại</th>
                        <th style="width: 20%; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <img src="../../images/merch/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>" class="table-thumbnail">
                                </td>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['category']) ?></td>
                                <td class="actions">
                                    <a href="edit.php?id=<?= $row['id'] ?>" class="action-btn edit">Sửa</a>
                                    <a href="delete.php?id=<?= $row['id'] ?>" class="action-btn delete" onclick="return confirm('Bạn có chắc chắn muốn xóa vật phẩm này không?');">Xóa</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-cell">Chưa có vật phẩm nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <script src="../../js/app.js"></script>
</body>

</html>