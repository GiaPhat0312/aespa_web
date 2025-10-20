<?php
session_start();
require_once 'config/database.php';
$error = '';

if (isset($_SESSION['user_id'])) {
    header("Location: admin/index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $username;
            header("Location: admin/index.php");
            exit();
        } else {
            $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
        }
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link rel="stylesheet" href="css/styleLogin.css">
</head>
<body>
    <div id="particles-js"></div>

    <div class="container login-container">
        <h1>Admin Login</h1>
        <p class="login-subtitle">Enter the SYNK to manage content</p>

        <?php if (!empty($error)): ?>
            <div class="message error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="login.php" method="POST" class="admin-form">
            <div class="form-group">
                <div class="input-with-icon">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,4A4,4 0 0,1 16,8A4,4 0 0,1 12,12A4,4 0 0,1 8,8A4,4 0 0,1 12,4M12,14C16.42,14 20,15.79 20,18V20H4V18C4,15.79 7.58,14 12,14Z" /></svg>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
            </div>
            <div class="form-group">
                <div class="input-with-icon">
                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12,17A2,2 0 0,0 14,15A2,2 0 0,0 12,13A2,2 0 0,0 10,15A2,2 0 0,0 12,17M18,8H17V6A5,5 0 0,0 12,1A5,5 0 0,0 7,6V8H6A2,2 0 0,0 4,10V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V10A2,2 0 0,0 18,8M12,3A3,3 0 0,1 15,6V8H9V6A3,3 0 0,1 12,3Z" /></svg>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
            </div>
            <button type="submit" class="form-button">Login</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="js/app.js"></script>
</body>
</html>