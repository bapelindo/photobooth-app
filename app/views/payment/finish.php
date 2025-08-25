<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <style>
        body { font-family: 'Comic Sans MS', cursive, sans-serif; background-color: #f0f8ff; text-align: center; padding: 50px; }
        .container { background: white; padding: 40px; border-radius: 20px; box-shadow: 0 10px 20px rgba(0,0,0,0.1); display: inline-block; }
        h1 { font-size: 2.5em; }
        .success { color: #28a745; }
        .pending { color: #ffc107; }
        .failure { color: #dc3545; }
        p { font-size: 1.2em; }
        .action-button {
            background-color: #ff6347; color: white; padding: 15px 30px; border: none;
            border-radius: 50px; font-size: 1.2em; cursor: pointer; margin-top: 20px;
            text-decoration: none; display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if ($transaction && $transaction->payment_status === 'success'): ?>
            <h1 class="success">Pembayaran Berhasil!</h1>
            <p>Terima kasih! Sekarang saatnya beraksi di depan kamera.</p>
            <p>Order ID: <strong><?= htmlspecialchars($transaction->order_id) ?></strong></p>
            <a href="<?= URLROOT; ?>/photo/selectFrame/<?= $transaction->id ?>" class="action-button">Mulai Sesi Foto!</a>
        <?php elseif ($transaction && $transaction->payment_status === 'pending'): ?>
            <h1 class="pending">Pembayaran Tertunda</h1>
            <p>Kami masih menunggu konfirmasi pembayaran Anda.</p>
            <p>Silakan selesaikan pembayaran atau tunggu beberapa saat.</p>
        <?php else: ?>
            <h1 class="failure">Pembayaran Gagal</h1>
            <p>Maaf, terjadi masalah dengan pembayaran Anda.</p>
            <a href="<?= URLROOT; ?>/packages" class="action-button">Coba Pilih Paket Lain</a>
        <?php endif; ?>
    </div>
</body>
</html>