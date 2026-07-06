<?php
session_start();
require_once '../koneksi.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../dashboard_user.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Semua field harus diisi!';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Format email tidak valid!';
    } elseif (strlen($password) < 8) {
        $error = 'Password harus minimal 8 karakter!';
    } elseif ($password !== $confirm_password) {
        $error = 'Password tidak cocok!';
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username, $email);
        $stmt->execute();
        
        if ($stmt->get_result()->num_rows > 0) {
            $error = 'Username atau email sudah digunakan!';
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $username, $email, $hashed_password);
            
            if ($stmt->execute()) {
                $_SESSION['signup_success'] = true;
                header("Location: login.php");
                exit();
            } else {
                $error = 'Pendaftaran gagal. Silakan coba lagi!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Game Store</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@500;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/sign_in.css">
</head>
<body>
    <video autoplay muted loop id="login_bg" style="position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: 0;">
    <source src="../aset/sign_in.mp4" type="video/mp4">
    </video>
    <div class="signup-wrapper" style="position: relative; z-index: 2;">
        <div class="signup-container">
            <div style="margin-bottom: 20px;">
            <a href="../index.php" class="btn-back" style="display: inline-block; padding: 8px 16px; background: #222; color: #fff; border-radius: 4px; text-decoration: none; font-family: 'Roboto', sans-serif;">
                &larr; Kembali
            </a>
            </div>
            <div class="signup-header">
                <div class="logo">
                    <span class="v">V</span><span class="gem">GEM</span>
                </div>
                <h2>Buat Akun Baru</h2>
            </div>

            <?php if ($error): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="signup-form">
                <div class="form-group">
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-with-icon">
                        <i class="fas fa-envelope"></i>
                        <input type="email" id="email" name="email" placeholder="Masukkan email" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Konfirmasi Password</label>
                    <div class="input-with-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" id="confirm_password" name="confirm_password" placeholder="Ulangi password" required>
                    </div>
                </div>

                <button type="submit" class="btn-signup">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </button>
            </form>

            <div class="signup-footer">
                <p>Sudah punya akun? <a href="login.php">Masuk disini</a></p>
            </div>
        </div>
    </div>
    <div class="particles" id="particles-js" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
    <script src="https://cdn.jsdelivr.net/npm/particles.js@2.0.0/particles.min.js"></script>
    <script src="./java/main.js"></script>
</body>
</html>