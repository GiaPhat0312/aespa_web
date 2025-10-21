<?php
require_once '../auth.php';
require_once '../../config/database.php';

// 1. Lấy danh sách tất cả thành viên
$members_result = $conn->query("SELECT id, stage_name FROM members ORDER BY stage_name ASC");

// 2. Chuẩn bị câu lệnh để lấy ảnh cho từng thành viên (hiệu quả hơn)
$photos_stmt = $conn->prepare("
    SELECT id, image_url, caption 
    FROM photos 
    WHERE member_id = ? 
    ORDER BY uploaded_at DESC
");

// Kiểm tra lỗi prepare statement
if (!$photos_stmt) {
    die("Lỗi chuẩn bị câu lệnh SQL lấy ảnh: " . $conn->error);
}

?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Quản Lý Ảnh Gallery | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css">
    <link rel="stylesheet" href="../../css/styleAdminPhotos.css">  
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

        <div class="photos-by-member-container">
            <?php if ($members_result && $members_result->num_rows > 0): ?>
                <?php while ($member = $members_result->fetch_assoc()): ?>
                    <?php
                    // Lấy ảnh cho thành viên hiện tại
                    $member_id = $member['id'];
                    $photos_stmt->bind_param("i", $member_id);
                    $photos_stmt->execute();
                    $photos_result = $photos_stmt->get_result();
                    ?>
                    
                    <div class="member-photo-group">
                        <h2 class="member-group-title"><?= htmlspecialchars($member['stage_name']) ?> (<?= $photos_result->num_rows ?> ảnh)</h2>
                        
                        <?php if ($photos_result && $photos_result->num_rows > 0): ?>
                            <div class="photo-admin-grid"> <?php while ($photo = $photos_result->fetch_assoc()): ?>
                                    <div class="photo-admin-card">
                                        <img src="../../images/photos/<?= htmlspecialchars($photo['image_url']) ?>" alt="<?= htmlspecialchars($photo['caption']) ?>">
                                        <div class="photo-admin-info">
                                            <p class="caption"><?= htmlspecialchars($photo['caption'] ?: '(Không có chú thích)') ?></p> 
                                        </div>
                                        <div class="photo-admin-actions">
                                            <a href="edit.php?id=<?= $photo['id'] ?>" class="edit">Sửa</a>
                                            <a href="delete.php?id=<?= $photo['id'] ?>" class="delete" onclick="return confirm('Bạn có chắc chắn muốn xóa ảnh này không?');">Xóa</a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        <?php else: ?>
                            <p class="empty-group-message">Chưa có ảnh nào cho thành viên này.</p>
                        <?php endif; ?>
                        <?php if($photos_result) $photos_result->close(); // Đóng kết quả ảnh của thành viên này ?>
                    </div> <?php endwhile; ?>
            <?php else: ?>
                <p>Chưa có thành viên nào trong hệ thống.</p>
            <?php endif; ?>
        </div>
        </div>
    <script src="../../js/app.js"></script> 
</body>
</html>
<?php 
if($photos_stmt) $photos_stmt->close(); // Đóng câu lệnh prepare ảnh
if($members_result) $members_result->close(); // Đóng kết quả thành viên
$conn->close(); 
?>