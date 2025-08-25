<?php require APPROOT . '/views/layouts/header.php'; ?>

<main class="main-content thankyou-page">
    <div class="content-box">
        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="#28a745" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
        <h1>Terima Kasih!</h1>
        <p>Foto Anda telah berhasil diproses. Semoga Anda menikmati sisa acara!</p>
        <a href="<?= URLROOT; ?>" class="btn btn-primary">Mulai Sesi Baru</a>
    </div>
</main>

<?php require APPROOT . '/views/layouts/footer.php'; ?>