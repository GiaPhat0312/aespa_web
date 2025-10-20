<?php
// Thông tin để kết nối với cơ sở dữ liệu MySQL
$hostname = 'localhost';      // Thường là 'localhost' trên XAMPP
$username = 'root';           // Tên người dùng mặc định của XAMPP
$password = '';               // Mật khẩu mặc định của XAMPP là rỗng
$database = 'aespa_db';       // Tên cơ sở dữ liệu bạn đã tạo

// Tạo kết nối bằng MySQLi
$conn = new mysqli($hostname, $username, $password, $database);

// Thiết lập bảng mã ký tự thành utf8mb4 để hiển thị tiếng Việt chính xác
$conn->set_charset("utf8mb4");

// Kiểm tra nếu có lỗi kết nối thì dừng chương trình và thông báo lỗi
if ($conn->connect_error) {
    die("Lỗi kết nối CSDL: " . $conn->connect_error);
}
?>