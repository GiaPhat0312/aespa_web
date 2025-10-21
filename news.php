<?php
require_once 'config/database.php';

// === SỬA LẠI CÂU LỆNH SQL ===
// Sử dụng LEFT JOIN và COUNT để đếm số bình luận cho mỗi bài viết
$sql = "
    SELECT 
        n.id, 
        n.title, 
        n.content, 
        n.image, 
        n.created_at,
        COUNT(c.id) AS total_comments
    FROM 
        news n
    LEFT JOIN 
        comments c ON n.id = c.item_id AND c.page_type = 'news'
    GROUP BY 
        n.id
    ORDER BY 
        n.created_at DESC 
    LIMIT 10
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>aespa News | Fanpage</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div class="video-background"> <video autoplay loop muted playsinline>
            <source src="videos/1021.mp4" type="video/mp4">
        </video>
    </div>
    
    <?php include 'partials/header.php'; ?>

    <main id="swup" class="transition-fade">
        <div class="container">
            <h1 class="index-title">Tin Tức Mới Nhất Về Aespa</h1>
            <p style="text-align: center; color: var(--text-secondary); margin-top: -20px;">
                Cập nhật tin tức và hoạt động mới nhất của aespa.
            </p>
            
            <div class="news-feed-grid"> <?php if ($result->num_rows > 0): ?>
                    <?php $post_index = 0; // Biến đếm để xác định bài nổi bật ?>
                    <?php while($post = $result->fetch_assoc()): ?>
                        <?php
                        // Tạo trích dẫn (excerpt)
                        $excerpt = strip_tags($post['content']);
                        if (mb_strlen($excerpt) > 120) {
                            $excerpt = mb_substr($excerpt, 0, 120) . '...';
                        }
                        
                        // Xác định class cho bài viết (bài đầu tiên sẽ khác)
                        $card_type = ($post_index == 0) ? 'featured' : 'normal';
                        ?>
                        
                        <a href="post.php?id=<?= $post['id'] ?>" class="news-card-link news-card-<?= $card_type ?>">
                            <div class="news-card">
                                <img src="images/news/<?= htmlspecialchars($post['image']) ?>" alt="News Image" class="news-image">
                                <div class="news-content">
                                    <h3 class="news-title"><?= htmlspecialchars($post['title']) ?></h3>
                                    
                                    <p class="news-excerpt"><?= htmlspecialchars($excerpt) ?></p>
                                    
                                    <div class="news-meta">
                                        <span class="news-date"><?= date("d/m/Y", strtotime($post['created_at'])) ?></span>
                                        <span class="news-comment-count">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4A2,2 0 0,0 20,2M20,16H5.17L4,17.17V4H20V16Z" /></svg>
                                            <?= $post['total_comments'] // Hiển thị số bình luận ?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <?php $post_index++; // Tăng biến đếm ?>
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
    <script src="js/merch-modal.js"></script>
    <script src="js/mobile-nav.js"></script>
</body>
</html>