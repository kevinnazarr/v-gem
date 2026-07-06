<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login/login.php");
    exit();
}

$game_id = $_GET['game_id'];
$stmt = $conn->prepare("SELECT * FROM game WHERE id = ?");
$stmt->bind_param("i", $game_id);
$stmt->execute();
$game = $stmt->get_result()->fetch_assoc();

if (!$game) {
    header("Location: ../dashboard_user.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("INSERT INTO orders 
            (user_id, total, status, payment_method, payment_status) 
            VALUES (?, ?, 'completed', ?, 'paid')");
        $stmt->bind_param("ids", 
            $_SESSION['user_id'], 
            $game['price'], 
            $_POST['payment_method']
        );
        $stmt->execute();
        $order_id = $conn->insert_id;

        $stmt = $conn->prepare("INSERT INTO order_details 
            (order_id, game_id, quantity, price) 
            VALUES (?, ?, 1, ?)");
        $stmt->bind_param("iid", $order_id, $game_id, $game['price']);
        $stmt->execute();

        $conn->commit();
        header("Location: invoice.php?order_id=$order_id");
        exit();
    } catch (Exception $e) {
        $conn->rollback();
        die("Error: ".$e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | VGEM</title>
    <link rel="stylesheet" href="css/beli.css">
</head>
<body>
    <header class="futuristic-header">
    </header>

    <main class="container">
        <section class="payment-container">
            <h1 class="section-title">Pembayaran <?= htmlspecialchars($game['name']) ?></h1>
            <div class="game-price">Rp <?= number_format($game['price'], 0, ',', '.') ?></div>
            
            <form method="POST">
                <div class="payment-methods">
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="credit_card" required>
                        Kartu Kredit/Debit
                    </label>
                    
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="e-wallet">
                        E-Wallet (OVO/Gopay/Dana)
                    </label>
                    
                    <label class="payment-method">
                        <input type="radio" name="payment_method" value="bank_transfer">
                        Transfer Bank
                    </label>
                </div>

                <button type="submit" class="btn-explore">
                    <i class="fas fa-lock"></i> Lanjutkan Pembayaran
                </button>
            </form>
        </section>
    </main>
</body>
</html>