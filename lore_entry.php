<?php
require_once 'config/database.php';
session_start();

// === SỬA LẠI TRUY VẤN ===
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: lore.php');
    exit();
}
$entry_id = intval($_GET['id']);

// Lấy từ bảng 'lore_entries'
$stmt = $conn->prepare("SELECT term, full_description, image FROM lore_entries WHERE id = ?");
$stmt->bind_param("i", $entry_id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header('Location: lore.php');
    exit();
}
$entry = $result->fetch_assoc();
$stmt->close();

// Lấy bình luận (dùng 'lore' làm page_type)
$comments_stmt = $conn->prepare("SELECT author_name, comment_text, created_at FROM comments WHERE page_type = 'lore' AND item_id = ? ORDER BY created_at DESC");
$comments_stmt->bind_param("i", $entry_id);
$comments_stmt->execute();
$comments_result = $comments_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <title><?= htmlspecialchars($entry['term']) ?> | aespa Lore</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div class="video-background">...</div>
    <?php include 'partials/header.php'; ?>
    <main id="swup" class="transition-fade">
        <div class="container post-container"> 
            <div class="back-link-container">
                <a href="lore.php" class="back-link">Quay lại Bách Khoa Lore</a>
            </div>
            
            <div class="post-full-content">
                <div class="post-header">
                    <h1 class="post-title"><?= htmlspecialchars($entry['term']) ?></h1>
                </div>
                
                <img src="images/lore/<?= htmlspecialchars($entry['image']) ?>" alt="<?= htmlspecialchars($entry['term']) ?>" class="post-full-image">
                
                <div class="post-body">
                    <?= $entry['full_description'] // Hiển thị nội dung từ TinyMCE ?>
                </div>
            </div>

            <div class="comment-section">
                <h2>Bình Luận (<?= $comments_result->num_rows ?>)</h2>
                <form action="handle_comment.php" method="POST" class="comment-form">
                    <input type="hidden" name="page_type" value="lore">
                    <input type="hidden" name="item_id" value="<?= $entry_id ?>">
                    
                    </form>
                <div class="comment-list">
                    </div>
            </div>
        </div>
    </main>
    </body>
</html>
<?php
$comments_stmt->close();
$conn->close();
?>