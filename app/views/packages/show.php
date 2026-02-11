<?php
\App\Core\Session::start();
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
    <title>Photobooth Airways</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">

    <?php
    $paymentConfig = require '../config/payment.php';
    $snapUrl = $paymentConfig['is_production']
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
    ?>
    <script src="<?= $snapUrl ?>" data-client-key="<?= $paymentConfig['client_key'] ?>"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100vh;
            overflow: hidden;
            font-family: 'Roboto Condensed', sans-serif;
        }

        /* ========== SOFT BLUE SKY BACKGROUND ========== */
        /* Gentle, airy blue gradient to harmonize with the cards */
        body {
            background: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            position: relative;
        }

        /* Animated Clouds - Kept white and fluffy */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 100px;
            animation: float linear infinite;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 100px;
        }

        /* Cloud 1 */
        .cloud1 {
            width: 120px;
            height: 50px;
            top: 10%;
            left: -150px;
            animation-duration: 45s;
        }

        .cloud1::before {
            width: 60px;
            height: 50px;
            top: -25px;
            left: 20px;
        }

        .cloud1::after {
            width: 70px;
            height: 40px;
            top: -15px;
            right: 20px;
        }

        /* Cloud 2 */
        .cloud2 {
            width: 100px;
            height: 40px;
            top: 25%;
            left: -120px;
            animation-duration: 55s;
            animation-delay: 5s;
        }

        .cloud2::before {
            width: 50px;
            height: 40px;
            top: -20px;
            left: 15px;
        }

        .cloud2::after {
            width: 60px;
            height: 35px;
            top: -12px;
            right: 15px;
        }

        /* Cloud 3 */
        .cloud3 {
            width: 140px;
            height: 55px;
            top: 50%;
            left: -160px;
            animation-duration: 50s;
            animation-delay: 10s;
        }

        .cloud3::before {
            width: 70px;
            height: 55px;
            top: -28px;
            left: 25px;
        }

        .cloud3::after {
            width: 80px;
            height: 45px;
            top: -18px;
            right: 25px;
        }

        /* Cloud 4 */
        .cloud4 {
            width: 110px;
            height: 45px;
            top: 70%;
            left: -130px;
            animation-duration: 60s;
            animation-delay: 15s;
        }

        .cloud4::before {
            width: 55px;
            height: 45px;
            top: -22px;
            left: 18px;
        }

        .cloud4::after {
            width: 65px;
            height: 38px;
            top: -14px;
            right: 18px;
        }

        /* Cloud 5 - Bottom */
        .cloud5 {
            width: 130px;
            height: 52px;
            top: 85%;
            left: -140px;
            animation-duration: 48s;
            animation-delay: 20s;
        }

        .cloud5::before {
            width: 65px;
            height: 52px;
            top: -26px;
            left: 22px;
        }

        .cloud5::after {
            width: 75px;
            height: 42px;
            top: -16px;
            right: 22px;
        }

        @keyframes float {
            0% {
                left: -200px;
            }

            100% {
                left: 110%;
            }
        }

        /* Flying Plane Container and Trail */
        .plane-container {
            position: fixed;
            top: 15%;
            left: -300px;
            display: flex;
            align-items: center;
            z-index: 1;
            animation: flyPlane 45s linear infinite;
            pointer-events: none;
        }

        .plane-trail {
            width: 180px;
            height: 2px;
            background: repeating-linear-gradient(90deg,
                    rgba(255, 255, 255, 0) 0,
                    rgba(255, 255, 255, 0) 4px,
                    rgba(255, 255, 255, 0.6) 4px,
                    rgba(255, 255, 255, 0.6) 10px);
            margin-right: -10px;
            border-radius: 2px;
            opacity: 0.8;
            filter: blur(0.5px);
        }

        .plane {
            width: 70px;
            height: 70px;
            filter: drop-shadow(2px 4px 6px rgba(0, 0, 0, 0.1));
            transform: rotate(90deg);
        }

        @keyframes flyPlane {
            0% {
                left: -300px;
                top: 15%;
                transform: rotate(2deg);
            }

            25% {
                top: 18%;
                transform: rotate(1deg);
            }

            50% {
                top: 14%;
                transform: rotate(-1deg);
            }

            75% {
                top: 17%;
                transform: rotate(0deg);
            }

            100% {
                left: 110%;
                top: 15%;
                transform: rotate(1deg);
            }
        }

        .main-container {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 3vh 2vw;
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* ========== CAROUSEL SECTION - MAXIMIZED SPACE ========== */
        .carousel-section {
            width: 100%;
            max-width: 1400px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 3vh;
        }

        .passes-container {
            display: flex;
            gap: 25px;
            overflow-x: auto;
            scroll-behavior: smooth;
            padding: 15px 20px 20px;
            -ms-overflow-style: none;
            scrollbar-width: none;
            max-width: 95vw;
            /* Allow perforation cutouts to be visible */
            padding-left: 30px;
            padding-right: 30px;
            margin: 0 -10px;
        }

        .passes-container::-webkit-scrollbar {
            display: none;
        }

        /* ========== BOARDING PASS ========== */
        .boarding-pass {
            flex: 0 0 300px;
            height: fit-content;
            background: #FFFFFF;
            border-radius: 12px;
            /* Softer shadow for lighter background */
            box-shadow:
                0 15px 35px rgba(50, 50, 93, 0.1),
                0 5px 15px rgba(0, 0, 0, 0.07);
            position: relative;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: visible;

            /* Transparent Cutouts using Mask */
            -webkit-mask-image: radial-gradient(circle at left 78px, transparent 13px, black 14px),
                radial-gradient(circle at right 78px, transparent 13px, black 14px);
            -webkit-mask-composite: source-in, source-in;
            mask-image: radial-gradient(circle at left 78px, transparent 13px, black 14px),
                radial-gradient(circle at right 78px, transparent 13px, black 14px);
            mask-composite: intersect;
        }

        .boarding-pass:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow:
                0 30px 60px rgba(50, 50, 93, 0.12),
                0 18px 36px rgba(0, 0, 0, 0.08);
        }

        .pass-stub {
            background: linear-gradient(135deg, #00BFFF 0%, #007FFF 100%);
            padding: 12px 16px;
            color: white;
            border-radius: 10px 10px 0 0;
            position: relative;
            margin-bottom: 0;
        }

        .pass-stub::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #A3E8FD, #C6C7DA, #A3E8FD);
        }

        .stub-airline {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.6rem;
            font-weight: 700;
            letter-spacing: 2px;
            margin-bottom: 4px;
            opacity: 0.95;
            color: #E3E0EC;
        }

        .stub-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            font-weight: 900;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 2px;
            color: #FFFFFF;
        }

        .stub-subtitle {
            font-size: 0.65rem;
            opacity: 0.95;
            font-weight: 600;
            color: #A3E8FD;
        }

        .perforation {
            height: 2px;
            background: repeating-linear-gradient(90deg,
                    transparent 0px,
                    transparent 4px,
                    #e0e0e0 4px,
                    #e0e0e0 8px);
            position: relative;
            z-index: 5;
        }

        /* Create cutout circles on card edges using boarding-pass pseudo-elements */
        .boarding-pass::before,
        .boarding-pass::after {
            content: '';
            position: absolute;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            z-index: 10;
            top: 78px;
            transform: translateY(-50%);
            pointer-events: none;
            /* Inner shadow to give depth to the cutout */
            box-shadow: inset 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .boarding-pass::before {
            left: -13px;
            border-right: 1px solid rgba(0, 0, 0, 0.03);
        }

        .boarding-pass::after {
            right: -13px;
            border-left: 1px solid rgba(0, 0, 0, 0.03);
        }

        .pass-main {
            padding: 16px;
            background: #FFFFFF;
        }

        .route-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 12px;
            border-bottom: 2px dashed #E3E0EC;
        }

        .airport {
            text-align: center;
        }

        .airport-code {
            font-family: 'Orbitron', sans-serif;
            font-size: 2rem;
            font-weight: 900;
            color: #007FFF;
            line-height: 1;
            margin-bottom: 2px;
            letter-spacing: -1px;
        }

        .airport-city {
            font-size: 0.6rem;
            color: #78909C;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }

        .flight-path {
            flex: 1;
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0 10px;
        }

        .flight-line {
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, #A3E8FD, #26C6F8, #A3E8FD);
            position: relative;
        }

        .flight-number {
            background: linear-gradient(135deg, #26C6F8 0%, #00BDFE 100%);
            color: #ffffff;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 3px;
            margin-bottom: 6px;
            letter-spacing: 1px;
            box-shadow: 0 2px 8px rgba(38, 198, 248, 0.2);
        }

        .passenger-section {
            background: linear-gradient(135deg, #F5F7FA 0%, #E8ECF1 100%);
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 12px;
            border-left: 3px solid #00BFFF;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .info-row:last-child {
            margin-bottom: 0;
        }

        .info-item {
            flex: 1;
        }

        .info-label {
            font-size: 0.55rem;
            color: #90A4AE;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            font-weight: 700;
        }

        .info-value {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            color: #007FFF;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .info-value.large {
            font-size: 0.9rem;
            color: #00BFFF;
        }

        .details-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .detail-box {
            text-align: center;
            padding: 8px 4px;
            background: linear-gradient(135deg, #FFFFFF 0%, #F5F7FA 100%);
            border-radius: 6px;
            border: 1px solid #E3E0EC;
        }

        .detail-icon {
            margin-bottom: 4px;
        }

        .detail-label {
            font-size: 0.55rem;
            color: #90A4AE;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            font-weight: 700;
        }

        .detail-value {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.75rem;
            color: #007FFF;
            font-weight: 700;
        }

        .fare-section {
            background: linear-gradient(135deg, #E1F5FE 0%, #B3E5FC 100%);
            color: #01579B;
            padding: 12px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 12px;
            border: 1px solid #B3E5FC;
        }

        .fare-label {
            font-size: 0.6rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2px;
            opacity: 0.8;
            font-weight: 700;
            color: #0277BD;
        }

        .fare-amount {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 0.5px;
            color: #01579B;
        }

        /* PEACH BUTTON - The "Sunset" Accent */
        .book-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #FB9F8B 0%, #F58C75 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(251, 159, 139, 0.4);
        }

        .book-button:hover {
            background: linear-gradient(135deg, #F58C75 0%, #E67E22 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 159, 139, 0.5);
        }

        .book-button:active {
            transform: translateY(0);
        }

        .pass-footer {
            background: linear-gradient(135deg, #F8FAFC 0%, #F1F5F9 100%);
            padding: 12px;
            border-top: 2px dashed #E2E8F0;
            text-align: center;
            border-radius: 0 0 10px 10px;
        }

        .barcode-wrapper {
            margin-bottom: 6px;
        }

        .barcode-svg {
            height: 40px;
            margin: 0 auto;
        }

        .booking-ref {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.65rem;
            color: #1B365D;
            font-weight: 700;
            letter-spacing: 2px;
            margin-top: 4px;
        }

        .boarding-note {
            font-size: 0.55rem;
            color: #007FFF;
            margin-top: 4px;
            font-style: italic;
        }

        /* ========== SIMPLE NAVIGATION ========== */
        .navigation-section {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 20px;
        }

        .nav-button {
            width: 55px;
            height: 55px;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.8);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .nav-button:hover {
            background: rgba(255, 255, 255, 0.9);
            transform: scale(1.12);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .nav-button svg {
            width: 26px;
            height: 26px;
            color: #007FFF;
        }

        .nav-indicator {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-dot {
            width: 10px;
            height: 10px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .nav-dot.active {
            width: 30px;
            border-radius: 5px;
            background: #FFFFFF;
        }

        /* ========== CLEAN POPUP ========== */
        .popup-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .popup-content {
            background: #ffffff;
            padding: 45px;
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 480px;
            width: 90%;
            animation: popIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(30px);
            }

            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .popup-content p {
            font-size: 1.25rem;
            color: #2c3e50;
            margin: 0 0 30px 0;
            line-height: 1.6;
            font-weight: 500;
        }

        #close-popup-btn {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 1.05rem;
            font-weight: 700;
            background: linear-gradient(135deg, #00BFFF, #007FFF);
            color: white;
            padding: 15px 45px;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 6px 20px rgba(0, 127, 255, 0.3);
        }

        #close-popup-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 127, 255, 0.4);
        }


        /* ========== RESPONSIVE ========== */
        @media (max-width: 768px) {
            .boarding-pass {
                flex: 0 0 280px;
            }

            .airport-code {
                font-size: 1.8rem;
            }

            .details-grid {
                grid-template-columns: repeat(3, 1fr);
                gap: 6px;
            }

            .nav-indicator {
                display: none;
            }

            .plane {
                width: 60px;
                height: 60px;
            }
        }

        @media (max-width: 480px) {
            .boarding-pass {
                flex: 0 0 260px;
            }

            .pass-stub {
                padding: 10px 14px;
            }

            .stub-title {
                font-size: 1rem;
            }

            .airport-code {
                font-size: 1.6rem;
            }

            .fare-amount {
                font-size: 1.3rem;
            }

            .pass-main {
                padding: 12px;
            }

            .details-grid {
                gap: 4px;
            }

            .detail-box {
                padding: 6px 3px;
            }
        }
    </style>
</head>

<body>
    <!-- Sky with Clouds -->
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
        <div class="cloud cloud4"></div>
        <div class="cloud cloud5"></div>
    </div>

    <!-- Flying Plane with Trail -->
    <div class="plane-container">
        <div class="plane-trail"></div>
        <svg class="plane" viewBox="0 0 24 24" fill="none">
            <path
                d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                fill="#FFFFFF" />
            <path
                d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                stroke="#4FC3F7" stroke-width="0.3" />
        </svg>
    </div>

    <div class="main-container">
        <!-- Flash Message Popup -->
        <?php if (\App\Core\Session::has('flash_message')): ?>
            <div id="flash-popup" class="popup-overlay">
                <div class="popup-content">
                    <p><?= \App\Core\Session::get('flash_message'); ?></p>
                    <button id="close-popup-btn">Close</button>
                </div>
            </div>
            <?php \App\Core\Session::unset('flash_message'); ?>
        <?php endif; ?>

        <!-- Carousel Section -->
        <div class="carousel-section">
            <div class="passes-container" id="passes-container">
                <?php
                $classTypes = ['ECONOMY', 'BUSINESS', 'FIRST CLASS'];
                $gates = ['A12', 'B07', 'C15', 'D03'];
                $seats = ['12A', '8F', '3B', '15C'];

                foreach ($packages as $index => $package):
                    $flightNumber = 'PBA' . str_pad($package->id * 100, 3, '0', STR_PAD_LEFT);
                    $bookingRef = strtoupper(substr(md5($package->id), 0, 6));
                    $classType = $classTypes[$index % 3];
                    $gate = $gates[$index % 4];
                    $seat = $seats[$index % 4];
                    ?>
                    <div class="boarding-pass">
                        <div class="pass-stub">
                            <div class="stub-airline">PHOTOBOOTH AIRWAYS</div>
                            <div class="stub-title"><?= htmlspecialchars($package->name); ?></div>
                            <div class="stub-subtitle"><?= $classType ?> CLASS</div>
                        </div>

                        <div class="perforation"></div>

                        <div class="pass-main">
                            <div class="route-section">
                                <div class="airport">
                                    <div class="airport-code">STU</div>
                                    <div class="airport-city">Studio</div>
                                </div>

                                <div class="flight-path">
                                    <div class="flight-number"><?= $flightNumber ?></div>
                                    <div class="flight-line">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                            style="position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);">
                                            <path
                                                d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                                                fill="#FB9F8B" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="airport">
                                    <div class="airport-code">MEM</div>
                                    <div class="airport-city">Memories</div>
                                </div>
                            </div>

                            <div class="passenger-section">
                                <div class="info-row">
                                    <div class="info-item">
                                        <div class="info-label">Passenger</div>
                                        <div class="info-value">PHOTOBOOTH GUEST</div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Booking Ref</div>
                                        <div class="info-value"><?= $bookingRef ?></div>
                                    </div>
                                </div>
                                <div class="info-row">
                                    <div class="info-item">
                                        <div class="info-label">Gate</div>
                                        <div class="info-value large"><?= $gate ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Seat</div>
                                        <div class="info-value large"><?= $seat ?></div>
                                    </div>
                                    <div class="info-item">
                                        <div class="info-label">Boarding Time</div>
                                        <div class="info-value">NOW</div>
                                    </div>
                                </div>
                            </div>

                            <div class="details-grid">
                                <div class="detail-box">
                                    <div class="detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <rect x="3" y="3" width="18" height="18" rx="2" stroke="#20B2AA"
                                                stroke-width="2" />
                                            <path d="M9 3V21M15 3V21M3 9H21M3 15H21" stroke="#20B2AA" stroke-width="2" />
                                        </svg>
                                    </div>
                                    <div class="detail-label">Prints</div>
                                    <div class="detail-value"><?= $package->photo_limit ?? 2; ?></div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <circle cx="12" cy="12" r="10" stroke="#FB9F8B" stroke-width="2" />
                                            <path d="M12 6V12L16 14" stroke="#FB9F8B" stroke-width="2"
                                                stroke-linecap="round" />
                                        </svg>
                                    </div>
                                    <div class="detail-label">Duration</div>
                                    <div class="detail-value"><?= floor(($package->session_duration ?? 300) / 60); ?> MIN
                                    </div>
                                </div>
                                <div class="detail-box">
                                    <div class="detail-icon">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                            <path
                                                d="M19 21H5C3.89543 21 3 20.1046 3 19V5C3 3.89543 3.89543 3 5 3H19C20.1046 3 21 3.89543 21 5V19C21 20.1046 20.1046 21 19 21Z"
                                                stroke="#1B365D" stroke-width="2" />
                                            <path d="M3 9H21M9 21V9" stroke="#1B365D" stroke-width="2" />
                                        </svg>
                                    </div>
                                    <div class="detail-label">Save</div>
                                    <div class="detail-value"><?= $package->max_save_photos ?? 20; ?></div>
                                </div>
                            </div>

                            <div class="fare-section">
                                <div class="fare-label">Total Fare</div>
                                <div class="fare-amount">Rp <?= number_format($package->price, 0, ',', '.'); ?></div>
                            </div>

                            <?php if (defined('ENABLE_PAYMENT_BYPASS') && ENABLE_PAYMENT_BYPASS): ?>
                                <button onclick="bypassPayment(<?= $package->id ?>, '<?= htmlspecialchars($package->name) ?>')"
                                    class="book-button">
                                    Confirm Booking
                                </button>
                            <?php else: ?>
                                <button
                                    onclick="payWithSnap(<?= $package->id ?>, '<?= htmlspecialchars($package->name) ?>', <?= $package->price ?>)"
                                    class="book-button">
                                    Confirm Booking
                                </button>
                            <?php endif; ?>
                        </div>

                        <div class="pass-footer">
                            <div class="barcode-wrapper">
                                <svg class="barcode-svg" viewBox="0 0 250 60">
                                    <rect x="5" y="5" width="4" height="50" fill="#000" />
                                    <rect x="13" y="5" width="2" height="50" fill="#000" />
                                    <rect x="19" y="5" width="5" height="50" fill="#000" />
                                    <rect x="28" y="5" width="2" height="50" fill="#000" />
                                    <rect x="34" y="5" width="4" height="50" fill="#000" />
                                    <rect x="42" y="5" width="2" height="50" fill="#000" />
                                    <rect x="48" y="5" width="6" height="50" fill="#000" />
                                    <rect x="58" y="5" width="2" height="50" fill="#000" />
                                    <rect x="64" y="5" width="4" height="50" fill="#000" />
                                    <rect x="72" y="5" width="2" height="50" fill="#000" />
                                    <rect x="78" y="5" width="5" height="50" fill="#000" />
                                    <rect x="87" y="5" width="2" height="50" fill="#000" />
                                    <rect x="93" y="5" width="4" height="50" fill="#000" />
                                    <rect x="101" y="5" width="2" height="50" fill="#000" />
                                    <rect x="107" y="5" width="6" height="50" fill="#000" />
                                    <rect x="117" y="5" width="2" height="50" fill="#000" />
                                    <rect x="123" y="5" width="4" height="50" fill="#000" />
                                    <rect x="131" y="5" width="2" height="50" fill="#000" />
                                    <rect x="137" y="5" width="5" height="50" fill="#000" />
                                    <rect x="146" y="5" width="2" height="50" fill="#000" />
                                    <rect x="152" y="5" width="4" height="50" fill="#000" />
                                    <rect x="160" y="5" width="2" height="50" fill="#000" />
                                    <rect x="166" y="5" width="6" height="50" fill="#000" />
                                    <rect x="176" y="5" width="2" height="50" fill="#000" />
                                    <rect x="182" y="5" width="4" height="50" fill="#000" />
                                    <rect x="190" y="5" width="2" height="50" fill="#000" />
                                    <rect x="196" y="5" width="5" height="50" fill="#000" />
                                    <rect x="205" y="5" width="2" height="50" fill="#000" />
                                    <rect x="211" y="5" width="4" height="50" fill="#000" />
                                    <rect x="219" y="5" width="2" height="50" fill="#000" />
                                    <rect x="225" y="5" width="6" height="50" fill="#000" />
                                    <rect x="235" y="5" width="2" height="50" fill="#000" />
                                    <rect x="241" y="5" width="4" height="50" fill="#000" />
                                </svg>
                            </div>
                            <div class="booking-ref"><?= $bookingRef ?></div>
                            <div class="boarding-note">Please proceed to gate for boarding</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Navigation -->
            <div class="navigation-section">
                <button class="nav-button" id="prev-btn">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M15 18L9 12L15 6" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
                <div class="nav-indicator">
                    <?php for ($i = 0; $i < count($packages); $i++): ?>
                        <div class="nav-dot <?= $i === 0 ? 'active' : '' ?>"></div>
                    <?php endfor; ?>
                </div>
                <button class="nav-button" id="next-btn">
                    <svg viewBox="0 0 24 24" fill="none">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="3" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        let isPaymentInProgress = false;

        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                window.location.reload(true);
            }
        });

        function bypassPayment(packageId, packageName) {
            fetch('<?= URLROOT ?>/payment/bypass-payment/' + packageId, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.replace('<?= URLROOT ?>/payment/finish/' + data.transaction_id);
                    } else {
                        alert('Payment bypass failed: ' + (data.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error during payment bypass');
                });
        }

        function payWithSnap(packageId, packageName, packagePrice) {
            if (isPaymentInProgress) return;
            isPaymentInProgress = true;

            fetch('<?= URLROOT ?>/payment/get-snap-token/' + packageId, {
                method: 'GET',
                headers: { 'Content-Type': 'application/json' }
            })
                .then(response => response.text())
                .then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response');
                    }
                })
                .then(data => {
                    if (data.success && data.snap_token) {
                        snap.pay(data.snap_token, {
                            onSuccess: function (result) {
                                fetch('<?= URLROOT ?>/payment/get-transaction-by-order/' + result.order_id)
                                    .then(response => response.json())
                                    .then(transactionData => {
                                        if (transactionData.success) {
                                            window.location.replace('<?= URLROOT ?>/payment/finish/' + transactionData.transaction_id);
                                        } else {
                                            window.location.replace('<?= URLROOT ?>/packages');
                                        }
                                    });
                            },
                            onPending: function (result) {
                                isPaymentInProgress = false;
                                alert('Payment is being processed.');
                            },
                            onError: function (result) {
                                isPaymentInProgress = false;
                                alert('Payment error occurred.');
                            },
                            onClose: function () {
                                isPaymentInProgress = false;
                            }
                        });
                    } else {
                        isPaymentInProgress = false;
                        alert('Failed to load payment');
                    }
                })
                .catch(error => {
                    isPaymentInProgress = false;
                    alert('Error loading payment');
                });
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Flash popup
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

            // Carousel navigation
            const container = document.getElementById('passes-container');
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const dots = document.querySelectorAll('.nav-dot');
            let currentIndex = 0;

            function updateDots() {
                dots.forEach((dot, index) => {
                    dot.classList.toggle('active', index === currentIndex);
                });
            }

            if (container && prevBtn && nextBtn) {
                const scrollAmount = 325;

                nextBtn.addEventListener('click', () => {
                    const maxIndex = dots.length - 1;
                    const maxScroll = container.scrollWidth - container.clientWidth;

                    if (container.scrollLeft >= maxScroll - 10) {
                        container.scrollTo({ left: 0, behavior: 'smooth' });
                        currentIndex = 0;
                    } else {
                        container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
                        currentIndex = Math.min(currentIndex + 1, maxIndex);
                    }
                    updateDots();
                });

                prevBtn.addEventListener('click', () => {
                    const maxIndex = dots.length - 1;

                    if (container.scrollLeft <= 10) {
                        container.scrollTo({ left: container.scrollWidth, behavior: 'smooth' });
                        currentIndex = maxIndex;
                    } else {
                        container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
                        currentIndex = Math.max(currentIndex - 1, 0);
                    }
                    updateDots();
                });

                container.addEventListener('scroll', () => {
                    const scrollPosition = container.scrollLeft;
                    const cardWidth = 325;
                    const newIndex = Math.round(scrollPosition / cardWidth);
                    if (newIndex !== currentIndex && newIndex < dots.length) {
                        currentIndex = newIndex;
                        updateDots();
                    }
                });
            }
        });
    </script>
</body>

</html>