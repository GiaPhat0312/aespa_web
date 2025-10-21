<?php
require_once 'config/database.php';
// Lấy các mục Lore, sắp xếp theo BẢNG CHỮ CÁI
$result = $conn->query("SELECT id, term, summary, image FROM lore_entries ORDER BY term ASC");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title>aespa Universe (Lore) | Fanpage</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div class="video-background">
        <video autoplay loop muted playsinline><source src="videos/1021.mp4" type="video/mp4"></video>
    </div>
    <?php include 'partials/header.php'; ?>
    <main id="swup" class="transition-fade">
        <div class="container">
            <h1>Aespa Universe </h1>
            <p style="text-align: center; color: var(--text-secondary); margin-top: -20px;">
                Vũ trụ aespa.
            </p>
            
            <div class="merch-grid" style="margin-top: 40px;">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($entry = $result->fetch_assoc()): ?>
                        <a href="lore_entry.php?id=<?= $entry['id'] ?>" class="lore-card-link">
                            <div class="merch-card"> <img src="images/lore/<?= htmlspecialchars($entry['image']) ?>" alt="<?= htmlspecialchars($entry['term']) ?>">
                                <div class="merch-info">
                                    <h3><?= htmlspecialchars($entry['term']) ?></h3>
                                    <p style="color: var(--text-secondary); font-size: 0.9em;"><?= htmlspecialchars($entry['summary']) ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 40px; grid-column: 1 / -1;">Chưa có mục lore nào được thêm vào.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>
    </body>
</html>