<?php
require_once '../auth.php';
require_once '../../config/database.php';
$message = '';
$success_count = 0; // Đếm số ảnh upload thành công
$error_count = 0;   // Đếm số ảnh upload lỗi

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $member_id = intval($_POST['member_id']);
    $caption = $_POST['caption']; // Chú thích chung

    // Kiểm tra đã chọn thành viên chưa
    if (empty($member_id)) {
        $message = '<div class="message error">Vui lòng chọn thành viên.</div>';
    } 
    // === SỬA LẠI: KIỂM TRA MẢNG $_FILES['images'] ===
    else if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) { 
        
        $target_dir = "../../images/photos/"; 
        if (!is_dir($target_dir)) mkdir($target_dir, 0755, true);

        // Chuẩn bị câu lệnh SQL một lần bên ngoài vòng lặp
        $stmt = $conn->prepare("INSERT INTO photos (member_id, caption, image_url) VALUES (?, ?, ?)");
        if (!$stmt) {
             $message = '<div class="message error">Lỗi chuẩn bị câu lệnh SQL: ' . $conn->error . '</div>';
        } else {
            // Lặp qua từng file ảnh được upload
            foreach ($_FILES['images']['name'] as $key => $name) {
                // Kiểm tra lỗi upload của từng file
                if ($_FILES['images']['error'][$key] == 0) {
                    
                    // Tạo tên file duy nhất
                    $image_name = time() . '_' . uniqid() . '_' . basename($name);
                    $target_file = $target_dir . $image_name;

                    // Di chuyển file ảnh
                    if (move_uploaded_file($_FILES['images']['tmp_name'][$key], $target_file)) {
                        // Lưu vào database
                        $stmt->bind_param("iss", $member_id, $caption, $image_name);
                        if ($stmt->execute()) {
                            $success_count++;
                        } else {
                            $error_count++;
                            // (Tùy chọn) Ghi log lỗi chi tiết hơn
                            error_log("Lỗi DB khi insert ảnh $image_name: " . $stmt->error); 
                        }
                    } else {
                        $error_count++;
                        error_log("Lỗi move_uploaded_file cho ảnh: " . $name);
                    }
                } else {
                    $error_count++;
                    error_log("Lỗi upload code " . $_FILES['images']['error'][$key] . " cho ảnh: " . $name);
                }
            } // Kết thúc vòng lặp foreach

            $stmt->close(); // Đóng câu lệnh prepare

            // Tạo thông báo kết quả
            if ($success_count > 0 && $error_count == 0) {
                $_SESSION['flash_message'] = "Đã upload thành công $success_count ảnh!";
                header("Location: manage.php"); 
                exit();
            } elseif ($success_count > 0 && $error_count > 0) {
                $message = '<div class="message warning">Đã upload thành công ' . $success_count . ' ảnh, nhưng có ' . $error_count . ' ảnh bị lỗi.</div>';
            } elseif ($success_count == 0 && $error_count > 0) {
                 $message = '<div class="message error">Tất cả ' . $error_count . ' ảnh đều bị lỗi khi upload. Vui lòng kiểm tra lại.</div>';
            } else {
                 $message = '<div class="message error">Không có ảnh nào được xử lý.</div>'; // Trường hợp lạ
            }
        } // Kết thúc else ($stmt)

    } else {
        $message = '<div class="message error">Vui lòng chọn ít nhất một file ảnh.</div>';
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Ảnh Mới | Admin</title>
    <link rel="stylesheet" href="../../css/styleAdmin.css"> <link rel="icon" type="image/png" href="../../images/favicon.png">
</head>
<body>
    <div class="video-background">
        <video autoplay loop muted playsinline>
            <source src="../../videos/bg-supernova.mp4" type="video/mp4"> </video>
    </div>
    <div class="container">
        <div class="back-link-container"><a href="manage.php" class="back-link">Quay lại Quản lý</a></div>
        <div class="admin-header"><h1>Thêm Ảnh Mới Vào Gallery</h1></div>
        <?= $message; ?>
        <form action="add.php" method="POST" enctype="multipart/form-data" class="admin-form">
            <div class="form-grid">
                <div class="form-main">
                    <div class="form-group">
                        <label for="member_id">Gắn ảnh cho thành viên</label>
                        <select id="member_id" name="member_id" required>
                            <option value="">-- Chọn thành viên --</option>
                            <option value="1">Karina</option>
                            <option value="2">Giselle</option>
                            <option value="3">Winter</option>
                            <option value="4">Ningning</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="caption">Chú thích chung (Tùy chọn)</label>
                        <input type="text" id="caption" name="caption" placeholder="Ví dụ: Sự kiện ABC 21/10...">
                        <small>Chú thích này sẽ áp dụng cho tất cả ảnh được upload lần này.</small>
                    </div>
                </div>
                <div class="form-sidebar">
                    <div class="sidebar-box">
                        <h3>Chọn Ảnh (Có thể chọn nhiều ảnh)</h3>
                        <div class="image-preview" id="imagePreview">
                            <span class="image-preview-text">Xem trước (ảnh đầu tiên)</span>
                        </div>
                        <input type="file" id="image" name="images[]" class="image-input" accept="image/*" required multiple> 
                        </div>
                    <div class="sidebar-box">
                        <h3>Đăng Ảnh</h3>
                        <button type="submit" class="form-button">Lưu Ảnh</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <script src="../../js/image-preview.js"></script> 
</body>
</html>