<?php
require_once 'config/database.php';
if (!isset($_GET['id'])) die("Bài viết không tồn tại.");
$id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT title, content, image, created_at FROM news WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();
if (!$post) die("Bài viết không tồn tại.");
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($post['title']) ?> | aespa News</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>
    <?php include 'partials/header.php'; ?>

    <main id="swup" class="transition-fade">
        <div class="container post-container">
             <div class="back-link-container">
                <a href="news.php" class="back-link">Quay lại danh sách tin</a>
            </div>
            
            <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
            <p class="post-meta">Đăng ngày: <?= date("d/m/Y", strtotime($post['created_at'])) ?></p>
            
            <?php if (!empty($post['image'])): ?>
                <img src="images/news/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-image">
            <?php endif; ?>
            
            <div class="post-content">
                <?= nl2br(htmlspecialchars($post['content'])) ?>
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