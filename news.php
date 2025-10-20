<?php
require_once 'config/database.php';
// Lấy tất cả bài viết từ CSDL
$result = $conn->query("SELECT id, title, content, image, created_at FROM news ORDER BY created_at DESC LIMIT 10");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>aespa News | Fanpage</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>
    <?php include 'partials/header.php'; ?>

    <main id="swup" class="transition-fade">
        <div class="container">
            <h1>aespa News Update</h1>
            <p style="text-align: center; color: var(--text-secondary); margin-top: -20px;">
                Cập nhật tin tức và hoạt động mới nhất của aespa.
            </p>
            
            <div class="news-feed">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($post = $result->fetch_assoc()): ?>
                        <a href="post.php?id=<?= $post['id'] ?>" class="news-card-link">
                            <div class="news-card">
                                <img src="images/news/<?= htmlspecialchars($post['image']) ?>" alt="News Image" class="news-image">
                                <div class="news-content">
                                    <h3 class="news-title"><?= htmlspecialchars($post['title']) ?></h3>
                                    <p class="news-date"><?= date("d/m/Y", strtotime($post['created_at'])) ?></p>
                                </div>
                            </div>
                        </a>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; margin-top: 40px;">Chưa có bài viết nào.</p>
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