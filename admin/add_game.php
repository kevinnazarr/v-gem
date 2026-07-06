<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

$error = '';
$success = '';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name          = trim($_POST['name']);
    $description   = trim($_POST['description']);
    $price         = trim($_POST['price']);
    $release_date  = trim($_POST['release_date']);
    $publisher     = trim($_POST['publisher']);
    $platform      = trim($_POST['platform']);
    $genre         = trim($_POST['genre']);

    $min_os        = trim($_POST['min_os']);
    $min_processor = trim($_POST['min_processor']);
    $min_ram       = trim($_POST['min_ram']);
    $min_gpu       = trim($_POST['min_gpu']);
    $min_storage   = trim($_POST['min_storage']);

    $rec_os        = trim($_POST['rec_os']);
    $rec_processor = trim($_POST['rec_processor']);
    $rec_ram       = trim($_POST['rec_ram']);
    $rec_gpu       = trim($_POST['rec_gpu']);
    $rec_storage   = trim($_POST['rec_storage']);

    $image_url = '';
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == UPLOAD_ERR_OK) {
        $upload_dir = '../aset/img/game/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $file_name = uniqid() . '_' . basename($_FILES['cover_image']['name']);
        $target_file = $upload_dir . $file_name;
        if (
            getimagesize($_FILES['cover_image']['tmp_name']) !== false &&
            move_uploaded_file($_FILES['cover_image']['tmp_name'], $target_file)
        ) {
            $image_url = $file_name;
        } else {
            $error = 'Gagal mengunggah gambar.';
    }
}

    if (
        empty($name) || empty($description) || empty($price) || empty($release_date) ||
        empty($publisher) || empty($platform) || empty($genre) ||
        empty($min_os) || empty($min_processor) || empty($min_ram) ||
        empty($min_gpu) || empty($min_storage) || empty($image_url)
    ) {
        $error = 'Semua field wajib diisi!';
    }

    if (empty($error)) {
        $sql = "INSERT INTO game 
        (name, description, price, release_date, publisher, platform, genre,
         min_os, min_processor, min_ram, min_gpu, min_storage,
         rec_os, rec_processor, rec_ram, rec_gpu, rec_storage, image_url)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        $stmt = $conn->prepare($sql);
        if ($stmt) {
            if ($stmt->execute([
                $name,
                $description,
                $price,
                $release_date,
                $publisher,
                $platform,
                $genre,
                $min_os,
                $min_processor,
                $min_ram,
                $min_gpu,
                $min_storage,
                $rec_os,
                $rec_processor,
                $rec_ram,
                $rec_gpu,
                $rec_storage,
                $image_url
            ])) {
                $success = 'Game berhasil ditambahkan!';
            } else {
                $error = 'Gagal menambahkan game: ' . implode(":", $stmt->errorInfo());
            }
            $stmt = null;
        } else {
            $error = 'Kesalahan query: ' . implode(":", $conn->errorInfo());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Game Baru | VGEM Admin</title>
    <link rel="stylesheet" href="css/add_game.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<header class="futuristic-header">
  <div class="container">
    <nav class="navbar">
      <div class="logo"><a href="../index.php"><span class="v">V</span><span class="gem">GEM</span></a></div>
      <div class="admin-menu">
        <span class="admin-badge">Admin</span>
        <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
      </div>
    </nav>
  </div>
</header>

<div class="container" style="margin-top:20px;">
  <a href="admin_dashboard.php" class="btn-submit"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
</div>

<main class="admin-container">
  <div class="container">
    <h1><i class="fas fa-plus-circle"></i> Tambah Game Baru</h1>

    <?php if ($error): ?>
      <div class="alert error"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="game-form">
      <div class="form-group">
        <label for="name">Judul Game</label>
        <input type="text" id="name" name="name" required>
      </div>

      <div class="form-group">
        <label for="description">Deskripsi</label>
        <textarea id="description" name="description" required></textarea>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="price">Harga (Rp)</label>
          <input type="number" id="price" name="price" step="1000" required>
        </div>
        <div class="form-group">
          <label for="release_date">Tanggal Rilis</label>
          <input type="date" id="release_date" name="release_date" required>
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="publisher">Publisher</label>
          <input type="text" id="publisher" name="publisher" required>
        </div>
        <div class="form-group">
          <label for="platform">Platform</label>
          <input type="text" id="platform" name="platform" placeholder="Contoh: PC, PS5" required>
        </div>
        <div class="form-group">
          <label for="genre">Genre</label>
          <input type="text" id="genre" name="genre" placeholder="Contoh: RPG, Action" required>
        </div>
      </div>

    <div class="form-group">
        <label>Spesifikasi Game</label>
        <div class="specs-container">
            <!-- Minimum Specifications -->
            <div class="spec-column min-specs">
                <h3><i class="fas fa-microchip"></i> Spesifikasi Minimum</h3>
                <div class="spec-grid">
                    <div>
                        <label for="min_os">OS</label>
                        <input type="text" id="min_os" name="min_os" required>
                    </div>
                    <div>
                        <label for="min_processor">Processor</label>
                        <input type="text" id="min_processor" name="min_processor" required>
                    </div>
                    <div>
                        <label for="min_ram">RAM</label>
                        <input type="text" id="min_ram" name="min_ram" required>
                    </div>
                    <div>
                        <label for="min_gpu">GPU</label>
                        <input type="text" id="min_gpu" name="min_gpu" required>
                    </div>
                    <div>
                        <label for="min_storage">Storage</label>
                        <input type="text" id="min_storage" name="min_storage" required>
                    </div>
                </div>
            </div>
            
            <div class="spec-column rec-specs">
                <h3><i class="fas fa-rocket"></i> Spesifikasi Direkomendasikan</h3>
                <div class="spec-grid">
                    <div>
                        <label for="rec_os">OS</label>
                        <input type="text" id="rec_os" name="rec_os">
                    </div>
                    <div>
                        <label for="rec_processor">Processor</label>
                        <input type="text" id="rec_processor" name="rec_processor">
                    </div>
                    <div>
                        <label for="rec_ram">RAM</label>
                        <input type="text" id="rec_ram" name="rec_ram">
                    </div>
                    <div>
                        <label for="rec_gpu">GPU</label>
                        <input type="text" id="rec_gpu" name="rec_gpu">
                    </div>
                    <div>
                        <label for="rec_storage">Storage</label>
                        <input type="text" id="rec_storage" name="rec_storage">
                    </div>
                </div>
            </div>
        </div>
    </div>

      <div class="form-group">
        <label for="cover_image">Cover Image</label>
        <div class="file-upload">
          <input type="file" id="cover_image" name="cover_image" accept="image/*" required>
          <label for="cover_image" class="file-upload-label">
            <i class="fas fa-cloud-upload-alt"></i> Pilih Gambar
          </label>
          <div id="file-name" class="file-name">Belum ada file</div>
        </div>
      </div>

      <button type="submit" class="btn-submit">
        <i class="fas fa-save"></i> Tambah Game
      </button>
    </form>
  </div>
</main>

 <footer class="futuristic-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-about">
                    <div class="logo">
                        <a href="../index.php"><span class="v">V</span><span class="gem">GEM</span></a>
                    </div>
                    <p>Masa depan dunia game ada di sini. VGEM menghadirkan pengalaman bermain game tercanggih dengan teknologi mutakhir.</p>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-discord"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 VGEM. Hak Cipta Dilindungi. Dirancang dengan untuk gamer.</p>
            </div>
        </div>
    </footer>

<div id="vanta-bg" style="position: fixed; bottom: 0; left: 0; width: 100vw; height: 100vh; z-index: -1;"></div>
<script src="https://cdn.jsdelivr.net/npm/three@0.150.1/build/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vanta/dist/vanta.globe.min.js"></script>
<script src="./js/vanta_add.js"></script>
<script>

    document.querySelectorAll('.form-group, .form-row, .spec-column, h1, .alert, .btn-submit, .admin-menu, .logo, .navbar, .container > a').forEach((el, i) => {
        el.style.opacity = 0;
        el.style.transform = 'translateY(30px)';
        setTimeout(() => {
            el.style.transition = 'opacity 0.7s cubic-bezier(.4,2,.3,1), transform 0.7s cubic-bezier(.4,2,.3,1)';
            el.style.opacity = 1;
            el.style.transform = 'translateY(0)';
        }, 200 + i * 120);
    });
  document.getElementById('cover_image').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || 'Belum ada file';
    document.getElementById('file-name').textContent = fileName;
  });
</script>
</body>
</html>
