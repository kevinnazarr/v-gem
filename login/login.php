<?php
session_start();
require_once '../koneksi.php';

// Redirect if already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: " . ($_SESSION['username'] === 'admin' ? '../admin/admin_dashboard.php' : '../dashboard_user.php'));
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($password)) {
        $error = 'Username and password must be filled!';
    } else {
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: ../admin/admin_dashboard.php");
                exit();
            } elseif (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: ../dashboard_user.php");
                exit();
            } else {
                $error = 'Wrong password!';
            }
        } else {
            $error = 'Username not found!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | VGEM</title>
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>
    <video autoplay muted loop id="login_bg" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: 0;">
        <source src="../aset/login.mp4" type="video/mp4">
    </video>
    <div class="login-wrapper" style="position: relative; z-index: 2;">
        <div class="login-container">
            <div style="margin-bottom: 20px;">
            <a href="../index.php" class="btn-back" style="display: inline-block; padding: 8px 16px; background: #222; color: #fff; border-radius: 4px; text-decoration: none; font-family: 'Roboto', sans-serif;">
                &larr; Kembali
            </a>
            </div>
            <div class="login-header" style="font-family: 'Orbitron', 'Audiowide', 'Press Start 2P', 'Roboto', sans-serif;">
                <div class="logo" style="font-family: 'Orbitron', 'Audiowide', 'Press Start 2P', 'Roboto', sans-serif;">
                    <a href="../index.php"><span class="v">V</span><span class="gem">GEM</span></a>
                </div>
                <h2 style="font-family: 'Orbitron', 'Audiowide', 'Press Start 2P', 'Roboto', sans-serif;">Masuk ke Akun Anda</h2>
            </div>
            
            <form method="POST" class="login-form">
                <?php if ($error): ?>
                    <div class="error-message"><?= $error ?></div>
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" placeholder="Enter your username" required>
                    <i class="fas fa-user"></i>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <i class="fas fa-lock"></i>
                </div>
                
                <button type="submit" class="btn-login">Login</button>
                
                <div class="login-footer">
                    <p>Belum punya akun? <a href="sign_in.php">Daftar di sini</a></p>
                </div>
            </form>
        </div>
    </div>
    <div class="particles" id="particles-js" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="./java/main.js"></script>


</body>
</html>