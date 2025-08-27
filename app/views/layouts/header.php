<?php
// LAPIS 1: MENCEGAH BROWSER MENYIMPAN CACHE
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title'] ?? SITENAME; ?></title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/public/css/style.css"> 
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script type="text/javascript">
        (function(window, location) {
            // 1. Tambahkan entri baru ke riwayat browser saat halaman dimuat.
            history.pushState(null, null, location.href);

            // 2. Dengarkan event 'popstate' yang terpicu saat tombol 'kembali' ditekan.
            window.addEventListener('popstate', function() {
                // Tampilkan popup peringatan kepada pengguna.
                alert("Anda tidak bisa kembali ke halaman sebelumnya. Silakan lanjutkan proses.");

                // 3. Dorong kembali entri riwayat yang sama untuk membatalkan aksi 'kembali'.
                history.pushState(null, null, location.href);
            });
        }(window, location));
    </script>
</head>
<body>
    <div class="container">