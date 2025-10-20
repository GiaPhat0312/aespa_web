<?php
// PHP code của bạn giữ nguyên
require_once 'config/database.php';
$sql_group = "SELECT name, debut_date, company, fandom_name, concept, group_image FROM group_info WHERE id = 1";
$result_group = $conn->query($sql_group);
$aespa = $result_group->fetch_assoc() ?? die("Không tìm thấy thông tin nhóm.");

$sql_members = "SELECT id, stage_name, birth_name, position, member_image FROM members ORDER BY birth_date ASC";
$result_members = $conn->query($sql_members);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông tin về Aespa</title>
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
            Trình duyệt của bạn không hỗ trợ video tag.
        </video>
    </div>
    
    <?php include 'partials/header.php'; ?>
    <main id="swup" class="transition-fade">
        <div class="container">
            <h1>Welcome to the SYNK: <?php echo htmlspecialchars($aespa['name']); ?></h1>

            <div class="group-info">
                <img src="images/<?php echo htmlspecialchars($aespa['group_image']); ?>" alt="Ảnh nhóm aespa">
                <div class="group-details">
                    <p><b>Ngày ra mắt:</b> <?php echo date("d/m/Y", strtotime($aespa['debut_date'])); ?></p>
                    <p><b>Công ty:</b> <?php echo htmlspecialchars($aespa['company']); ?></p>
                    <p><b>Fandom:</b> <?php echo htmlspecialchars($aespa['fandom_name']); ?></p>
                    <p><b>Concept:</b> <?php echo nl2br(htmlspecialchars($aespa['concept'])); ?></p>
                </div>
            </div>
             <div class="social-links">
                <a href="https://www.youtube.com/@aespa" target="_blank" rel="noopener noreferrer" class="social-icon youtube" title="aespa on YouTube">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M10,15L15.19,12L10,9V15M21.56,7.17C21.69,7.64 21.78,8.27 21.84,9.07C21.91,9.87 21.94,10.56 21.94,11.16L22,12C22,14.19 21.84,15.8 21.56,16.83C21.31,17.73 20.73,18.31 19.83,18.56C19.36,18.69 18.73,18.78 17.93,18.84C17.13,18.91 16.44,18.94 15.84,18.94L15,19C12.81,19 11.2,18.84 10.17,18.56C9.27,18.31 8.69,17.73 8.44,16.83C8.31,16.36 8.22,15.73 8.16,14.93C8.09,14.13 8.06,13.44 8.06,12.84L8,12C8,9.81 8.16,8.2 8.44,7.17C8.69,6.27 9.27,5.69 10.17,5.44C10.64,5.31 11.27,5.22 12.07,5.16C12.87,5.09 13.56,5.06 14.16,5.06L15,5C17.19,5 18.8,5.16 19.83,5.44C20.73,5.69 21.31,6.27 21.56,7.17Z" /></svg>
                </a>
                <a href="https://www.instagram.com/aespa_official/" target="_blank" rel="noopener noreferrer" class="social-icon instagram" title="aespa on Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z" /></svg>
                </a>
                <a href="https://www.facebook.com/aespa.official" target="_blank" rel="noopener noreferrer" class="social-icon facebook" title="aespa on Facebook">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13,15H11V9H13V15M13,5H11V7H13V5M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z" /></svg>
                </a>
            </div>

            <h2>M Y, Æ S P A</h2>
            <div class="members-grid">
                <?php if ($result_members->num_rows > 0): ?>
                    <?php while ($member = $result_members->fetch_assoc()): ?>
                        <a href="member.php?id=<?= $member['id'] ?>" class="member-link">
                            <div class="member-card">
                                <img src="images/<?= htmlspecialchars($member['member_image']) ?>" alt="Ảnh của <?= htmlspecialchars($member['stage_name']) ?>">
                                <h3><?= htmlspecialchars($member['stage_name']) ?></h3>
                                <p>(<?= htmlspecialchars($member['birth_name']) ?>)</p>
                                <p><?= htmlspecialchars($member['position']) ?></p>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Không có thông tin thành viên.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>
    <script src="js/header_updater.js"></script>

</body>
</html>
<?php $conn->close(); ?>