<?php
include 'koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VGEM - Toko Game Terbaik</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="preloader">
        <div class="loader">
            <div class="pulse"></div>
            <div class="pulse"></div>
            <div class="pulse"></div>
        </div>
    </div>

    <header class="futuristic-header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="index.php"><span class="v">V</span><span class="gem">GEM</span></a>
                </div>
                <ul class="nav-links">
                    <li><a href="#home" class="active">Beranda</a></li>
                    <li><a href="#games">Game</a></li>
                    <li><a href="#features">Fitur</a></li>
                    <li><a href="#testimonials">Ulasan</a></li>
                    <li><a href="#contact">Kontak</a></li>
                </ul>
                <div class="auth-buttons">
                    <a href="login/login.php" class="btn-login">login</a>
                    <a href="login/sign_in.php" class="btn-signup">sign in</a>
                </div>
                <div class="mobile-menu-btn">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>
    <section id="home" class="hero" style="position: relative;">
        <video autoplay muted loop playsinline class="hero-bg-video" style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;z-index:0;">
            <source src="aset/bg_video.mp4" type="video/mp4">
        </video>
        <div class="container" style="position: relative; z-index: 2;">
            <div class="hero-content">
                <h1 class="hero-title">Masuki <span>Masa Depan</span> Dunia Game</h1>
                <p class="hero-subtitle">Temukan pengalaman bermain game paling imersif dengan teknologi mutakhir</p>
                <div class="hero-buttons">
                    <a href="#games" class="btn-explore">
                        Jelajahi Game
                    </a>
                    <a href="#features" class="btn-learn-more">
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>
        <div class="particles" id="particles-js" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></div>
    </section>

    <section id="games" class="featured-games">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Game <span>Unggulan</span></h2>
                <p class="section-subtitle">Jelajahi koleksi game premium kami</p>
            </div>

            <div class="view-all">
                <a href="dashboard_user.php" class="btn-view-all">Lihat Semua Game</a>
            </div>
        </div>
    </section>

    <section id="features" class="features">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Kenapa Pilih <span>VGEM</span></h2>
                <p class="section-subtitle">Rasakan pengalaman bermain game yang belum pernah ada sebelumnya</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3>Pengiriman Instan</h3>
                    <p>Dapatkan game Anda langsung setelah pembelian dengan sistem pengiriman super cepat kami.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3>Pembayaran Aman</h3>
                    <p>Transaksi Anda dilindungi dengan teknologi enkripsi tingkat tinggi.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3>Dukungan 24/7</h3>
                    <p>Sistem dukungan berbasis AI kami siap membantu Anda kapan saja.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <h3>Konten Eksklusif</h3>
                    <p>Akses edisi spesial dan konten yang hanya tersedia di VGEM.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Hubungi <span>Kami</span></h2>
                <p class="section-subtitle">Kami siap mendengar dari Anda</p>
            </div>
            <div class="contact-content">
                <div class="contact-info">
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <h3>Alamat</h3>
                        <p>Jl. Masa Depan 123, Distrik Cyber</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone-alt"></i>
                        <h3>Telepon</h3>
                        <p>+62 812-3456-7890</p>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <h3>Email</h3>
                        <p>support@vgem.com</p>
                    </div>
                </div>
                <div class="contact-form">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <input type="text" name="name" placeholder="Nama Anda" required>
                        </div>
                        <div class="form-group">
                            <input type="email" name="email" placeholder="Email Anda" required>
                        </div>
                        <div class="form-group">
                            <input type="text" name="subject" placeholder="Subjek" required>
                        </div>
                        <div class="form-group">
                            <textarea name="message" placeholder="Pesan Anda" required></textarea>
                        </div>
                        <button type="submit" class="btn-send">Kirim Pesan</button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="futuristic-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="logo">
                        <a href="index.php"><span class="v">V</span><span class="gem">GEM</span></a>
                    </div>
                    <p>Masa depan dunia game ada di sini. VGEM menghadirkan pengalaman bermain game tercanggih dengan teknologi mutakhir.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-discord"></i></a>
                    </div>
                </div>
                <div class="footer-links">
                    <h3>Tautan Cepat</h3>
                    <ul>
                        <li><a href="#home">Beranda</a></li>
                        <li><a href="#games">Game</a></li>
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#testimonials">Ulasan</a></li>
                        <li><a href="#contact">Kontak</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2023 VGEM. Hak Cipta Dilindungi. Dirancang dengan <i class="fas fa-heart"></i> untuk gamer.</p>
            </div>
        </div>
    </footer>

    <script src="java/main.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS.load('particles-js', 'aset/particles.json', function() {
            console.log('callback - particles.js config loaded');
        });
    </script>

</body>
</html>