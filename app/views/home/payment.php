<?php require APPROOT . '/views/layouts/header.php'; ?>

<main class="main-content payment-page">
    <div class="content-box">
        <h1>Selesaikan Pembayaran</h1>
        <p>Silakan scan kode QRIS di bawah ini untuk memulai sesi photobooth Anda.</p>
        
        <div class="qris-container">
            <?php if (!empty($data['event']->qris_image_path)): ?>
                <img src="<?= URLROOT . '/' . htmlspecialchars($data['event']->qris_image_path); ?>" alt="QRIS Code">
            <?php else: ?>
                <p class="error">Gambar QRIS tidak tersedia untuk acara ini.</p>
            <?php endif; ?>
        </div>
        
        <p class="instruction">Setelah pembayaran berhasil, klik tombol di bawah ini.</p>
        
        <a href="<?= URLROOT; ?>/home/confirmPayment" class="btn btn-success btn-large">Saya Sudah Bayar & Lanjutkan</a>
        <a href="<?= URLROOT; ?>" class="link-back">Kembali ke Awal</a>
    </div>
</main>

<?php require APPROOT . '/views/layouts/footer.php'; ?>

<!-- This view is no longer used in the new simulated payment flow. -->