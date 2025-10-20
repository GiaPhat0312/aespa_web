<?php
require_once 'config/database.php';

// Kiểm tra ID album
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Lỗi: ID album không hợp lệ.");
}
$album_id = intval($_GET['id']);

// Lấy thông tin chi tiết của album
$stmt_album = $conn->prepare("SELECT * FROM albums WHERE id = ?");
$stmt_album->bind_param("i", $album_id);
$stmt_album->execute();
$result_album = $stmt_album->get_result();
if ($result_album->num_rows === 0) {
    die("Không tìm thấy album này.");
}
$album = $result_album->fetch_assoc();

// Lấy danh sách bài hát (tracklist) của album đó
$stmt_tracks = $conn->prepare("SELECT title, is_title_track FROM tracks WHERE album_id = ? ORDER BY id ASC");
$stmt_tracks->bind_param("i", $album_id);
$stmt_tracks->execute();
$result_tracks = $stmt_tracks->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($album['title']); ?> | aespa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/styleDiscography.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>

    <?php include 'partials/header.php'; ?>

    <main id="swup" class="transition-fade">
        <div class="container">
            <div class="back-link-container">
                <a href="discography.php" class="back-link">Back to Discography</a>
            </div>

            <div class="album-details-header">
                <div class="album-details-cover">
                    <img src="images/<?php echo htmlspecialchars($album['cover_image']); ?>" alt="Bìa album <?php echo htmlspecialchars($album['title']); ?>">
                </div>
                <div class="album-details-info">
                    <h1><?php echo htmlspecialchars($album['title']); ?></h1>
                    <p><b>Loại album:</b> <?php echo htmlspecialchars($album['type']); ?></p>
                    <p><b>Ngày phát hành:</b> <?php echo date("d/m/Y", strtotime($album['release_date'])); ?></p>
                </div>
            </div>

            <h2>Tracklist</h2>
            <ul class="tracklist">
                <?php
                if ($result_tracks->num_rows > 0) {
                    $track_number = 1;
                    while($track = $result_tracks->fetch_assoc()) {
                        echo '<li class="tracklist-item">';
                        echo '  <span class="track-number">' . str_pad($track_number, 2, '0', STR_PAD_LEFT) . '</span>';
                        echo '  <span class="track-title">' . htmlspecialchars($track['title']);
                        // Nếu là bài hát chủ đề thì thêm nhãn "TITLE"
                        if ($track['is_title_track']) {
                            echo '      <span class="title-track-marker">TITLE</span>';
                        }
                        echo '  </span>';
                        echo '</li>';
                        $track_number++;
                    }
                } else {
                    echo "<p>Chưa có danh sách bài hát cho album này.</p>";
                }
                ?>
            </ul>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>

    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>
    <script src="js/header_updater.js"></script>
</body>
</html>
<?php
$stmt_album->close();
$stmt_tracks->close();
$conn->close();
?>