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
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Pilih Paket Kecerianmu!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap"
        rel="stylesheet">
    <!-- Midtrans Snap.js -->
    <?php
    $paymentConfig = require '../config/payment.php';
    // Use production or sandbox Snap.js URL based on configuration
    $snapUrl = $paymentConfig['is_production']
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
    ?>
    <script src="<?= $snapUrl ?>" data-client-key="<?= $paymentConfig['client_key'] ?>"></script>

    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

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

        body.fade-out {
            opacity: 0;
        }

        .main-container {
            width: 100%;
            max-width: 1100px;
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.3s ease-out;
        }

        .main-container.content-fade-out {
            opacity: 0;
        }

        @keyframes contentFadeIn {
            to {
                opacity: 1;
            }
        }

        .header-container {
            margin-bottom: 2vh;
        }

        .mascot {
            width: clamp(80px, 12vh, 120px);
            height: auto;
            animation: bounce 2s infinite ease-in-out;
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .header-container h1 {
            font-family: 'Fredoka One', cursive;
            font-size: clamp(2rem, 5.5vmin, 3.5rem);
            margin: 1vh 0;
            text-shadow: 2px 2px #FFD166;
        }

        .header-container p {
            font-size: clamp(0.8rem, 2vmin, 1rem);
            max-width: 600px;
            margin: 0 auto;
        }

        .carousel-wrapper {
            position: relative;
            width: 100%;
            max-width: 82vw;
            /* Lebar tiga kartu + gap */
            margin: 0 auto;
        }

        .packages-container {
            display: flex;
            overflow-x: auto;
            scroll-behavior: smooth;
            -ms-overflow-style: none;
            /* IE and Edge */
            scrollbar-width: none;
            /* Firefox */
            padding: 20px;
            clip-path: inset(0 0 0 0);
        }

        .packages-container::-webkit-scrollbar {
            display: none;
            /* Safari and Chrome */
        }

        .package-card {
            flex: 0 0 280px;
            margin-right: 2vw;
            background-color: #FFFFFF;
            border: 2px solid #333;
            border-radius: 15px;
            padding: 1.5vw;
            box-shadow: 6px 6px 0px #333;
            transition: all 0.3s ease;
            text-align: left;
        }

        .package-card:last-child {
            margin-right: 0;
        }

        .package-card:hover {
            box-shadow: 9px 9px 0px #FFD166;
        }

        .package-card h2 {
            font-family: 'Fredoka One', cursive;
            font-size: clamp(1.5rem, 3.5vmin, 1.8rem);
            color: #6C63FF;
            margin: 0 0 1vh;
        }

        .package-card .price {
            font-family: 'Fredoka One', cursive;
            font-size: clamp(2rem, 4.5vmin, 2.5rem);
            color: #FF6584;
            margin-bottom: 2vh;
        }

        .package-card p {
            font-size: clamp(0.75rem, 1.8vmin, 0.9rem);
            margin-bottom: 2vh;
            min-height: 3vh;
        }

        .package-card ul {
            list-style: none;
            padding: 0;
            margin: 0 0 2vh;
        }

        .package-card ul li {
            margin-bottom: 1vh;
            font-weight: 600;
            font-size: clamp(0.75rem, 1.8vmin, 0.9rem);
        }

        .package-card ul li::before {
            content: '⭐';
            margin-right: 10px;
        }

        .select-button {
            display: inline-block;
            font-family: 'Fredoka One', cursive;
            font-size: clamp(1rem, 2.2vmin, 1.2rem);
            background-color: #FFD166;
            color: #333;
            padding: 1.5vh 3vw;
            border: 2px solid #333;
            border-radius: 50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 3px 3px 0 #333;
            width: auto;
        }

        .select-button:hover {
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0 #333;
        }

        .select-button:active {
            transform: translate(3px, 3px);
            box-shadow: none;
        }

        .bypass-button {
            background-color: #6C63FF;
            color: white;
            margin-left: 10px;
            font-size: clamp(0.85rem, 2vmin, 1rem);
            padding: 1.2vh 2.5vw;
        }

        .bypass-button:hover {
            background-color: #5a52d5;
            transform: translate(-2px, -2px);
            box-shadow: 5px 5px 0 #333;
        }

        .carousel-buttons {
            text-align: center;
            margin-top: 20px;
        }

        .nav-btn {
            background-color: #FFD166;
            border: 2px solid #333;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 30px;
            font-family: 'Fredoka One', cursive;
            color: #333;
            cursor: pointer;
            box-shadow: 3px 3px 0 #333;
            transition: all 0.2s ease;
            margin: 0 10px;
        }

        .nav-btn:hover {
            transform: scale(1.05);
            box-shadow: 5px 5px 0 #333;
        }

        .nav-btn:active {
            transform: scale(1.05);
            box-shadow: none;
        }

        /* Popup Styles */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            display: none;
            /* Hidden by default */
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .popup-content {
            background-color: #fff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
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

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: none;
            flex-direction: column;
            /* Stack spinner and text vertically */
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .loading-spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid #FFD166;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .loading-text {
            color: white;
            font-family: 'Fredoka One', cursive;
            font-size: 1.2rem;
            margin-top: 20px;
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

        <!-- Loading overlay -->
        <div id="loading-overlay" class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text">Memuat pembayaran...</div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const popup = document.getElementById('flash-popup');
                const closeBtn = document.getElementById('close-popup-btn');

                if (popup) {
                    popup.style.display = 'flex';
                    closeBtn.addEventListener('click', () => {
                        popup.style.display = 'none';
                    });
                    setTimeout(() => {
                        if (popup.style.display !== 'none') {
                            popup.style.display = 'none';
                        }
                    }, 5000);
                }
            });
        </script>

        <div class="header-container">
            <img src="https://em-content.zobj.net/source/microsoft-teams/363/camera_1f4f7.png" alt="Cute Camera Mascot"
                class="mascot">
            <h1>Pilih Paket Kecerianmu!</h1>
            <p>Setiap foto adalah ledakan tawa yang tak terlupakan. Yuk, pilih paket yang paling seru buat kamu!</p>
        </div>

        <div class="carousel-wrapper">
            <div class="packages-container">
                <?php foreach ($packages as $package): ?>
                    <div class="package-card">
                        <h2><?= htmlspecialchars($package->name); ?></h2>
                        <div class="price">Rp <?= number_format($package->price, 0, ',', '.'); ?></div>
                        <p><?= htmlspecialchars($package->description); ?></p>
                        <ul>
                            <li><b><?= $package->photo_limit ?? 2; ?> Cetak</b> Photostrip</li>
                            <li><b><?= $package->frame_limit ?? 2; ?> Pilihan</b> Desain Frame</li>
                            <li><b><?= floor(($package->session_duration ?? 300) / 60); ?> Menit</b> Durasi Sesi Foto</li>
                            <li><b>Simpan hingga <?= $package->max_save_photos ?? 20; ?></b> foto terbaik</li>
                        </ul>
                        <?php if (defined('ENABLE_PAYMENT_BYPASS') && ENABLE_PAYMENT_BYPASS): ?>
                            <button onclick="bypassPayment(<?= $package->id ?>, '<?= htmlspecialchars($package->name) ?>')"
                                class="select-button">Pilih Paket Ini!</button>
                        <?php else: ?>
                            <button
                                onclick="payWithSnap(<?= $package->id ?>, '<?= htmlspecialchars($package->name) ?>', <?= $package->price ?>)"
                                class="select-button">Pilih Paket Ini!</button>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="carousel-buttons">
            <button id="prev-btn" class="nav-btn">&lt;</button>
            <button id="next-btn" class="nav-btn">&gt;</button>
        </div>

    </div>

    <script>
        // Prevent duplicate requests
        let isPaymentInProgress = false;

        // Clear browser cache on page load
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                // Page is restored from cache, reload it
                window.location.reload(true);
            }
        });

        // Bypass payment function (for testing)
        function bypassPayment(packageId, packageName) {
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.style.display = 'flex';

            fetch('<?= URLROOT ?>/payment/bypass-payment/' + packageId, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => response.json())
                .then(data => {
                    loadingOverlay.style.display = 'none';

                    if (data.success) {
                        console.log('Payment bypassed:', data);
                        // Redirect to payment finish page
                        window.location.replace('<?= URLROOT ?>/payment/finish/' + data.transaction_id);
                    } else {
                        alert('Gagal bypass payment: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    loadingOverlay.style.display = 'none';
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat bypass payment');
                });
        }

        // Midtrans Snap payment function
        function payWithSnap(packageId, packageName, packagePrice) {
            // Prevent duplicate payment requests
            if (isPaymentInProgress) {
                console.log('Payment already in progress, ignoring request');
                return;
            }
            isPaymentInProgress = true;
            const loadingOverlay = document.getElementById('loading-overlay');
            loadingOverlay.style.display = 'flex';

            // Get snap token from server
            fetch('<?= URLROOT ?>/payment/get-snap-token/' + packageId, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
                .then(response => {
                    console.log('Raw response status:', response.status);
                    console.log('Raw response headers:', response.headers);
                    return response.text().then(text => {
                        console.log('Raw response text:', text);
                        try {
                            return JSON.parse(text);
                        } catch (e) {
                            console.error('Failed to parse JSON:', e);
                            console.error('Response text:', text);
                            throw new Error('Invalid JSON response: ' + text.substring(0, 100) + '...');
                        }
                    });
                })
                .then(data => {
                    loadingOverlay.style.display = 'none';

                    console.log('Payment response:', data);

                    if (data.success && data.snap_token) {
                        console.log('Snap token received:', data.snap_token);
                        console.log('Token length:', data.snap_token.length);
                        console.log('Token format check:', /^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/.test(data.snap_token));

                        // Use Midtrans Snap
                        try {
                            snap.pay(data.snap_token, {
                                onSuccess: function (result) {
                                    console.log('Payment success:', result);
                                    // Redirect to frame selection with transaction ID
                                    // We'll need to get the transaction ID from the payment result
                                    fetch('<?= URLROOT ?>/payment/get-transaction-by-order/' + result.order_id)
                                        .then(response => response.json())
                                        .then(transactionData => {
                                            if (transactionData.success) {
                                                window.location.replace('<?= URLROOT ?>/payment/finish/' + transactionData.transaction_id);
                                            } else {
                                                window.location.replace('<?= URLROOT ?>/packages');
                                            }
                                        })
                                        .catch(() => {
                                            window.location.replace('<?= URLROOT ?>/packages');
                                        });
                                },
                                onPending: function (result) {
                                    console.log('Payment pending:', result);
                                    isPaymentInProgress = false;
                                    alert('Pembayaran sedang diproses. Silakan selesaikan pembayaran Anda.');
                                },
                                onError: function (result) {
                                    console.log('Payment error:', result);
                                    isPaymentInProgress = false;
                                    alert('Terjadi kesalahan saat pembayaran. Silakan coba lagi.');
                                },
                                onClose: function () {
                                    console.log('Payment popup closed');
                                    isPaymentInProgress = false;
                                }
                            });
                        } catch (snapError) {
                            console.error('Snap.pay error:', snapError);
                            isPaymentInProgress = false;
                            alert('Error initializing payment: ' + snapError.message);
                        }
                    } else {
                        isPaymentInProgress = false;
                        alert('Gagal memuat pembayaran: ' + (data.error || 'Terjadi kesalahan'));
                    }
                })
                .catch(error => {
                    loadingOverlay.style.display = 'none';
                    isPaymentInProgress = false;
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat memuat pembayaran');
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Note: Payment buttons now use onclick payWithSnap() directly
            // No need for fade animation on payment buttons since they open popup

            // Carousel script
            const container = document.querySelector('.packages-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');

            if (container) {
                const scrollAmount = container.clientWidth;

                nextBtn.addEventListener('click', () => {
                    const maxScroll = container.scrollWidth - container.clientWidth;
                    if (container.scrollLeft >= maxScroll - 1) {
                        container.scrollTo({ left: 0, behavior: 'smooth' });
                    } else {
                        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                    }
                });

                prevBtn.addEventListener('click', () => {
                    if (container.scrollLeft < 1) {
                        container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
                    } else {
                        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                    }
                });
            }
        });
    </script>
</body>

</html>