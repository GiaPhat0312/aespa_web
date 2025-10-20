<?php require_once 'auth.php'; // Gọi "lính gác" để kiểm tra đăng nhập ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Trang Quản Lý</title>
    <link rel="stylesheet" href="../css/styleAdmin.css"> <link rel="icon" type="image/png" href="../images/favicon.png"> </head>
<body>
    <div id="particles-js"></div>
    <div class="container">
        <h1>Trang Quản Lý</h1>
        <p style="text-align: center; color: var(--text-secondary);">
            Xin chào, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>!
        </p>
        
        <h2>Quản Lý Nội Dung</h2>
        <div class="dashboard-grid">
            <a href="manage_albums.php" class="dashboard-item-link">
                <div class="dashboard-item">
                    <svg class="dashboard-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22,13V15H20.74C20.4,16.16 19.74,17.16 18.83,17.89L20,19.06L18.59,20.47L17.29,19.17C16.5,19.73 15.58,20.13 14.54,20.31L14.54,22H12.46L12.46,20.31C11.42,20.13 10.5,19.73 9.71,19.17L8.41,20.47L7,19.06L8.17,17.89C7.26,17.16 6.6,16.16 6.26,15H5V13H6.26C6.6,11.84 7.26,10.84 8.17,10.11L7,8.94L8.41,7.53L9.71,8.83C10.5,8.27 11.42,7.87 12.46,7.69L12.46,6H14.54L14.54,7.69C15.58,7.87 16.5,8.27 17.29,8.83L18.59,7.53L20,8.94L18.83,10.11C19.74,10.84 20.4,11.84 20.74,13H22M13.5,14A1.5,1.5 0 0,0 12,12.5A1.5,1.5 0 0,0 10.5,14A1.5,1.5 0 0,0 12,15.5A1.5,1.5 0 0,0 13.5,14M5,4H19A2,2 0 0,1 21,6V11.17C20.5,11.07 20,11 19.5,11H18.5A2.5,2.5 0 0,0 16,13.5A2.5,2.5 0 0,0 18.5,16H19.5C20,16 20.5,15.93 21,15.83V20A2,2 0 0,1 19,22H5A2,2 0 0,1 3,20V6A2,2 0 0,1 5,4Z"/></svg>
                    <h3>Quản Lý Album</h3>
                    <p>Thêm, sửa, xóa album và bài hát.</p>
                </div>
            </a>
            
            <a href="manage_news.php" class="dashboard-item-link">
                <div class="dashboard-item">
                    <svg class="dashboard-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20,22H4A2,2 0 0,1 2,20V4A2,2 0 0,1 4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22M11,19H13V17H11V19M11,15H13V10H11V15M4,6V20H20V6H4Z" /></svg>
                    <h3>Quản Lý Tin Tức</h3>
                    <p>Đăng bài, cập nhật tin tức mới nhất.</p>
                </div>
            </a>
            <a href="merch/manage.php" class="dashboard-item-link">
                <div class="dashboard-item">
                    <svg class="dashboard-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M20 6H4V4H20V6M20.32 8H3.68L2 12V20H22V12L20.32 8M18 18H6V12.91L7.09 11H16.91L18 12.91V18M12 13C10.9 13 10 13.9 10 15H11.5C11.5 14.72 11.72 14.5 12 14.5S12.5 14.72 12.5 15H14C14 13.9 13.1 13 12 13Z"/></svg>
                    <h3>Quản Lý Vật Phẩm</h3>
                    <p>Trưng bày lightstick, photocard...</p>
                </div>
            </a>
        </div>

        <a href="../logout.php" class="logout-btn">Đăng xuất</a> </div>
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="../js/app.js"></script>
</body>
</html>