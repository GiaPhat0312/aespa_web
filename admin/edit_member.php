<?php
// 1. Kiểm tra đăng nhập
require_once 'auth.php';
// 2. Kết nối CSDL
require_once '../config/database.php';
$message = '';

// 3. Lấy ID thành viên từ URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php"); // Quay về dashboard nếu ID lỗi
    exit();
}
$member_id = intval($_GET['id']);

// 4. Xử lý khi người dùng nhấn nút "Lưu Thay Đổi"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lấy dữ liệu từ form
    $stage_name = trim($_POST['stage_name']);
    $birth_name = trim($_POST['birth_name']);
    $birth_date = $_POST['birth_date'];
    $nationality = trim($_POST['nationality']);
    $position = trim($_POST['position']);
    $instagram_url = trim($_POST['instagram_url']); // Lấy link Instagram
    $old_image = $_POST['old_image']; // Lấy tên ảnh cũ
    $image_name = $old_image; // Mặc định giữ ảnh cũ

    // Validate dữ liệu (ví dụ cơ bản)
    if (empty($stage_name) || empty($birth_name) || empty($birth_date) || empty($nationality) || empty($position)) {
        $message = '<div class="message error">Vui lòng nhập đầy đủ thông tin bắt buộc.</div>';
    } else {
        // Xử lý upload ảnh mới (nếu có)
        if (isset($_FILES['member_image']) && $_FILES['member_image']['error'] == 0) {
            $target_dir = "../images/"; // Thư mục lưu ảnh thành viên (ở gốc)
            // Tạo tên file duy nhất
            $image_name = time() . '_' . basename($_FILES["member_image"]["name"]);
            $target_file = $target_dir . $image_name;

            // Di chuyển file ảnh mới
            if (move_uploaded_file($_FILES["member_image"]["tmp_name"], $target_file)) {
                // Xóa ảnh cũ nếu upload thành công và ảnh cũ không phải mặc định (nếu có)
                if (!empty($old_image) && file_exists($target_dir . $old_image)) {
                     // Thêm kiểm tra nếu bạn có ảnh mặc định
                     // if ($old_image != 'default_member.png') {
                           unlink($target_dir . $old_image);
                     // }
                }
            } else {
                $message = '<div class="message error">Lỗi upload ảnh mới. Giữ lại ảnh cũ.</div>';
                $image_name = $old_image; // Nếu lỗi upload, giữ lại tên ảnh cũ
            }
        }

        // Cập nhật vào database (chỉ cập nhật nếu không có lỗi validation)
        if (empty($message)) {
            $stmt = $conn->prepare("UPDATE members SET 
                stage_name = ?, 
                birth_name = ?, 
                birth_date = ?, 
                nationality = ?, 
                position = ?, 
                member_image = ?,
                instagram_url = ? 
                WHERE id = ?");
            // Kiểm tra lỗi prepare
             if (!$stmt) {
                 $message = '<div class="message error">Lỗi chuẩn bị SQL: ' . $conn->error . '</div>';
             } else {
                // bind_param: s (string), s, s, s, s, s, s, i (integer)
                $stmt->bind_param("sssssssi", 
                    $stage_name, 
                    $birth_name, 
                    $birth_date, 
                    $nationality, 
                    $position, 
                    $image_name,
                    $instagram_url, // Thêm Instagram URL
                    $member_id
                );

                if ($stmt->execute()) {
                    $message = '<div class="message success">Cập nhật thông tin thành viên thành công!</div>';
                    // Không chuyển hướng ngay để người dùng thấy thông báo
                    // header("Location: manage_members.php"); // (Nếu bạn có trang quản lý riêng)
                    // exit();
                } else {
                    $message = '<div class="message error">Lỗi cập nhật database: ' . $stmt->error . '</div>';
                }
                $stmt->close();
             }
        }
    } // Kết thúc else validate
} // Kết thúc if POST

// 5. Lấy thông tin hiện tại của thành viên để hiển thị trong form
$stmt_get = $conn->prepare("SELECT * FROM members WHERE id = ?");
if (!$stmt_get) { die("Lỗi chuẩn bị SQL lấy thành viên: " . $conn->error); }
$stmt_get->bind_param("i", $member_id);
$stmt_get->execute();
$result_get = $stmt_get->get_result();
if ($result_get->num_rows === 0) {
    echo "Không tìm thấy thành viên."; // Hoặc chuyển hướng
    exit();
}
$member = $result_get->fetch_assoc();
$stmt_get->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Thông Tin Thành Viên | Admin</title>
    <link rel="stylesheet" href="../css/styleAdmin.css"> <link rel="icon" type="image/png" href="../images/favicon.png">
</head>
<body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="../videos/1021.mp4" type="video/mp4">
        </video>
    </div>
    <div class="container">
        <div class="back-link-container">
            <a href="manage_members.php" class="back-link">Về Dashboard</a>
            </div>
        <div class="admin-header">
            <h1>Sửa Thông Tin: <?= htmlspecialchars($member['stage_name']) ?></h1>
        </div>
        <?= $message; ?> <form action="edit_member.php?id=<?= $member_id ?>" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="stage_name">Nghệ danh</label>
                        <input type="text" id="stage_name" name="stage_name" value="<?= htmlspecialchars($member['stage_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="birth_name">Tên thật</label>
                        <input type="text" id="birth_name" name="birth_name" value="<?= htmlspecialchars($member['birth_name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="birth_date">Ngày sinh</label>
                        <input type="date" id="birth_date" name="birth_date" value="<?= htmlspecialchars($member['birth_date']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="nationality">Quốc tịch</label>
                        <input type="text" id="nationality" name="nationality" value="<?= htmlspecialchars($member['nationality']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="position">Vị trí</label>
                        <input type="text" id="position" name="position" value="<?= htmlspecialchars($member['position']) ?>" required>
                    </div>
                     <div class="form-group">
                        <label for="instagram_url">Link Instagram (URL đầy đủ)</label>
                        <input type="url" id="instagram_url" name="instagram_url" value="<?= htmlspecialchars($member['instagram_url'] ?? '') ?>" placeholder="https://www.instagram.com/username">
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Ảnh Đại Diện</h3>
                        <div class="image-preview" id="imagePreview">
                             <img src="../images/<?= htmlspecialchars($member['member_image']) ?>" alt="Xem trước" class="image-preview-image" style="display:block;">
                        </div>
                        <input type="file" id="member_image" name="member_image" class="image-input" accept="image/*">
                        <input type="hidden" name="old_image" value="<?= htmlspecialchars($member['member_image']) ?>">
                        <small>Chọn ảnh mới nếu muốn thay thế.</small>
                    </div>
                    <div class="sidebar-box">
                        <h3>Cập Nhật</h3>
                        <button type="submit" class="form-button">Lưu Thay Đổi</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../js/image-preview.js"></script>
</body>
</html>
<?php $conn->close(); ?>