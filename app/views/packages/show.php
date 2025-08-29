<?php 
    \App\Core\Session::start();
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
    <title>Pilih Paket Kecerianmu!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        html, body { height: 100%; margin: 0; overflow: hidden; }
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #FDF4F5;
            text-align: center;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1vw;
            box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out;
        }
        body.fade-out { opacity: 0; }
        
        .main-container { 
            width: 100%; 
            max-width: 1100px;
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.3s ease-out;
        }
        .main-container.content-fade-out { opacity: 0; }
        @keyframes contentFadeIn { to { opacity: 1; } }
        
        .header-container { margin-bottom: 2vh; }
        .mascot { width: clamp(80px, 12vh, 120px); height: auto; animation: bounce 2s infinite ease-in-out; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .header-container h1 { font-family: 'Fredoka One', cursive; font-size: clamp(2rem, 5.5vmin, 3.5rem); margin: 1vh 0; text-shadow: 2px 2px #FFD166; }
        .header-container p { font-size: clamp(0.8rem, 2vmin, 1rem); max-width: 600px; margin: 0 auto; }
        .packages-container { display: flex; justify-content: center; gap: 2vw; flex-wrap: wrap; }
        .package-card { background-color: #FFFFFF; border: 2px solid #333; border-radius: 15px; padding: 1.5vw; width: 280px; box-shadow: 6px 6px 0px #333; transition: all 0.3s ease; text-align: left; }
        .package-card:hover { transform: translate(-3px, -3px); box-shadow: 9px 9px 0px #333; }
        .package-card h2 { font-family: 'Fredoka One', cursive; font-size: clamp(1.5rem, 3.5vmin, 1.8rem); color: #6C63FF; margin: 0 0 1vh; }
        .package-card .price { font-family: 'Fredoka One', cursive; font-size: clamp(2rem, 4.5vmin, 2.5rem); color: #FF6584; margin-bottom: 2vh; }
        .package-card p { font-size: clamp(0.75rem, 1.8vmin, 0.9rem); margin-bottom: 2vh; min-height: 3vh; }
        .package-card ul { list-style: none; padding: 0; margin: 0 0 2vh; }
        .package-card ul li { margin-bottom: 1vh; font-weight: 600; font-size: clamp(0.75rem, 1.8vmin, 0.9rem); }
        .package-card ul li::before { content: '⭐'; margin-right: 10px; }
        .select-button { display: inline-block; font-family: 'Fredoka One', cursive; font-size: clamp(1rem, 2.2vmin, 1.2rem); background-color: #FFD166; color: #333; padding: 1.5vh 3vw; border: 2px solid #333; border-radius: 50px; text-decoration: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 3px 3px 0 #333; }
        .select-button:hover { transform: translate(-2px, -2px); box-shadow: 5px 5px 0 #333; }
        .select-button:active { transform: translate(3px, 3px); box-shadow: none; }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none; /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }
        .popup-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            text-align: center;
            max-width: 400px;
            width: 90%;
            font-family: 'Poppins', sans-serif;
        }
        .popup-content p {
            font-size: 1.1rem;
            color: #333;
            margin: 0 0 20px 0;
        }
        #close-popup-btn {
            font-family: 'Fredoka One', cursive;
            font-size: 1rem;
            background-color: #FFD166;
            color: #333;
            padding: 10px 25px;
            border: 2px solid #333;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.2s ease;
        }
        #close-popup-btn:hover {
            background-color: #ffc84a;
        }
    </style>
</head>
<body>
    <div class="main-container">

        <?php if (\App\Core\Session::has('flash_message')): ?>
        <div id="flash-popup" class="popup-overlay">
            <div class="popup-content">
                <p><?= \App\Core\Session::get('flash_message'); ?></p>
                <button id="close-popup-btn">Tutup</button>
            </div>
        </div>
        <?php \App\Core\Session::unset('flash_message'); ?>
        <?php endif; ?>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const popup = document.getElementById('flash-popup');
            const closeBtn = document.getElementById('close-popup-btn');

            if (popup) {
                // Show the popup
                popup.style.display = 'flex';

                // Close button functionality
                closeBtn.addEventListener('click', () => {
                    popup.style.display = 'none';
                });

                // Auto-hide after 5 seconds
                setTimeout(() => {
                    if (popup.style.display !== 'none') {
                        popup.style.display = 'none';
                    }
                }, 5000);
            }
        });
        </script>

        <div class="header-container">
            <img src="https://em-content.zobj.net/source/microsoft-teams/363/camera_1f4f7.png" alt="Cute Camera Mascot" class="mascot">
            <h1>Pilih Paket Kecerianmu!</h1>
            <p>Setiap foto adalah ledakan tawa yang tak terlupakan. Yuk, pilih paket yang paling seru buat kamu!</p>
        </div>
        <div class="packages-container">
            <?php foreach ($packages as $package): ?>
                <div class="package-card">
                    <h2><?= htmlspecialchars($package->name); ?></h2>
                    <div class="price">Rp <?= number_format($package->price, 0, ',', '.'); ?></div>
                    <p><?= htmlspecialchars($package->description); ?></p>
                    <ul>
                        <li><b><?= $package->photo_limit; ?>x</b> Ambil Foto</li>
                        <li><b><?= $package->retake_limit; ?>x</b> Kesempatan Ulang</li>
                    </ul>
                    <a href="<?= URLROOT; ?>/payment/process/<?= $package->id ?>" class="select-button">Pilih Paket Ini!</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const links = document.querySelectorAll('a.select-button');
            const contentWrapper = document.querySelector('.main-container');

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