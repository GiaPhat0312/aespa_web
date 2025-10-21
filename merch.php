<?php
require_once 'config/database.php';

// SỬA LẠI: Lấy thêm cột 'description' và 'release_date'
$category_filter = '';
if (isset($_GET['category']) && !empty($_GET['category'])) {
    $category_filter = $_GET['category'];
    $stmt = $conn->prepare("SELECT name, category, image, description, release_date FROM merchandise WHERE category = ? ORDER BY release_date DESC, id DESC");
    $stmt->bind_param("s", $category_filter);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT name, category, image, description, release_date FROM merchandise ORDER BY release_date DESC, id DESC");
}
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bộ Sưu Tập | aespa Fanpage</title>
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
            <h1 class="index-title">Bộ sưu tập</h1>
            <p style="text-align: center; color: var(--text-secondary); margin-top: -20px; max-width: 600px; margin-left: auto; margin-right: auto;">
                Khám phá bộ sưu tập các vật phẩm chính thức, từ lightstick biểu tượng đến những tấm photocard độc quyền.
            </p>

            <div class="filter-bar">
                <a href="merch.php" class="<?= empty($category_filter) ? 'active' : '' ?>">Tất Cả</a>
                <a href="merch.php?category=Lightstick" class="<?= ($category_filter == 'Lightstick') ? 'active' : '' ?>">Lightstick</a>
                <a href="merch.php?category=Photocard" class="<?= ($category_filter == 'Photocard') ? 'active' : '' ?>">Photocard</a>
                <a href="merch.php?category=Album Merch" class="<?= ($category_filter == 'Album Merch') ? 'active' : '' ?>">Album</a>
            </div>

            <div class="merch-grid">
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($item = $result->fetch_assoc()): ?>
                        <div class="merch-card"
                            data-name="<?= htmlspecialchars($item['name']) ?>"
                            data-category="<?= htmlspecialchars($item['category']) ?>"
                            data-image="images/merch/<?= htmlspecialchars($item['image']) ?>"
                            data-description="<?= htmlspecialchars($item['description']) ?>"
                            data-release="<?= !empty($item['release_date']) ? date("d/m/Y", strtotime($item['release_date'])) : 'N/A' ?>">
                            <img src="images/merch/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>">
                            <div class="merch-info">
                                <h3><?= htmlspecialchars($item['name']) ?></h3>
                                <span class="category-tag"><?= htmlspecialchars($item['category']) ?></span>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p style="text-align: center; color: var(--text-secondary); margin-top: 40px; grid-column: 1 / -1;">Chưa có vật phẩm nào trong mục này.</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <div class="merch-modal" id="merchModal">
        <div class="modal-content">
            <button class="modal-close" id="modalClose">&times;</button>
            <div class="modal-body">
                <img src="" alt="" id="modalImage">
                <div class="modal-details">
                    <h2 id="modalName"></h2>
                    <span class="category-tag" id="modalCategory"></span>
                    <p><strong>Ngày phát hành:</strong> <span id="modalRelease"></span></p>
                    <p id="modalDescription"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>
    <script src="js/header_updater.js"></script>
    <script src="js/merch-modal.js"></script>
    <script src="js/mobile-nav.js"></script>
</body>

</html>