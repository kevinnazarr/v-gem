<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$order_id = $_GET['order_id'] ?? null;

if (isset($_GET['confirm'], $order_id) && $_GET['confirm'] === 'true') {
    $stmt = $conn->prepare("UPDATE orders SET payment_status = 'paid', status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    header("Location: invoice.php?order_id=$order_id");
    exit();
}

if (!$order_id) {
    header("Location: ../dashboard_user.php");
    exit();
}

$stmt = $conn->prepare(
    "SELECT o.*, g.name AS game_name 
     FROM orders o
     JOIN order_details od ON o.id = od.order_id
     JOIN game g ON od.game_id = g.id
     WHERE o.id = ? AND o.user_id = ?"
);
$stmt->bind_param("ii", $order_id, $_SESSION['user_id']);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    header("Location: ../dashboard_user.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #<?= $order['id'] ?> | VGEM</title>
    <link rel="stylesheet" href="invoice.css">
    <style>
        :root {
            --primary-color: #0af;
            --secondary-color: #8a2be2;
            --dark-bg: #0a0a2a;
            --darker-bg: #050515;
            --text-color: #e0e0ff;
            --neon-glow: 0 0 10px rgba(10, 175, 255, 0.7);
            --transition: all 0.3s ease;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Orbitron', sans-serif;
        }
        body {
            background: var(--dark-bg);
            color: var(--text-color);
            overflow-x: hidden;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .invoice-container {
            width: 100%;
            max-width: 800px;
            background: rgba(10, 10, 42, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            border: 1px solid var(--primary-color);
        }
        .section-title {
            font-size: 2rem;
            margin-bottom: 30px;
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: var(--neon-glow);
            position: relative;
            display: inline-block;
        }
        .transaction-details {
            margin-bottom: 40px;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .detail-label {
            font-weight: 600;
            color: rgba(224, 224, 255, 0.8);
        }
        .detail-value {
            font-weight: 700;
            color: var(--primary-color);
        }
        .status-badge {
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        .status-badge.completed {
            background: rgba(0, 255, 128, 0.2);
            color: #00ff80;
            text-shadow: 0 0 5px #00ff80;
        }
        .status-badge.pending {
            background: rgba(255, 255, 0, 0.2);
            color: #ffff00;
            text-shadow: 0 0 5px #ffff00;
        }
        .invoice-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }
        .btn-explore, .btn-learn-more {
            display: flex;
            align-items: center;
            padding: 12px 25px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 1rem;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }
        .btn-explore {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: white;
        }
        .btn-explore:hover {
            transform: translateY(-3px);
            box-shadow: 0 0 20px var(--secondary-color);
        }
        .btn-learn-more {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-color);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .btn-learn-more:hover {
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
        }
        .btn-explore i, .btn-learn-more i {
            margin-right: 10px;
        }
        .footer {
            text-align: center;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(224, 224, 255, 0.6);
            font-size: 0.9rem;
        }
        @media (max-width: 768px) {
            .invoice-container {
                padding: 30px 20px;
            }
            .detail-item {
                flex-direction: column;
                gap: 10px;
            }
            .detail-item:last-child {
                border-bottom: none;
            }
            .invoice-actions {
                flex-direction: column;
            }
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="invoice-container">
        <h1 class="section-title">
            Detail Pembayaran #ORD<?= str_pad($order['id'], 6, '0', STR_PAD_LEFT) ?>
        </h1>
        <div class="transaction-details">
            <div class="detail-item">
                <span class="detail-label">Tanggal Transaksi:</span>
                <span class="detail-value"><?= date('d M Y H:i', strtotime($order['order_date'])) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Nama Game:</span>
                <span class="detail-value"><?= htmlspecialchars($order['game_name']) ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Total Pembayaran:</span>
                <span class="detail-value">Rp <?= number_format($order['total'], 0, ',', '.') ?></span>
            </div>
            <div class="detail-item">
                <span class="detail-label">Status:</span>
                <span class="status-badge <?= $order['status'] ?>">
                    <?= ucfirst($order['status']) ?>
                </span>
            </div>
        </div>
        <div class="invoice-actions">
            <?php if ($order['payment_status'] === 'pending'): ?>
                <a href="?order_id=<?= $order_id ?>&confirm=true" class="btn-explore">
                    <i class="fas fa-check-circle"></i> Konfirmasi Pembayaran
                </a>
            <?php endif; ?>
            <button onclick="window.print()" class="btn-explore">
                <i class="fas fa-print"></i> Cetak
            </button>
            <a href="../dashboard_user.php" class="btn-learn-more">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> VGEM. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
