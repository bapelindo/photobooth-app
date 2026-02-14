<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Your Frames - Photobooth Airways</title>
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
            font-family: 'Roboto Condensed', sans-serif;
        }

        /* ========== BEAUTIFUL LIGHT SKY (MATCHING SESSION) ========== */
        body {
            /* Blue #c2e9fb -> Peach #fed6e3 */
            background: linear-gradient(135deg, #c2e9fb 0%, #fed6e3 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            padding: 20px;
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

        /* Flying Plane */
        .plane-container {
            position: fixed;
            top: 20%;
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
            width: 50px;
            height: 50px;
            transform: rotate(90deg);
        }

        @keyframes flyPlane {
            0% {
                left: -300px;
                top: 20%;
            }

            100% {
                left: 110%;
                top: 20%;
            }
        }

        /* ========== FROSTED GLASS CONTAINER (like session.php) ========== */
        .main-container {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 100%;
            height: 95vh;
            display: flex;
            flex-direction: column;

            /* Frosted glass effect matching session.php */
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            box-sizing: border-box;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);

            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards;
        }

        @keyframes fadeIn {
            to {
                opacity: 1;
            }
        }

        /* ========== AIRPORT HEADER - COMPACT BAR ========== */
        .airport-header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            padding: 8px 20px;
            border-radius: 50px;
            /* Capsule shape */
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.1);
            margin-bottom: 15px;
            gap: 15px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .header-left,
        .header-right {
            flex: 1;
            display: flex;
            align-items: center;
        }

        .header-left {
            justify-content: flex-start;
        }

        .header-right {
            justify-content: flex-end;
        }

        .airline-branding {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .airline-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.65rem;
            color: #1B365D;
            letter-spacing: 1px;
            font-weight: 900;
            line-height: 1;
        }

        .airline-sub {
            font-size: 0.5rem;
            color: #007FFF;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .page-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.1rem;
            font-weight: 900;
            color: #1B365D;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
            white-space: nowrap;
        }

        .page-subtitle {
            display: none;
            /* Hide subtitle to save space */
        }

        .selection-counter {
            background: linear-gradient(135deg, #00BFFF 0%, #007FFF 100%);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.8rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(0, 127, 255, 0.25);
            white-space: nowrap;
        }

        /* Responsive adjustments for header */
        @media (max-width: 768px) {
            .airport-header {
                padding: 6px 12px;
                border-radius: 12px;
                /* Rectangular on mobile to fit text */
                gap: 10px;
            }

            .page-title {
                font-size: 0.85rem;
            }

            .airline-name {
                font-size: 0.5rem;
            }

            .airline-sub {
                font-size: 0.4rem;
            }

            .selection-counter {
                padding: 4px 10px;
                font-size: 0.65rem;
            }
        }


        /* ========== FRAMES CONTAINER ========== */
        .frames-container {
            flex-grow: 1;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 0 15px 20px 15px;
        }

        .frames-container::-webkit-scrollbar {
            width: 12px;
        }

        .frames-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }

        .frames-container::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #00BFFF, #007FFF);
            border-radius: 10px;
        }

        .frames-container::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #007FFF, #0056CC);
        }

        /* ========== FRAMES GRID - COMPACT & VISIBLE ========== */
        .frames-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            /* Much smaller: ~420px height */
            gap: 15px;
            padding: 10px;
            justify-content: center;
        }

        /* ========== FRAME CARD - 2:6 ASPECT RATIO (1:3) ========== */
        .frame-card {
            background: white;
            border-radius: 8px;
            /* Slightly smaller radius */
            overflow: hidden;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 2px solid transparent;
            /* Thinner border */
            position: relative;
            aspect-ratio: 1 / 3;
            /* 2:6 inch ratio strictly maintained */
        }

        .frame-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 10px 25px rgba(0, 127, 255, 0.25);
            border-color: #00BFFF;
        }

        .frame-card.selected {
            border-color: #FB9F8B;
            box-shadow: 0 5px 20px rgba(251, 159, 139, 0.4);
        }

        /* Frame Number Badge */
        .frame-number {
            position: absolute;
            top: 8px;
            left: 8px;
            background: linear-gradient(135deg, #00BFFF, #007FFF);
            color: white;
            width: 25px;
            /* Smaller badge */
            height: 25px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            z-index: 5;
            box-shadow: 0 2px 8px rgba(0, 127, 255, 0.4);
        }

        /* Frame Preview - FULL SIZE */
        .frame-preview {
            width: 100%;
            height: 100%;
            position: relative;
        }

        .frame-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Selection Checkmark */
        .selection-check {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0) rotate(-15deg);
            width: 50px;
            /* Smaller checkmark */
            height: 50px;
            background: #00BFFF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
            opacity: 0;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            border: 3px solid white;
            box-shadow: 0 4px 15px rgba(0, 127, 255, 0.6);
            z-index: 10;
        }

        .frame-card.selected .selection-check {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1) rotate(0deg);
        }

        /* ========== CONTINUE BUTTON ========== */
        .continue-btn {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%) scale(0.5);
            z-index: 1000;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 12px 30px;
            /* Smaller padding */
            background: linear-gradient(135deg, #FB9F8B 0%, #F58C75 100%);
            color: white;
            border: none;
            border-radius: 40px;
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 1rem;
            /* Smaller font */
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            box-shadow: 0 6px 20px rgba(251, 159, 139, 0.5);
            opacity: 0;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
        }

        .continue-btn.is-visible {
            opacity: 1;
            transform: translateX(-50%) scale(1);
            pointer-events: auto;
        }

        .continue-btn:hover {
            background: linear-gradient(135deg, #F58C75 0%, #E67E22 100%);
            transform: translateX(-50%) scale(1.05) translateY(-3px);
            box-shadow: 0 10px 30px rgba(251, 159, 139, 0.6);
        }

        .continue-btn .plane-icon {
            width: 20px;
            height: 20px;
        }

        /* ========== RESPONSIVE - LANDSCAPE OPTIMIZED (6-8 FRAMES) ========== */

        /* Large Desktop & Landscape - 6-8 frames */
        @media (min-width: 1400px) and (orientation: landscape) {
            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
                gap: 20px;
            }

            .page-title {
                font-size: 1.5rem;
            }
        }

        /* Desktop Landscape - 6-8 frames */
        @media (min-width: 1024px) and (max-width: 1399px) and (orientation: landscape) {
            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 18px;
            }
        }

        /* Tablet Landscape - 6-8 frames */
        @media (min-width: 768px) and (max-width: 1023px) and (orientation: landscape) {
            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
                gap: 15px;
            }

            .page-title {
                font-size: 0.95rem;
            }

            .airport-header {
                padding: 6px 12px;
            }

            .frame-number {
                width: 20px;
                height: 20px;
                font-size: 0.6rem;
            }

            .selection-check {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }
        }

        /* Mobile Landscape - 6-8 frames */
        @media (max-width: 767px) and (orientation: landscape) {
            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 12px;
            }

            .airport-header {
                padding: 4px 10px;
                gap: 8px;
            }

            .page-title {
                font-size: 0.75rem;
            }

            .selection-counter {
                padding: 3px 8px;
                font-size: 0.6rem;
            }

            .frame-number {
                width: 18px;
                height: 18px;
                font-size: 0.5rem;
                top: 5px;
                left: 5px;
            }

            .selection-check {
                width: 35px;
                height: 35px;
                font-size: 18px;
                border: 2px solid white;
            }

            .continue-btn {
                font-size: 0.8rem;
                padding: 8px 20px;
                bottom: 10px;
            }
        }

        /* ========== PORTRAIT MODE (2-3 FRAMES) ========== */

        /* Tablet Portrait - 2-3 frames */
        @media (max-width: 768px) and (orientation: portrait) {
            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                /* Keep smaller */
                gap: 15px;
            }

            .page-title {
                font-size: 0.95rem;
            }
        }

        /* Mobile Portrait - 2-3 frames */
        @media (max-width: 480px) {
            .airport-header {
                padding: 6px 12px;
            }

            .airline-name {
                font-size: 0.45rem;
            }

            .page-title {
                font-size: 0.9rem;
            }

            .page-subtitle {
                font-size: 0.65rem;
            }

            .frames-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                /* Even smaller on mobile portrait */
                gap: 12px;
            }

            .frame-number {
                width: 22px;
                height: 22px;
                font-size: 0.6rem;
                top: 6px;
                left: 6px;
            }

            .selection-check {
                width: 40px;
                height: 40px;
                font-size: 20px;
            }

            .continue-btn {
                font-size: 0.9rem;
                padding: 10px 25px;
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
        <!-- Airport Header (Horizontal Bar) -->
        <div class="airport-header">
            <div class="header-left">
                <div class="airline-branding">
                    <div class="airline-name">PHOTOBOOTH</div>
                    <div class="airline-sub">AIRWAYS</div>
                </div>
            </div>

            <div class="page-title">SELECT FRAMES</div>

            <div class="header-right">
                <div class="selection-counter">
                    <span id="selection-count">0</span> / <?= $data['frame_limit'] ?> <span style="font-size: 0.7em;
                        opacity: 0.8; margin-left: 5px;">SELECTED</span>
                </div>
            </div>
        </div>

        <!-- Frames Container -->
        <div class="frames-container">
            <?php if (empty($data['frames'])): ?>
                <p style="text-align: center; padding: 40px; color: #666;">No frames available for this photo package.
                    Please contact admin.</p>
            <?php else: ?>
                <form id="frame-selection-form" method="POST" action="<?= URLROOT; ?>/photo/submit-frame-selection">
                    <input type="hidden" name="transaction_id" value="<?= $data['transaction_id'] ?>">

                    <div class="frames-grid">
                        <?php
                        $frameNumber = 1;
                        foreach ($data['frames'] as $frame):
                            ?>
                            <div class="frame-card" data-frame-id="<?= $frame->id ?>">
                                <input type="checkbox" name="selected_frames[]" value="<?= $frame->id ?>"
                                    style="display: none;">

                                <div class="frame-number"><?= $frameNumber ?></div>

                                <div class="frame-preview">
                                    <img src="<?= URLROOT . htmlspecialchars($frame->path); ?>"
                                        alt="<?= htmlspecialchars($frame->name); ?>">
                                    <div class="selection-check">✓</div>
                                </div>
                            </div>
                            <?php
                            $frameNumber++;
                        endforeach;
                        ?>
                    </div>
                </form>
            <?php endif; ?>
        </div>

        <!-- Continue Button -->
        <button type="submit" form="frame-selection-form" class="continue-btn" id="continue-btn">
            <svg class="plane-icon" viewBox="0 0 24 24" fill="none">
                <path
                    d="M21 16V14L13 9V3.5C13 2.67 12.33 2 11.5 2C10.67 2 10 2.67 10 3.5V9L2 14V16L10 13.5V19L8 20.5V22L11.5 21L15 22V20.5L13 19V13.5L21 16Z"
                    fill="currentColor" />
            </svg>
            <span>PROCEED TO PHOTO SESSION</span>
        </button>
    </div>

    <script>
        // Back/refresh protection
        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
            let allowNavigation = false;

            window.addEventListener('beforeunload', function (e) {
                if (allowNavigation) return;
                e.preventDefault();
                e.returnValue = '';
                return '';
            });

            let currentUrl = window.location.href;
            window.history.pushState({}, '', currentUrl);

            window.addEventListener('popstate', function (e) {
                if (allowNavigation) return;
                if (confirm('⚠️ WARNING!\n\nYou are trying to go back. Frame selection progress will be lost.\n\nAre you sure you want to continue?')) {
                    allowNavigation = true;
                    window.history.go(-1);
                } else {
                    window.history.pushState({}, '', currentUrl);
                }
            });
        <?php endif; ?>

        // Frame selection logic
        document.addEventListener('DOMContentLoaded', () => {
            const frameCards = document.querySelectorAll('.frame-card');
            const selectionCount = document.getElementById('selection-count');
            const continueBtn = document.getElementById('continue-btn');
            const frameLimit = <?= $data['frame_limit'] ?>;
            let selectedFrames = [];

            function updateCounter() {
                selectionCount.textContent = selectedFrames.length;

                if (selectedFrames.length === frameLimit) {
                    continueBtn.classList.add('is-visible');
                } else {
                    continueBtn.classList.remove('is-visible');
                }
            }

            frameCards.forEach(frameCard => {
                frameCard.addEventListener('click', function (e) {
                    e.preventDefault();

                    const frameId = this.dataset.frameId;
                    const checkbox = this.querySelector('input[type="checkbox"]');

                    if (this.classList.contains('selected')) {
                        // Deselect
                        this.classList.remove('selected');
                        checkbox.checked = false;
                        selectedFrames = selectedFrames.filter(id => id !== frameId);
                    } else {
                        // Select (if limit not reached)
                        if (selectedFrames.length < frameLimit) {
                            this.classList.add('selected');
                            checkbox.checked = true;
                            selectedFrames.push(frameId);
                        }
                    }

                    updateCounter();
                });
            });

            // Form submission
            const form = document.getElementById('frame-selection-form');
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                if (selectedFrames.length !== frameLimit) {
                    alert(`Please select ${frameLimit} frames to continue.`);
                    return;
                }

                <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
                    allowNavigation = true;
                <?php endif; ?>

                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.4s ease-out';

                setTimeout(() => {
                    this.submit();
                }, 500);
            });
        });
    </script>

</body>

</html>