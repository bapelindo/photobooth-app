<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<div class="card">
    <h2>Selamat Datang, <?= $_SESSION['admin_username']; ?>!</h2>
    <p>Gunakan menu di samping untuk mengelola acara, aset, dan melihat galeri foto.</p>
</div>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>