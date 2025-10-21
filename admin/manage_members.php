<?php
// 1. Kiểm tra đăng nhập
require_once 'auth.php';
// 2. Kết nối CSDL
require_once '../config/database.php';

// 3. Lấy danh sách tất cả thành viên
$sql = "SELECT id, stage_name, birth_name, member_image FROM members ORDER BY birth_date ASC"; // Sắp xếp theo ngày sinh
$result = $conn->query($sql);
if (!$result) {
    die("Lỗi truy vấn thành viên: " . $conn->error); // Dừng nếu có lỗi SQL
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Thành Viên | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css"> <link rel="icon" type="image/png" href="../images/favicon.png">
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

        <div class="admin-header">
            <h1>Quản Lý Thành Viên</h1>
            </div>

        <div class="table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th style="width: 15%;">Ảnh</th>
                        <th style="width: 30%;">Nghệ danh</th>
                        <th style="width: 35%;">Tên thật</th>
                        <th style="width: 20%; text-align: center;">Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($member = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="../images/<?= htmlspecialchars($member['member_image']) ?>" alt="<?= htmlspecialchars($member['stage_name']) ?>" class="table-thumbnail circle"> </td>
                            <td><?= htmlspecialchars($member['stage_name']) ?></td>
                            <td><?= htmlspecialchars($member['birth_name']) ?></td>
                            <td class="actions">
                                <a href="edit_member.php?id=<?= $member['id'] ?>" class="action-btn edit">Sửa</a>
                                </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="empty-cell">Chưa có thành viên nào.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    </body>
</html>
<?php
$result->close();
$conn->close();
?>