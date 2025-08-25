<?php require APPROOT . '/views/layouts/header.php'; ?>

<main class="main-content finalize-page">
    <div class="content-box">
        <h1>Foto Anda Sudah Siap!</h1>
        <p>Pilih salah satu opsi di bawah untuk menyimpan kenangan Anda.</p>

        <div class="photo-display-container" id="print-area">
            <img src="<?= $data['imagePath']; ?>" alt="Final Photobooth Photo" class="final-photo">
        </div>

        <div class="output-actions">
            <a href="<?= $data['imagePath']; ?>" download="photobooth-foto-<?= date('Ymd_His'); ?>.jpg" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                Unduh
            </a>

            <button onclick="window.print()" class="btn btn-secondary">
                 <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
                Cetak
            </button>
        </div>
        
        <hr style="margin: 2rem 0;">

        <a href="<?= URLROOT; ?>" class="btn">Mulai Sesi Baru</a>
    </div>
</main>

<?php require APPROOT . '/views/layouts/footer.php'; ?>