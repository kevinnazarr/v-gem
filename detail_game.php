<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

$game_id = $_GET['game_id'];
$stmt = $conn->prepare("SELECT * FROM game WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    header("Location: dashboard_user.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($game['name']) ?> | VGEM</title>
    <link rel="stylesheet" href="css/detail_game.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="futuristic-header">
    </header>

    <main class="container">
        <section class="game-detail-container">
            <div class="game-image">
                <img src="aset/img/game/<?= htmlspecialchars($game['image_url']) ?>" 
                     alt="<?= htmlspecialchars($game['name']) ?>">
            </div>
            <div class="nav-back" style="margin-bottom: 20px;">
                <a href="dashboard_user.php" class="btn-back">
                    <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
            
            <div class="game-info">
                <h1 class="section-title"><?= htmlspecialchars($game['name']) ?></h1>
                <div class="game-meta">
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        <?= htmlspecialchars($game['release_date']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-tag"></i>
                        <?= htmlspecialchars($game['genre']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-gamepad"></i>
                        <?= htmlspecialchars($game['platform']) ?>
                    </div>
                    <div class="meta-item">
                        <i class="fas fa-building"></i>
                        <?= htmlspecialchars($game['publisher']) ?>
                    </div>
                </div>
                
                <div class="game-price">Rp <?= number_format($game['price'], 0, ',', '.') ?></div>
                <p class="game-description"><?= nl2br(htmlspecialchars($game['description'])) ?></p>
                
                <div class="game-specs">
                    <h3><i class="fas fa-microchip"></i> Spesifikasi Minimum</h3>
                    <div class="spec-item">
                        <span>Sistem Operasi:</span>
                        <?= htmlspecialchars($game['min_os']) ?>
                    </div>
                    <div class="spec-item">
                        <span>Prosesor:</span>
                        <?= htmlspecialchars($game['min_processor']) ?>
                    </div>
                    <div class="spec-item">
                        <span>RAM:</span>
                        <?= htmlspecialchars($game['min_ram']) ?>
                    </div>
                    <div class="spec-item">
                        <span>GPU:</span>
                        <?= htmlspecialchars($game['min_gpu']) ?>
                    </div>
                    <div class="spec-item">
                        <span>Penyimpanan:</span>
                        <?= htmlspecialchars($game['min_storage']) ?>
                    </div>
                </div>

                <?php if ($game['rec_os'] || $game['rec_processor'] || $game['rec_ram']): ?>
                <div class="game-specs rec-specs">
                    <h3><i class="fas fa-rocket"></i> Spesifikasi Direkomendasikan</h3>
                    <?php if ($game['rec_os']): ?>
                    <div class="spec-item">
                        <span>Sistem Operasi:</span>
                        <?= htmlspecialchars($game['rec_os']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($game['rec_processor']): ?>
                    <div class="spec-item">
                        <span>Prosesor:</span>
                        <?= htmlspecialchars($game['rec_processor']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($game['rec_ram']): ?>
                    <div class="spec-item">
                        <span>RAM:</span>
                        <?= htmlspecialchars($game['rec_ram']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($game['rec_gpu']): ?>
                    <div class="spec-item">
                        <span>GPU:</span>
                        <?= htmlspecialchars($game['rec_gpu']) ?>
                    </div>
                    <?php endif; ?>
                    <?php if ($game['rec_storage']): ?>
                    <div class="spec-item">
                        <span>Penyimpanan:</span>
                        <?= htmlspecialchars($game['rec_storage']) ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <a href="transaksi/beli.php?game_id=<?= $game['id'] ?>" 
                   class="btn-explore">
                    <i class="fas fa-shopping-cart"></i> Beli Sekarang
                </a>
            </div>
        </section>
    </main>
</body>
</html>

