<?php
// Prevent caching
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - Photobooth Airways</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
            width: 100%;
            overflow: hidden;
            /* Prevent scrolling */
            font-family: 'Roboto Condensed', sans-serif;
        }

        /* ========== BEAUTIFUL LIGHT SKY (STRICT PALETTE) ========== */
        body {
            /* Turquoise #6BB6BC -> Pale Pink #E2CCC6 -> Peach #FB9F8B */
            background: linear-gradient(to right, #c2e9fb 0%, #a1c4fd 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Animated Clouds */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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

        /* Cloud Variations */
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

        .cloud2 {
            width: 100px;
            height: 40px;
            top: 75%;
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

        @keyframes float {
            0% {
                left: -200px;
            }

            100% {
                left: 110%;
            }
        }

        /* Flying Plane Container */
        .plane-container {
            position: fixed;
            top: 20%;
            left: -300px;
            display: flex;
            align-items: center;
            z-index: 100;
            animation: flyPlane 45s linear infinite;
            cursor: pointer;
            pointer-events: auto;
            transition: transform 0.3s ease;
        }

        .plane-container:hover {
            transform: scale(1.1);
        }

        .plane-container.barrel-roll .plane {
            animation: barrelRoll 1s ease-in-out;
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
            width: 50px;
            height: 50px;
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }

        @keyframes flyPlane {
            0% {
                left: -300px;
                top: 20%;
                transform: rotate(2deg);
            }

            100% {
                left: 110%;
                top: 20%;
                transform: rotate(1deg);
            }
        }

        @keyframes barrelRoll {
            0% {
                transform: rotate(90deg);
            }

            100% {
                transform: rotate(450deg);
            }
        }

        /* ========== TICKET CONTAINER ========== */
        .ticket-wrapper {
            position: relative;
            z-index: 10;
            width: 90%;
            max-width: 400px;
            animation: popIn 0.8s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes popIn {
            0% {
                transform: scale(0.8) translateY(50px);
                opacity: 0;
            }

            100% {
                transform: scale(1) translateY(0);
                opacity: 1;
            }
        }

        /* ========== BOARDING PASS TICKET ========== */
        .boarding-stub {
            background: linear-gradient(135deg, #00BFFF 0%, #007FFF 100%);
            padding: 20px;
            border-radius: 12px 12px 0 0;
            color: white;
            text-align: center;
            position: relative;
        }

        .boarding-stub::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #A3E8FD, #C6C7DA, #A3E8FD);
        }

        .airline-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            letter-spacing: 2px;
            opacity: 0.9;
            margin-bottom: 5px;
        }

        .ticket-status {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .ticket-substatus {
            font-size: 0.8rem;
            font-weight: 400;
            opacity: 0.95;
        }

        .perforation {
            height: 3px;
            background: repeating-linear-gradient(90deg, transparent 0px, transparent 6px, #ccc 6px, #ccc 10px);
            background-color: #f0f0f0;
            position: relative;
        }

        .perforation::before,
        .perforation::after {
            content: '';
            position: absolute;
            width: 24px;
            height: 24px;
            background: #a1c4fd;
            /* Matches body background somewhat, but tricky with gradient */
            /* Using mask is better for transparency, but solid color okay for simple implementation */
            background: transparent;
            /* Use box-shadow to fake the cutout if background is complex, or just use mask */
            border-radius: 50%;
            top: -11px;
            z-index: 10;
        }

        /* Left/Right Cutouts - Using border/mask trick or matching background */
        /* Since background is gradient, we use mask on container or overwrite with pseudo elements matching gradient? 
           Easier: Use same SVG mask trick or just dark dots */
        .ticket-body {
            background: white;
            padding: 24px;
            border-radius: 0 0 12px 12px;
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
            position: relative;
            /* Cutouts logic */
            -webkit-mask-image: radial-gradient(circle at left top, transparent 15px, black 16px),
                radial-gradient(circle at right top, transparent 15px, black 16px);
            -webkit-mask-composite: source-in, source-in;
            mask-image: radial-gradient(circle at left top, transparent 15px, black 16px),
                radial-gradient(circle at right top, transparent 15px, black 16px);
            mask-composite: intersect;
            margin-top: -1px;
            /* Fix sub-pixel gap */
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }

        .info-group {
            display: flex;
            flex-direction: column;
        }

        .label {
            font-size: 0.65rem;
            text-transform: uppercase;
            color: #90A4AE;
            font-weight: 700;
            letter-spacing: 0.5px;
            margin-bottom: 3px;
        }

        .value {
            font-family: 'Roboto Mono', monospace;
            font-size: 0.9rem;
            color: #1B365D;
            font-weight: 700;
        }

        .highlight-value {
            color: #007FFF;
            font-size: 1.1rem;
        }

        .flight-route {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            padding: 15px 0;
            border-top: 2px dashed #E3E0EC;
            border-bottom: 2px dashed #E3E0EC;
        }

        .route-code {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #007FFF;
        }

        .route-icon {
            color: #FB9F8B;
        }

        .message-box {
            background: #F5F7FA;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 25px;
            border-left: 3px solid #00BFFF;
        }

        .message-text {
            font-size: 0.9rem;
            color: #455A64;
            line-height: 1.4;
        }

        .action-btn {
            display: block;
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #FB9F8B 0%, #F58C75 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: center;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(251, 159, 139, 0.4);
        }

        .action-btn:hover {
            background: linear-gradient(135deg, #F58C75 0%, #E67E22 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(251, 159, 139, 0.5);
        }

        .barcode-section {
            margin-top: 20px;
            text-align: center;
            opacity: 0.7;
        }

        /* Status Colors */
        .status-success {
            background: linear-gradient(135deg, #00BFFF 0%, #007FFF 100%);
        }

        .status-pending {
            background: linear-gradient(135deg, #FBC02D 0%, #F57F17 100%);
        }

        .status-failure {
            background: linear-gradient(135deg, #EF5350 0%, #C62828 100%);
        }

        /* Responsive */
        @media (max-height: 600px) {
            .ticket-wrapper {
                transform: scale(0.9);
            }
        }
    </style>
</head>

<body>

    <!-- Decorations -->
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
    </div>

    <div class="plane-container" onclick="interactivePlane(this)">
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

    <div class="ticket-wrapper">
        <?php if ($transaction && $transaction->payment_status === 'success'): ?>
            <!-- SUCCESS TICKET -->
            <div class="boarding-stub status-success">
                <div class="airline-name">PHOTOBOOTH AIRWAYS</div>
                <div class="ticket-status">BOARDING PASS</div>
                <div class="ticket-substatus">Payment Confirmed • Economy Class</div>
            </div>

            <div class="ticket-body">
                <div class="info-grid">
                    <div class="info-group">
                        <div class="label">Passenger</div>
                        <div class="value">GUEST</div>
                    </div>
                    <div class="info-group">
                        <div class="label">Flight No</div>
                        <div class="value">PB-<?= substr($transaction->id, -4) ?></div>
                    </div>
                    <div class="info-group">
                        <div class="label">Date</div>
                        <div class="value"><?= date('d M Y') ?></div>
                    </div>
                    <div class="info-group">
                        <div class="label">Gate</div>
                        <div class="value highlight-value">A01</div>
                    </div>
                </div>

                <div class="flight-route">
                    <div class="route-code">YOU</div>
                    <div class="route-icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                                fill="#FB9F8B" />
                        </svg>
                    </div>
                    <div class="route-code">PIC</div>
                </div>

                <div class="message-box">
                    <div class="message-text">Gate is open. Please proceed to the camera zone immediately. Find your pose!
                    </div>
                </div>

                <a href="<?= URLROOT; ?>/photo/select_frame/<?= $transaction->id ?>" class="action-btn">
                    START PHOTO SESSION
                </a>

                <div class="barcode-section">
                    <svg height="30" viewBox="0 0 200 60" width="100%">
                        <rect x="0" y="0" width="200" height="60" fill="none"></rect>
                        <g fill="#000">
                            <rect x="10" y="10" width="4" height="40"></rect>
                            <rect x="16" y="10" width="2" height="40"></rect>
                            <rect x="22" y="10" width="6" height="40"></rect>
                            <rect x="32" y="10" width="2" height="40"></rect>
                            <rect x="38" y="10" width="4" height="40"></rect>
                            <rect x="46" y="10" width="2" height="40"></rect>
                            <rect x="52" y="10" width="8" height="40"></rect>
                            <rect x="64" y="10" width="2" height="40"></rect>
                            <rect x="70" y="10" width="4" height="40"></rect>
                            <rect x="78" y="10" width="2" height="40"></rect>
                            <rect x="84" y="10" width="6" height="40"></rect>
                            <rect x="94" y="10" width="2" height="40"></rect>
                            <rect x="100" y="10" width="4" height="40"></rect>
                            <rect x="108" y="10" width="2" height="40"></rect>
                            <rect x="114" y="10" width="6" height="40"></rect>
                            <rect x="124" y="10" width="2" height="40"></rect>
                            <rect x="130" y="10" width="4" height="40"></rect>
                            <rect x="138" y="10" width="2" height="40"></rect>
                            <rect x="144" y="10" width="8" height="40"></rect>
                            <rect x="156" y="10" width="2" height="40"></rect>
                            <rect x="162" y="10" width="4" height="40"></rect>
                            <rect x="170" y="10" width="2" height="40"></rect>
                            <rect x="176" y="10" width="6" height="40"></rect>
                            <rect x="186" y="10" width="2" height="40"></rect>
                        </g>
                    </svg>
                </div>
            </div>

        <?php elseif ($transaction && $transaction->payment_status === 'pending'): ?>
            <!-- PENDING TICKET -->
            <div class="boarding-stub status-pending">
                <div class="airline-name">PHOTOBOOTH AIRWAYS</div>
                <div class="ticket-status">Please Wait</div>
                <div class="ticket-substatus">Payment Processing • Standby</div>
            </div>

            <div class="ticket-body">
                <div class="info-grid">
                    <div class="info-group">
                        <div class="label">Passenger</div>
                        <div class="value">GUEST</div>
                    </div>
                    <div class="info-group">
                        <div class="label">Status</div>
                        <div class="value highlight-value" style="color: #F57F17;">PENDING</div>
                    </div>
                </div>

                <div class="message-box" style="border-color: #FBC02D;">
                    <div class="message-text">We are verifying your payment. Please complete the transaction to receive your
                        boarding pass.</div>
                </div>

                <a href="<?= URLROOT; ?>/packages" class="action-btn"
                    style="background: linear-gradient(135deg, #a1c4fd, #c2e9fb); color: #1B365D;">
                    Choose Another Package
                </a>
            </div>

        <?php else: ?>
            <!-- FAILURE TICKET -->
            <div class="boarding-stub status-failure">
                <div class="airline-name">PHOTOBOOTH AIRWAYS</div>
                <div class="ticket-status">Cancelled</div>
                <div class="ticket-substatus">Transaction Failed • Please Retry</div>
            </div>

            <div class="ticket-body">
                <div class="message-box" style="border-color: #EF5350;">
                    <div class="message-text">There was a problem with your payment. Your flight has been cancelled. Please
                        try booking again.</div>
                </div>

                <a href="<?= URLROOT; ?>/packages" class="action-btn"
                    style="background: linear-gradient(135deg, #EF5350, #C62828);">
                    Try Again
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Back Protection Script -->
    <script>
        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
            // Prevent back button
            history.pushState(null, null, location.href);
            window.onpopstate = function () {
                history.go(1);
            };
        <?php endif; ?>
    </script>
    <script>
        function interactivePlane(container) {
            if (!container.classList.contains('barrel-roll')) {
                container.classList.add('barrel-roll');
                setTimeout(() => {
                    container.classList.remove('barrel-roll');
                }, 1000);
            }
        }
    </script>
</body>

</html>