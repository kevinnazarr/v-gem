<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../login/login.php");
    exit();
}

function resetGameIdOrder($conn) {
    $conn->exec("SELECT setval('game_id_seq', (SELECT MAX(id) FROM game), FALSE)");
}

function resetUserIdOrder($conn) {
    $conn->exec("SELECT setval('users_id_seq', (SELECT MAX(id) FROM users), FALSE)");
}

if (isset($_GET['delete_game'])) {
    $game_id = $_GET['delete_game'];
    $stmt = $conn->prepare("DELETE FROM game WHERE id = ?");
    $stmt->execute([$game_id]);
    resetGameIdOrder($conn); 
    header("Location: admin_dashboard.php?success=Game berhasil dihapus");
    exit();
}

if (isset($_GET['delete_user'])) {
    $user_id = $_GET['delete_user'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ? AND role != 'admin'");
    $stmt->execute([$user_id]);
    resetUserIdOrder($conn); 
    header("Location: admin_dashboard.php?success=Pengguna berhasil dihapus");
    exit();
}

function getStatistics($conn) {
    $stats = [
        'users_count' => 0,
        'games_count' => 0,
        'sales_total' => 0,
        'orders_count' => 0
    ];

    $query = "
        SELECT 
            (SELECT COALESCE(COUNT(*), 0) FROM users WHERE role = 'user') as users_count,
            (SELECT COALESCE(COUNT(*), 0) FROM game) as games_count,
            (SELECT COALESCE(SUM(total), 0) FROM orders WHERE status = 'completed') as sales_total,
            (SELECT COALESCE(COUNT(*), 0) FROM orders) as orders_count
    ";

    $result = $conn->query($query);
    
    if (!$result) {
        error_log("Database error: " . implode(":", $conn->errorInfo()));
        return $stats;
    }

    $stats = $result->fetch(PDO::FETCH_ASSOC);

    return $stats;
}

$stats = getStatistics($conn);
error_log(print_r($stats, true));

$recent_orders = $conn->query("
    SELECT o.id, u.username, o.total, o.order_date, o.status
    FROM orders o
    JOIN users u ON o.user_id = u.id
    ORDER BY o.order_date DESC
    LIMIT 5
")->fetchAll(PDO::FETCH_ASSOC);

$games = $conn->query("SELECT * FROM game ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC); 

$users = $conn->query("SELECT id, username, email, created_at FROM users WHERE role = 'user' ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);

$search_results = [];
if (isset($_GET['search'])) {
    $search_term = "%".$_GET['search']."%";
    $stmt = $conn->prepare("SELECT * FROM game WHERE name LIKE ?");
    $stmt->execute([$search_term]);
    $search_results = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin | VGEM</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700;900&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function(event) {
                const confirmMessage = this.getAttribute('data-confirm');
                if (!confirm(confirmMessage)) {
                    event.preventDefault();
                }
            });
        });
    });
    </script>
