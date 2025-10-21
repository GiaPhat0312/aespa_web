<?php
// 1. Kết nối CSDL
require_once 'config/database.php';

// 2. Kiểm tra và lấy ID thành viên
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php'); 
    exit();
}
$member_id = intval($_GET['id']);

// 3. Lấy thông tin thành viên hiện tại (BAO GỒM instagram_url)
$stmt = $conn->prepare("SELECT id, stage_name, birth_name, birth_date, nationality, position, member_image, instagram_url FROM members WHERE id = ?"); 
if (!$stmt) { die("Lỗi chuẩn bị SQL lấy thành viên: " . $conn->error); }
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header('Location: index.php'); 
    exit(); 
}
$member = $result->fetch_assoc();
$stmt->close(); 

// 4. Lấy ảnh cá nhân của thành viên hiện tại
$photo_sql = $conn->prepare("SELECT id, member_id, image_url, caption FROM photos WHERE member_id = ? ORDER BY uploaded_at DESC");
if (!$photo_sql) { die("Lỗi chuẩn bị SQL lấy ảnh: " . $conn->error); } 
$photo_sql->bind_param("i", $member_id);
$photo_sql->execute();
$photos = $photo_sql->get_result();

// 5. Lấy danh sách tất cả thành viên cho thanh điều hướng
$all_members_sql = "SELECT id, stage_name FROM members ORDER BY birth_date ASC"; 
$all_members_result = $conn->query($all_members_sql);
if (!$all_members_result) { die("Lỗi truy vấn danh sách thành viên: " . $conn->error); } 

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile: <?php echo htmlspecialchars($member['stage_name']); ?> | aespa Fanpage</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div class="video-background"> 
        <video autoplay loop muted playsinline>
            <source src="videos/1021.mp4" type="video/mp4"> 
        </video>
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
                    
                    <?php if (!empty($member['instagram_url'])): ?>
                        <div class="member-profile-social">
                             <a href="<?= htmlspecialchars($member['instagram_url']) ?>" target="_blank" rel="noopener noreferrer" title="Instagram">
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z" /></svg>
                             </a>
                             </div>
                    <?php endif; ?>
                    
                </div>
            </div>

            <nav class="member-nav">
                <ul>
                    <?php if ($all_members_result && $all_members_result->num_rows > 0): ?>
                        <?php while($nav_member = $all_members_result->fetch_assoc()): ?>
                            <li>
                                <a href="member.php?id=<?= $nav_member['id'] ?>" 
                                   class="<?= ($nav_member['id'] == $member_id) ? 'active' : '' ?>"> 
                                    <?= htmlspecialchars($nav_member['stage_name']) ?>
                                </a>
                            </li>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </ul>
            </nav>

            <?php if ($photos && $photos->num_rows > 0): ?>
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
                                
                                <img src="images/photos/<?= htmlspecialchars($photo['image_url']) ?>" alt="<?= htmlspecialchars($photo['caption'] ?: 'Ảnh của ' . $member['stage_name']) ?>">
                                <div class="merch-info">
                                    <?php if (!empty($photo['caption'])): ?>
                                        <h3><?= htmlspecialchars($photo['caption']) ?></h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php else: ?>
                 <div class="member-photo-section">
                     <h2>Photo Gallery</h2>
                     <p style="text-align: center; color: var(--text-secondary); margin-top: 30px;">Chưa có ảnh nào cho thành viên này.</p>
                 </div>
            <?php endif; ?>
            
        </div> </main>

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

    <script src="https://unpkg.com/swup@4"></script> 
    <script src="js/transitions.js"></script>
    <script src="js/header_updater.js"></script>
    <script src="js/photo-modal.js"></script> 
    <script src="js/mobile-nav.js"></script>

    <svg style="position: absolute; width: 0; height: 0; overflow: hidden;" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
      <defs>
        <linearGradient id="instagram-gradient" x1="0%" y1="0%" x2="100%" y2="100%">
          <stop offset="0%" style="stop-color:#fdf497;stop-opacity:1" />
          <stop offset="5%" style="stop-color:#fdf497;stop-opacity:1" />
          <stop offset="45%" style="stop-color:#fd5949;stop-opacity:1" />
          <stop offset="60%" style="stop-color:#d6249f;stop-opacity:1" />
          <stop offset="90%" style="stop-color:#285AEB;stop-opacity:1" />
        </linearGradient>
      </defs>
    </svg>

    <?php
    if($photos) $photos->close(); 
    if($all_members_result) $all_members_result->close(); 
    $conn->close();
    ?>
</body>
</html>