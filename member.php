<?php
// 1. Kết nối CSDL
require_once 'config/database.php';

// 2. Kiểm tra và lấy ID thành viên
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Lỗi: ID thành viên không hợp lệ.");
}
$member_id = intval($_GET['id']);

// 3. Lấy thông tin thành viên
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy thành viên này.");
}
$member = $result->fetch_assoc();

// === MÃ MỚI: LẤY ẢNH CÁ NHÂN ===
$photo_sql = $conn->prepare("
    SELECT id, member_id, image_url, caption 
    FROM photos 
    WHERE member_id = ? 
    ORDER BY uploaded_at DESC
");
$photo_sql->bind_param("i", $member_id);
$photo_sql->execute();
$photos = $photo_sql->get_result();
// ==================================
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile: <?php echo htmlspecialchars($member['stage_name']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div class="video-background"> <video autoplay loop muted playsinline><source src="videos/1021.mp4" type="video/mp4"></video>
    </div>

    <?php include 'partials/header.php'; ?>
    
    <main id="swup" class="transition-fade">
        <div class="container">
            <div class="back-link-container">
                <a href="index.php" class="back-link">Back to SYNK</a>
            </div>
            <div class="member-profile">
                <div class="member-photo">
                    <img src="images/<?php echo htmlspecialchars($member['member_image']); ?>" alt="Ảnh của <?php echo htmlspecialchars($member['stage_name']); ?>">
                </div>
                <div class="member-details">
                    <h1><?php echo htmlspecialchars($member['stage_name']); ?></h1>
                    <p><b>Tên thật:</b> <?php echo htmlspecialchars($member['birth_name']); ?></p>
                    <p><b>Ngày sinh:</b> <?php echo date("d/m/Y", strtotime($member['birth_date'])); ?></p>
                    <p><b>Quốc tịch:</b> <?php echo htmlspecialchars($member['nationality']); ?></p>
                    <p><b>Vị trí:</b> <?php echo htmlspecialchars($member['position']); ?></p>
                </div>
            </div>

            <?php if ($photos->num_rows > 0): ?>
                <div class="member-photo-section">
                    <h2>Ảnh</h2>
                    <div class="merch-grid"> 
                        <?php while($photo = $photos->fetch_assoc()): ?>
                            <div class="merch-card" 
                                 data-name="<?= htmlspecialchars($photo['caption']) ?>"
                                 data-category="Photo"
                                 data-image="images/photos/<?= htmlspecialchars($photo['image_url']) ?>"
                                 data-description=""
                                 data-release="">
                                
                                <img src="images/photos/<?= htmlspecialchars($photo['image_url']) ?>" alt="<?= htmlspecialchars($photo['caption']) ?>">
                                <div class="merch-info">
                                    <h3><?= htmlspecialchars($photo['caption']) ?></h3>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endif; ?>
            </div>
    </main>

    <div class="merch-modal" id="merchModal">
        <div class="modal-content">
            <button class="modal-close" id="modalClosePhoto">&times;</button>
            <div class="modal-body">
                <img src="" alt="" id="modalImage">
                <div class="modal-details">
                    <h2 id="modalName"></h2>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>
    
    <script src="js/header_updater.js"></script>
    <script src="js/photo-modal.js"></script> 
    <?php
    $stmt->close();
    $photos->close(); // Đóng kết quả truy vấn ảnh
    $conn->close();
    ?>
</body>
</html>