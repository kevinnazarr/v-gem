<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_query = $conn->prepare("SELECT * FROM users WHERE id = ?");
$user_query->execute([$user_id]);
$user_data = $user_query->fetch();

$wishlist_query = $conn->prepare("SELECT game_id FROM wishlist WHERE user_id = ?");
$wishlist_query->execute([$user_id]);
$wishlist = $wishlist_query->fetchAll();
$wishlist_ids = array_column($wishlist, 'game_id');

$search = isset($_GET['search']) ? $_GET['search'] : '';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

$query = "SELECT * FROM game WHERE name LIKE ?";
$params = ["%$search%"];

if ($category !== 'all') {
    $query .= " AND genre = ?";
    $params[] = $category;
}

switch ($sort) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    default:
        $query .= " ORDER BY release_date DESC";
}

$games_query = $conn->prepare($query);
$games_query->execute($params);
$games_result = $games_query->fetchAll();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna | VGEM</title>
    <link rel="stylesheet" href="css/dashboard_user.css">
    <script src="https://unpkg.com/gsap@3.9.0/dist/gsap.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
</head>
<body>
    <div class="preloader">
        <div class="stardust-loader">
            <div class="particle"></div>
            <div class="particle"></div>
            <div class="particle"></div>
        </div>
    </div>

    <header class="cyber-header">
        <div class="hologram-bar"></div>
        <nav class="nav-container">
            <div class="logo">
                <a href="index.php" class="neon-text">VGEM</a>
            </div>

            <div class="search-wrapper">
                <form method="GET" class="search-form">
                    <div class="input-group">
                        <input type="text" name="search" placeholder="Cari game..." 
                               value="<?= htmlspecialchars($search) ?>">
                        <button type="submit" class="search-btn">
                            <i data-lucide="search"></i>
                        </button>
                    </div>
                    <div class="filter-group">
                        <select name="category" class="cyber-select">
                            <option value="all" <?= $category === 'all' ? 'selected' : '' ?>>Semua Genre</option>
                            <option value="action" <?= $category === 'action' ? 'selected' : '' ?>>Aksi</option>
                            <option value="rpg" <?= $category === 'rpg' ? 'selected' : '' ?>>RPG</option>
                        </select>
                        <select name="sort" class="cyber-select">
                            <option value="latest" <?= $sort === 'latest' ? 'selected' : '' ?>>Terbaru</option>
                            <option value="price_asc" <?= $sort === 'price_asc' ? 'selected' : '' ?>>Harga Terendah</option>
                            <option value="price_desc" <?= $sort === 'price_desc' ? 'selected' : '' ?>>Harga Tertinggi</option>
                        </select>
                    </div>
                </form>
            </div>

            <div class="user-panel">
                <a href="wishlist.php" class="wishlist-btn">
                    <i data-lucide="heart"></i>
                    <span class="counter"><?= count($wishlist_ids) ?></span>
                </a>
                <div class="user-avatar" onclick="toggleMenu()">
                    <i data-lucide="user"></i>
                    <div class="dropdown-menu" id="userMenu">
                        <a href="profile.php"><i data-lucide="settings"></i> Profil</a>
                        <a href="transaksi/transaksi.php"><i data-lucide="shopping-cart"></i> Pesanan</a>
                        <a href="logout.php"><i data-lucide="log-out"></i> Keluar</a>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="main-content">
        <section class="game-grid-section">
            <div class="section-title">
                <h1>Jelajahi <span>Game</span></h1>
                <p>Temukan game terbaik untuk koleksimu</p>
            </div>

            <div class="game-grid">
                <?php if (count($games_result) > 0): ?>
                    <?php foreach ($games_result as $game): ?>
                        <div class="game-card" data-game-id="<?= $game['id'] ?>">
                            <div class="card-inner">
                                <div class="game-thumbnail">
                                    <img src="aset/img/game/<?= htmlspecialchars($game['image_url']) ?>" 
                                         alt="<?= htmlspecialchars($game['name']) ?>">
                                    <div class="hover-overlay">
                                        <button class="btn-wishlist <?= in_array($game['id'], array_column($wishlist, 'game_id')) ? 'active' : '' ?>" 
                                                onclick="toggleWishlist(<?= $game['id'] ?>)">
                                            <i data-lucide="heart"></i>
                                        </button>
                                        <a href="detail_game.php?game_id=<?= $game['id'] ?>" class="btn-detail">
                                            Lihat Detail
                                        </a>
                                    </div>
                                </div>
                                <div class="game-info">
                                    <h3><?= htmlspecialchars($game['name']) ?></h3>
                                    <div class="price-tag">
                                        Rp <?= number_format($game['price'], 0, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="empty-state">
                        <i data-lucide="gamepad"></i>
                        <h3>Tidak Ada Game Ditemukan</h3>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <script>
        lucide.createIcons();

        gsap.from(".game-card", {
            duration: 1,
            y: 100,
            opacity: 0,
            stagger: 0.1,
            ease: "power4.out"
        });

        async function toggleWishlist(gameId) {
            try {
                const response = await fetch(`wishlist_action.php?game_id=${gameId}`);
                const result = await response.json();
                
                if (result.status === 'added') {
                    document.querySelector(`.btn-wishlist[data-game-id="${gameId}"]`).classList.add('active');
                    document.querySelector('.counter').textContent = parseInt(document.querySelector('.counter').textContent) + 1;
                } else {
                    document.querySelector(`.btn-wishlist[data-game-id="${gameId}"]`).classList.remove('active');
                    document.querySelector('.counter').textContent = parseInt(document.querySelector('.counter').textContent) - 1;
                }
            } catch (error) {
                console.error('Error:', error);
            }
        }

        window.addEventListener('load', () => {
            gsap.to(".preloader", {
                opacity: 0,
                duration: 0.5,
                onComplete: () => document.querySelector('.preloader').remove()
            });
        });

        document.querySelectorAll('.card-inner').forEach(card => {
            card.addEventListener('mousemove', e => {
                const rect = card.getBoundingClientRect();
                const x = e.clientX - rect.left;
                const y = e.clientY - rect.top;
                card.style.setProperty('--mouse-x', `${x}px`);
                card.style.setProperty('--mouse-y', `${y}px`);
            });
        });

        function toggleMenu() {
            document.getElementById('userMenu').classList.toggle('show');
        }

        document.addEventListener('click', function(e) {
            const menu = document.getElementById('userMenu');
            const avatar = document.querySelector('.user-avatar');
            if (!avatar.contains(e.target) && menu.classList.contains('show')) {
                menu.classList.remove('show');
            }
        });
    </script>
</body>
</html>