</head>
<body>
    <header class="futuristic-header">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <a href="admin_dashboard.php"><span class="v">V</span><span class="gem">GEM</span></a>
                </div>
                <div class="admin-menu">
                    <span class="admin-badge">Admin</span>
                    <span class="username"><?= htmlspecialchars($_SESSION['username']) ?></span>
                    <a href="logout.php" class="btn-logout">Keluar</a>
                </div>
            </nav>
        </div>
    </header>

    <main class="admin-container">
        <div class="container">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_GET['success']) ?>
                </div>
            <?php endif; ?>
            
            <h1><i class="fas fa-user-shield"></i> Dashboard Admin</h1>
            <div class="admin-stats">
                <div class="stat-card">
                    <h3>Total Pengguna</h3>
                    <p><?= number_format($stats['users_count']) ?></p>
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-card">
                    <h3>Total Game</h3>
                    <p><?= number_format($stats['games_count']) ?></p>
                    <i class="fas fa-gamepad"></i>
                </div>
                <div class="stat-card">
                    <h3>Total Penjualan</h3>
                    <p>Rp <?= number_format($stats['sales_total']) ?></p>
                    <i class="fas fa-chart-line"></i>
                </div>
                <div class="stat-card">
                    <h3>Total Pesanan</h3>
                    <p><?= number_format($stats['orders_count']) ?></p>
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="admin-actions">
                <a href="#game-management" class="action-card">
                    <i class="fas fa-gamepad"></i>
                    <h3>Kelola Game</h3>
                    <p>Edit atau hapus game</p>
                </a>
                <a href="#user-management" class="action-card">
                    <i class="fas fa-users"></i>
                    <h3>Kelola Pengguna</h3>
                    <p>Lihat dan kelola semua pengguna</p>
                </a>
                <a href="#sales-report" class="action-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Laporan Penjualan</h3>
                    <p>Lihat riwayat transaksi</p>
                </a>
                <a href="add_game.php" class="action-card">
                    <i class="fas fa-plus-circle"></i>
                    <h3>Tambah Game Baru</h3>
                    <p>Upload game baru</p>
                </a>
            </div>

            <section id="game-management" class="management-section">
                <h2><i class="fas fa-gamepad"></i> Manajemen Game</h2>
                <div class="games-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Sampul</th>
                                <th>Judul</th>
                                <th>Harga</th>
                                <th>Tanggal Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($games) > 0): ?>
                                <?php foreach ($games as $game): ?>
                                    <tr>
                                        <td><?= $game['id'] ?></td>
                                        <td>
                                            <img src="../aset/img/game/<?= htmlspecialchars($game['image_url']) ?>" alt="<?= htmlspecialchars($game['name']) ?>" class="game-cover">
                                        </td>
                                        <td><?= htmlspecialchars($game['name']) ?></td>
                                        <td>Rp <?= number_format($game['price']) ?></td>
                                        <td><?= date('d M Y', strtotime($game['created_at'])) ?></td>
                                        <td class="actions">
                                            <a href="./edit/edit_game.php?id=<?= $game['id'] ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="admin_dashboard.php?delete_game=<?= $game['id'] ?>" class="btn-delete" data-confirm="Yakin ingin menghapus game ini?" onclick="return confirm('Yakin ingin menghapus game ini?')">
                                                <i class="fas fa-trash-alt"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">Tidak ada game ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <section id="user-management" class="management-section">
                <h2><i class="fas fa-users"></i> Manajemen Pengguna</h2>
                <div class="users-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($users) > 0): ?>
                                <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= htmlspecialchars($user['username']) ?></td>
                                    <td><?= htmlspecialchars($user['email']) ?></td>
                                    <td><?= date('d M Y', strtotime($user['created_at'])) ?></td>
                                    <td class="actions">
                                        <a href="admin_dashboard.php?delete_user=<?= $user['id'] ?>" class="btn-delete" data-confirm="Yakin ingin menghapus pengguna ini?" onclick="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            <i class="fas fa-trash-alt"></i> Hapus
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada pengguna ditemukan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
            
            <section id="sales-report" class="management-section">
                <h2><i class="fas fa-chart-bar"></i> Pesanan Terbaru</h2>
                <div class="sales-table">
                    <table>
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pengguna</th>
                                <th>Jumlah</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($recent_orders) > 0): ?>
                                <?php foreach ($recent_orders as $order): ?>
                                <tr>
                                    <td><?= $order['id'] ?></td>
                                    <td><?= htmlspecialchars($order['username']) ?></td>
                                    <td>Rp <?= number_format($order['total']) ?></td>
                                    <td><?= date('d M Y H:i', strtotime($order['order_date'])) ?></td>
                                    <td><span class="status-badge <?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">Tidak ada data pesanan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanta@latest/dist/vanta.net.min.js"></script>
    <script src="./js/admin.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const sections = document.querySelectorAll('.management-section, .admin-stats, .admin-actions');
        sections.forEach((section, i) => {
            section.style.opacity = 0;
            section.style.transform = "translateY(40px)";
            setTimeout(() => {
                section.style.transition = "all 0.7s cubic-bezier(.77,0,.18,1)";
                section.style.opacity = 1;
                section.style.transform = "translateY(0)";
            }, 200 + i * 200);
        });
    });
    </script>
</body>
</html>