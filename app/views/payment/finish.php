<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">

    <style>
        html, body { height: 100%; margin: 0; overflow: hidden; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FDF4F5;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out; /* Untuk fade-out akhir */
        }
        body.fade-out { opacity: 0; }
        
        .status-wrapper { 
            display: flex; align-items: center; gap: 50px; 
            width: 100%; max-width: 800px; 
            transition: opacity 0.3s ease-out; /* Transisi untuk konten */
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
        }
        .status-wrapper.content-fade-out { opacity: 0; }
        @keyframes contentFadeIn { to { opacity: 1; } }

        /* ... sisa CSS tidak berubah ... */
        .mascot-container { flex-shrink: 0; animation: bounce 2s infinite ease-in-out; }
        .mascot { width: 200px; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-15px); } }
        .speech-bubble { position: relative; background: #FFFFFF; border-radius: 25px; padding: 40px; text-align: center; border: 3px solid #333; box-shadow: 8px 8px 0px #333; width: 100%; }
        .speech-bubble::before { content: ''; position: absolute; left: 0; top: 50%; width: 0; height: 0; border: 25px solid transparent; border-right-color: #333; border-left: 0; margin-top: -25px; margin-left: -28px; }
        h1 { font-family: 'Fredoka One', cursive; font-size: 2.8rem; margin: 0 0 15px; }
        .success h1 { color: #28a745; }
        .pending h1 { color: #ffc107; }
        .failure h1 { color: #dc3545; }
        p { font-size: 1.1rem; color: #555; line-height: 1.6; margin: 0 0 30px; }
        .action-button { display: inline-block; font-family: 'Fredoka One', cursive; font-size: 1.2rem; color: #333; padding: 18px 45px; border: 3px solid #333; border-radius: 50px; text-decoration: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 4px 4px 0 #333; }
        .action-button:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 #333; }
        .action-button:active { transform: translate(4px, 4px); box-shadow: none; }
        .btn-success { background-color: #83e2a2; }
        .btn-primary { background-color: #a1c4fd; }
        .btn-failure { background-color: #ff8a8a; }
    </style>
</head>
<body>
    <div class="status-wrapper">
        <div class="mascot-container">
            <img src="https://em-content.zobj.net/source/microsoft-teams/363/camera_1f4f7.png" alt="Cute Camera Mascot" class="mascot">
        </div>
        <?php if ($transaction && $transaction->payment_status === 'success'): ?>
            <div class="speech-bubble success">
                <h1>Hore, Berhasil!</h1>
                <p>Pembayaranmu sudah kami terima. Aku sudah tidak sabar untuk memotretmu. Yuk, kita mulai!</p>
                <a href="<?= URLROOT; ?>/photo/selectFrame/<?= $transaction->id ?>" class="action-button btn-success">Mulai Foto! 📸</a>
            </div>
        <?php elseif ($transaction && $transaction->payment_status === 'pending'): ?>
            <div class="speech-bubble pending">
                <h1>Hmm, Sebentar..</h1>
                <p>Aku masih menunggu sinyal pembayaran darimu. Pastikan kamu sudah menyelesaikannya, ya!</p>
                <a href="<?= URLROOT; ?>/packages" class="action-button btn-primary">Pilih Paket Lain</a>
            </div>
        <?php else: ?>
            <div class="speech-bubble failure">
                <h1>Oh, Tidak!</h1>
                <p>Sepertinya ada gangguan sinyal pembayaran. Tapi jangan sedih, kamu bisa coba lagi!</p>
                <a href="<?= URLROOT; ?>/packages" class="action-button btn-failure">Coba Lagi</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a.action-button');
            const contentWrapper = document.querySelector('.status-wrapper');

            links.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const destination = this.href;
                    if (contentWrapper) {
                        contentWrapper.classList.add('content-fade-out');
                    }
                    setTimeout(() => {
                        document.body.classList.add('fade-out');
                    }, 300);
                    setTimeout(() => {
                        window.location.href = destination;
                    }, 700);
                });
            });
        });
    </script>
</body>
</html>