<?php
require_once 'config/database.php';

// Truy vấn để lấy tất cả album, sắp xếp theo ngày phát hành mới nhất
$sql_albums = "SELECT id, title, type, release_date, cover_image FROM albums ORDER BY release_date DESC";
$result_albums = $conn->query($sql_albums);
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discography | aespa Fanpage</title>
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
            <div class="back-link-container">
                <a href="index.php" class="back-link">Back to SYNK</a>
            </div>
            <h1 class="index-title">Album Nhạc</h1>

            <div class="album-grid">
                <?php
                if ($result_albums->num_rows > 0) {
                    while ($album = $result_albums->fetch_assoc()) {
                        echo '<a href="album.php?id=' . $album['id'] . '" class="album-link">';
                        echo '  <div class="album-card">';
                        echo '      <div class="album-cover">';
                        echo '          <img src="images/' . htmlspecialchars($album['cover_image']) . '" alt="Bìa album ' . htmlspecialchars($album['title']) . '">';
                        echo '      </div>';
                        echo '      <div class="album-info">';
                        echo '          <h3>' . htmlspecialchars($album['title']) . '</h3>';
                        echo '          <p>' . htmlspecialchars($album['type']) . ' &bull; ' . date("Y", strtotime($album['release_date'])) . '</p>';
                        echo '      </div>';
                        echo '  </div>';
                        echo '</a>';
                    }
                } else {
                    echo "<p>Chưa có thông tin album.</p>";
                }
                ?>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>
    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>
    <script src="js/header_updater.js"></script>
    <script src="js/mobile-nav.js"></script>
</body>

</html>
<?php $conn->close(); ?>