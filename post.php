<?php
require_once 'config/database.php';
session_start(); // Bắt đầu session để xử lý bình luận

// 1. LẤY BÀI VIẾT
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: news.php');
    exit();
}
$post_id = intval($_GET['id']);
$stmt = $conn->prepare("SELECT title, content, image, created_at FROM news WHERE id = ?");
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header('Location: news.php');
    exit();
}
$post = $result->fetch_assoc();
$stmt->close();

// 2. LẤY DANH SÁCH BÌNH LUẬN
$comments_stmt = $conn->prepare("SELECT author_name, comment_text, created_at FROM comments WHERE page_type = 'news' AND item_id = ? ORDER BY created_at DESC");
$comments_stmt->bind_param("i", $post_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($post['title']) ?> | aespa News</title>
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
        <div class="container post-container">
            <div class="back-link-container">
                <a href="news.php" class="back-link">Quay lại Tin tức</a>
            </div>

            <div class="post-full-content">
                <div class="post-header">
                    <h1 class="post-title"><?= htmlspecialchars($post['title']) ?></h1>
                    <p class="post-meta">Đăng ngày: <?= date("d/m/Y", strtotime($post['created_at'])) ?></p>
                </div>

                <?php if (!empty($post['image'])): ?>
                    <img src="images/news/<?= htmlspecialchars($post['image']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="post-full-image">
                <?php endif; ?>

                <div class="post-body">
                    <?= $post['content'] // SỬA LỖI QUAN TRỌNG: Hiển thị đúng HTML từ TinyMCE 
                    ?>
                </div>
            </div>

            <div class="comment-section">
                <h2>Bình Luận (<?= $comments_result->num_rows ?>)</h2>

                <form action="handle_comment.php" method="POST" class="comment-form">
                    <h3>Gửi bình luận của bạn</h3>
                    <?php
                    if (isset($_SESSION['comment_error'])) {
                        echo '<div class="message error">' . $_SESSION['comment_error'] . '</div>';
                        unset($_SESSION['comment_error']);
                    }
                    ?>
                    <input type="hidden" name="page_type" value="news">
                    <input type="hidden" name="item_id" value="<?= $post_id ?>">

                    <div class="form-group">
                        <label for="author_name">Tên của bạn</label>
                        <input type="text" id="author_name" name="author_name" required>
                    </div>
                    <div class="form-group">
                        <label for="comment_text">Nội dung bình luận</label>
                        <textarea id="comment_text" name="comment_text" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="form-button">Gửi Bình Luận</button>
                </form>

                <div class="comment-list">
                    <?php if ($comments_result->num_rows > 0): ?>
                        <?php while ($comment = $comments_result->fetch_assoc()): ?>
                            <div class="comment-item">
                                <div class="comment-avatar">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                        <path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" />
                                    </svg>
                                </div>
                                <div class="comment-content-wrap">
                                    <div class="comment-header">
                                        <strong class="comment-author"><?= htmlspecialchars($comment['author_name']) ?></strong>
                                        <span class="comment-date"><?= date("d/m/Y H:i", strtotime($comment['created_at'])) ?></span>
                                    </div>
                                    <div class="comment-body">
                                        <?= nl2br(htmlspecialchars($comment['comment_text'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p style="text-align:center; color: var(--text-secondary);">Chưa có bình luận nào. Hãy là người đầu tiên!</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

    <script src="js/header_updater.js"></script>
    <script src="js/merch-modal.js"></script>
    <script src="js/mobile-nav.js"></script>
</body>

</html>
<?php
$comments_stmt->close();
$conn->close();
?>