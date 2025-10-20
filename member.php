<?php
// 1. Kết nối CSDL
require_once 'config/database.php';

// 2. Kiểm tra và lấy ID thành viên
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Lỗi: ID thành viên không hợp lệ.");
}
$member_id = intval($_GET['id']);

// 3. Lấy thông tin thành viên
$stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Không tìm thấy thành viên này.");
}
$member = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile: <?php echo htmlspecialchars($member['stage_name']); ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="images/favicon.png">
</head>
<body>
    <div id="particles-js"></div>

    <?php include 'partials/header.php'; ?>
    
    <main id="swup" class="transition-fade">
        <div class="container">
            <div class="back-link-container">
                <a href="index.php" class="back-link">Back to SYNK</a>
            </div>
            <div class="member-profile">
                <div class="member-photo">
                    <img src="images/<?php echo htmlspecialchars($member['member_image']); ?>" alt="Ảnh của <?php echo htmlspecialchars($member['stage_name']); ?>">
                </div>
                <div class="member-details">
                    <h1><?php echo htmlspecialchars($member['stage_name']); ?></h1>
                    <p><b>Tên thật:</b> <?php echo htmlspecialchars($member['birth_name']); ?></p>
                    <p><b>Ngày sinh:</b> <?php echo date("d/m/Y", strtotime($member['birth_date'])); ?></p>
                    <p><b>Quốc tịch:</b> <?php echo htmlspecialchars($member['nationality']); ?></p>
                    <p><b>Vị trí:</b> <?php echo htmlspecialchars($member['position']); ?></p>
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>

    <script src="https://unpkg.com/swup@4"></script>
    <script src="js/transitions.js"></script>

    <?php
    $stmt->close();
    $conn->close();
    ?>
</body>
</html>