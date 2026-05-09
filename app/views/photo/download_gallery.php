<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>First Class Gallery | Photobooth Airways</title>

    <!-- Ultra-Premium Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,400&family=Montserrat:wght@200;300;400;500;600&display=swap"
        rel="stylesheet">

    <!-- GSAP & Custom Scroll -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        /* === VARIABLES & RESET === */
        :root {
            --c-bg: #FDFBF7;
            --c-text: #11151C;
            --c-text-muted: #828A95;
            --c-gold: #C5A059;
            --c-navy: #0A1128;
            --ease-out-expo: cubic-bezier(0.16, 1, 0.3, 1);
            --ease-in-out-circ: cubic-bezier(0.85, 0, 0.15, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: auto;
        }

        /* Let GSAP handle smoothness */

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--c-bg);
            color: var(--c-text);
            overflow-x: hidden;
            cursor: none;
            /* Luxurious soft gradient */
            background-image:
                radial-gradient(at 0% 0%, rgba(197, 160, 89, 0.05) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(10, 17, 40, 0.03) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* === PRELOADER === */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: var(--c-bg);
            z-index: 9998;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        .loader-text {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.5rem;
            letter-spacing: 0.5em;
            color: var(--c-navy);
            text-transform: uppercase;
            font-style: italic;
        }

        .loader-line-wrap {
            width: 250px;
            height: 1px;
            background: rgba(10, 17, 40, 0.1);
            margin-top: 30px;
            position: relative;
            overflow: hidden;
        }

        .loader-line {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: var(--c-gold);
            transform: scaleX(0);
            transform-origin: left;
        }

        /* === CURSOR === */
        .cursor-dot,
        .cursor-ring {
            position: fixed;
            top: 0;
            left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            pointer-events: none;
            z-index: 10000;
        }

        .cursor-dot {
            width: 6px;
            height: 6px;
            background-color: var(--c-gold);
            transition: opacity 0.3s;
        }

        .cursor-ring {
            width: 36px;
            height: 36px;
            border: 1px solid var(--c-navy);
            transition: width 0.4s var(--ease-out-expo), height 0.4s var(--ease-out-expo), background-color 0.4s, border-color 0.4s;
        }

        body.hovering .cursor-ring {
            width: 70px;
            height: 70px;
            background-color: rgba(197, 160, 89, 0.1);
            border-color: transparent;
            backdrop-filter: blur(2px);
        }

        body.hovering .cursor-dot {
            opacity: 0;
        }

        /* === LAYOUT === */
        .container {
            max-width: 1500px;
            margin: 0 auto;
            padding: 0 4vw;
        }

        /* === HERO === */
        .hero {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: relative;
        }

        .hero-badge {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5em;
            color: var(--c-gold);
            margin-top: 3rem;
            font-weight: 500;
            overflow: hidden;
        }

        .hero-badge span {
            display: inline-block;
        }

        .hero-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(4rem, 10vw, 9rem);
            font-weight: 300;
            color: var(--c-navy);
            line-height: 0.9;
            margin-bottom: 2rem;
            text-transform: uppercase;
            perspective: 1000px;
        }

        /* Editorial Italic Accent */
        .hero-title .italic {
            font-style: italic;
            font-weight: 400;
            color: var(--c-gold);
            text-transform: lowercase;
            font-size: 1.1em;
        }

        .char {
            display: inline-block;
        }

        .hero-subtitle {
            font-size: clamp(0.9rem, 1.5vw, 1.1rem);
            color: var(--c-text-muted);
            max-width: 500px;
            line-height: 1.8;
            font-weight: 300;
            overflow: hidden;
            letter-spacing: 0.05em;
        }

        /* Vertical Scroll Line */
        .scroll-down {
            margin-top: 4vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            opacity: 0;
        }

        .scroll-text {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            writing-mode: vertical-rl;
            color: var(--c-navy);
        }

        .scroll-line-wrap {
            width: 1px;
            height: 80px;
            background: rgba(10, 17, 40, 0.1);
            position: relative;
            overflow: hidden;
        }

        .scroll-line-inner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--c-gold);
            transform: translateY(-100%);
            animation: scrollLine 2s cubic-bezier(0.77, 0, 0.175, 1) infinite;
        }

        @keyframes scrollLine {
            0% {
                transform: translateY(-100%);
            }

            50% {
                transform: translateY(0%);
            }

            100% {
                transform: translateY(100%);
            }
        }

        /* === EDITORIAL GALLERY === */
        .section-header {
            margin: 15vh 0 10vh;
            display: flex;
            align-items: center;
            gap: 40px;
            overflow: hidden;
        }

        .section-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: clamp(2rem, 5vw, 4rem);
            font-weight: 300;
            color: var(--c-navy);
            white-space: nowrap;
            text-transform: uppercase;
        }

        .section-line {
            height: 1px;
            width: 100%;
            background: rgba(10, 17, 40, 0.1);
            transform-origin: left;
            position: relative;
        }

        .section-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100px;
            height: 100%;
            background: var(--c-gold);
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 4vw;
            margin-bottom: 15vh;
        }

        /* Editorial Asymmetric Layout */
        .gallery-item {
            position: relative;
            perspective: 1200px;
        }

        /* Photostrips (4 per row on desktop) */
        .gallery-item.strip {
            grid-column: span 3;
        }

        .gallery-item.strip:nth-child(4n+1) {
            margin-top: 0vh;
        }

        .gallery-item.strip:nth-child(4n+2) {
            margin-top: 8vh;
        }

        .gallery-item.strip:nth-child(4n+3) {
            margin-top: 3vh;
        }

        .gallery-item.strip:nth-child(4n+4) {
            margin-top: 10vh;
        }

        /* Individual photos (3 per row on desktop) */
        .gallery-item.photo {
            grid-column: span 4;
        }

        .gallery-item.photo:nth-child(3n+1) {
            margin-top: 0vh;
        }

        .gallery-item.photo:nth-child(3n+2) {
            margin-top: 8vh;
        }

        .gallery-item.photo:nth-child(3n+3) {
            margin-top: 4vh;
        }

        @media (max-width: 992px) {

            .gallery-item.strip,
            .gallery-item.photo {
                grid-column: span 6 !important;
                margin-top: 0 !important;
            }
        }

        @media (max-width: 600px) {

            .gallery-item.strip,
            .gallery-item.photo {
                grid-column: span 12 !important;
            }
        }

        /* === CARD & GLARE EFFECTS === */
        .card-inner {
            background: #fff;
            padding: 24px;
            border-radius: 2px;
            box-shadow: 0 30px 60px rgba(10, 17, 40, 0.05);
            transform-style: preserve-3d;
            position: relative;
            transition: box-shadow 0.5s ease;
        }

        .gallery-item:hover .card-inner {
            box-shadow: 0 40px 90px rgba(197, 160, 89, 0.15);
        }

        /* Glass Glare Overlay */
        .glare {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            background: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.6) 0%, transparent 60%);
            opacity: 0;
            transition: opacity 0.4s ease;
            mix-blend-mode: overlay;
            z-index: 10;
        }

        .gallery-item:hover .glare {
            opacity: 1;
        }

        /* Airline Info Header */
        .ticket-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 1px solid rgba(10, 17, 40, 0.05);
            padding-bottom: 15px;
            transform: translateZ(20px);
        }

        .ticket-text {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--c-text-muted);
            font-weight: 500;
        }

        .ticket-icon svg {
            width: 16px;
            height: 16px;
            fill: var(--c-gold);
        }

        /* Dramatic Image Reveal */
        .img-wrap {
            position: relative;
            overflow: hidden;
            background: #F4F4F4;
            transform: translateZ(40px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
        }

        .img-reveal-mask {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--c-navy);
            z-index: 5;
            transform-origin: bottom;
        }

        .img-wrap img {
            width: 100%;
            height: auto;
            display: block;
            transform: scale(1.05);
            transition: transform 1.5s var(--ease-out-expo);
        }

        .gallery-item:hover .img-wrap img {
            transform: scale(1.1);
        }

        /* === TRUE MAGNETIC BUTTON === */
        .btn-wrap {
            margin-top: 30px;
            display: flex;
            justify-content: center;
            transform: translateZ(30px);
            position: relative;
            padding: 10px;
            /* Interaction area */
        }

        .btn-magnetic {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 18px 32px;
            background: var(--c-bg);
            color: var(--c-navy);
            border: 1px solid rgba(10, 17, 40, 0.1);
            text-decoration: none;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            font-weight: 600;
            cursor: none;
            transition: background 0.4s, color 0.4s, border-color 0.4s;
            /* Magnet movement applied via JS inline styles */
            will-change: transform;
        }

        .btn-magnetic:hover {
            background: var(--c-navy);
            color: var(--c-bg);
            border-color: var(--c-navy);
        }

        .btn-magnetic svg {
            width: 14px;
            height: 14px;
            fill: currentColor;
            transition: transform 0.4s var(--ease-out-expo);
        }

        .btn-magnetic:hover svg {
            transform: translateY(2px);
        }

        /* === FOOTER === */
        .footer {
            padding: 10vh 0 5vh;
            text-align: center;
            font-family: 'Cormorant Garamond', serif;
            font-size: 1rem;
            letter-spacing: 0.3em;
            color: var(--c-text-muted);
            text-transform: uppercase;
        }

        .footer span {
            color: var(--c-gold);
            font-style: italic;
        }

        /* === TOUCH DEVICE OPTIMIZATION === */
        @media (hover: none) and (pointer: coarse) {

            .cursor-dot,
            .cursor-ring {
                display: none;
            }

            body {
                cursor: auto;
            }

            .btn-magnetic {
                cursor: pointer;
                transform: none !important;
            }

            .card-inner,
            .ticket-info,
            .img-wrap,
            .btn-wrap {
                /* Biarkan GSAP yang menangani pergerakan */
            }

            .hero-title {
                font-size: 3rem;
            }

            .img-wrap img {
                transform: scale(1) !important;
            }

            .glare {
                display: none;
            }

            .gallery-grid {
                display: flex;
                flex-direction: column;
                gap: 40px;
            }
        }
    </style>
</head>

<body>

    <!-- Custom Cursor -->
    <div class="cursor-dot" id="cursorDot"></div>
    <div class="cursor-ring" id="cursorRing"></div>

    <!-- Preloader -->
    <div class="preloader" id="preloader">
        <div class="loader-text">Preparing Cabin</div>
        <div class="loader-line-wrap">
            <div class="loader-line" id="loaderLine"></div>
        </div>
    </div>

    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <div class="hero-badge"><span class="badge-text">VVIP Terminal</span></div>
            <h1 class="hero-title" id="mainTitle">Photobooth <span class="italic">Airways</span></h1>
            <p class="hero-subtitle">
                Rasakan pengalaman lepas landas bersama platform agen generasi berikutnya. Sebuah perjalanan visual yang
                tak terlupakan, di mana setiap momen berharga Anda diabadikan dan disajikan dengan standar kelas dunia.
            </p>

            <div class="scroll-down" id="scrollInd">
                <div class="scroll-text">Scroll</div>
                <div class="scroll-line-wrap">
                    <div class="scroll-line-inner"></div>
                </div>
            </div>
        </div>

        <!-- Photostrips -->
        <?php if (!empty($data['photostrips'])): ?>
            <div class="section-header">
                <h2 class="section-title">The <span style="color:var(--c-gold); font-style:italic;">Collection</span></h2>
                <div class="section-line"></div>
            </div>

            <div class="gallery-grid">
                <?php foreach ($data['photostrips'] as $index => $photostrip): ?>
                    <?php if ($photostrip->final_image_path): ?>
                        <div class="gallery-item strip">
                            <div class="card-inner">
                                <div class="glare"></div>
                                <div class="ticket-info">
                                    <span class="ticket-text">Class: First // Flt
                                        <?= str_pad($index + 1, 3, '0', STR_PAD_LEFT) ?></span>
                                    <div class="ticket-icon">
                                        <svg viewBox="0 0 24 24">
                                            <path
                                                d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="img-wrap">
                                    <div class="img-reveal-mask"></div>
                                    <img src="<?= URLROOT . $photostrip->final_image_path ?>" alt="Photostrip">
                                </div>
                                <div class="btn-wrap">
                                    <a href="<?= URLROOT . $photostrip->final_image_path ?>"
                                        download="PB_Airways_Strip_<?= $index + 1 ?>.png" class="btn-magnetic hover-target">
                                        <span>Acquire Asset</span>
                                        <svg viewBox="0 0 24 24">
                                            <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <!-- Individual Photos -->
        <?php if (!empty($data['session_photos'])): ?>
            <div class="section-header">
                <h2 class="section-title">Solo <span style="color:var(--c-gold); font-style:italic;">Captures</span></h2>
                <div class="section-line"></div>
            </div>

            <div class="gallery-grid">
                <?php foreach ($data['session_photos'] as $index => $photo): ?>
                    <div class="gallery-item photo">
                        <div class="card-inner">
                            <div class="glare"></div>
                            <div class="ticket-info">
                                <span class="ticket-text">Seat <?= str_pad($index + 1, 2, '0', STR_PAD_LEFT) ?>A</span>
                                <div class="ticket-icon">
                                    <svg viewBox="0 0 24 24">
                                        <path
                                            d="M21 16v-2l-8-5V3.5c0-.83-.67-1.5-1.5-1.5S10 2.67 10 3.5V9l-8 5v2l8-2.5V19l-2 1.5V22l3.5-1 3.5 1v-1.5L13 19v-5.5l8 2.5z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="img-wrap">
                                <div class="img-reveal-mask"></div>
                                <img src="<?= URLROOT . $photo->file_path ?>" alt="Capture">
                            </div>
                            <div class="btn-wrap">
                                <a href="<?= URLROOT . $photo->file_path ?>" download="PB_Airways_Capture_<?= $index + 1 ?>.png"
                                    class="btn-magnetic hover-target">
                                    <span>Acquire Asset</span>
                                    <svg viewBox="0 0 24 24">
                                        <path d="M19 9h-4V3H9v6H5l7 7 7-7zM5 18v2h14v-2H5z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="footer">
            &copy; <?= date('Y') ?> PHOTOBOOTH AIRWAYS. <span>CAPTURED IN EXCELLENCE.</span>
        </div>
    </div>

    <!-- Application Logic -->
    <script>
        gsap.registerPlugin(ScrollTrigger);

        // Device Check
        const isTouch = window.matchMedia("(pointer: coarse)").matches;

        // --- 1. Custom Cursor ---
        if (!isTouch) {
            const cursorDot = document.getElementById('cursorDot');
            const cursorRing = document.getElementById('cursorRing');
            let mouseX = window.innerWidth / 2, mouseY = window.innerHeight / 2;
            let ringX = mouseX, ringY = mouseY;

            window.addEventListener('mousemove', (e) => {
                mouseX = e.clientX; mouseY = e.clientY;
                gsap.set(cursorDot, { x: mouseX, y: mouseY });
            });

            const renderCursor = () => {
                ringX += (mouseX - ringX) * 0.15;
                ringY += (mouseY - ringY) * 0.15;
                gsap.set(cursorRing, { x: ringX, y: ringY });
                requestAnimationFrame(renderCursor);
            };
            requestAnimationFrame(renderCursor);

            document.querySelectorAll('a, .hover-target').forEach(el => {
                el.addEventListener('mouseenter', () => document.body.classList.add('hovering'));
                el.addEventListener('mouseleave', () => document.body.classList.remove('hovering'));
            });
        }

        // --- 2. Text Splitter for Hero ---
        const title = document.getElementById('mainTitle');
        const subtitle = document.querySelector('.hero-subtitle');
        if (subtitle) {
            const text = subtitle.innerText.trim();
            subtitle.innerHTML = '';
            text.split(' ').forEach(word => {
                const wrap = document.createElement('span');
                wrap.style.overflow = 'hidden';
                wrap.style.display = 'inline-block';
                wrap.style.marginRight = '0.25em';
                wrap.style.verticalAlign = 'bottom';

                const inner = document.createElement('span');
                inner.innerText = word;
                inner.style.display = 'inline-block';
                inner.classList.add('split-word');

                wrap.appendChild(inner);
                subtitle.appendChild(wrap);
            });
        }

        // --- 3. Preloader & Entrance ---
        const tlLoad = gsap.timeline();

        tlLoad.to('#loaderLine', { scaleX: 1, duration: 1.8, ease: "power3.inOut" })
            .to('#preloader', { yPercent: -100, duration: 1.2, ease: "expo.inOut" })
            .fromTo('.badge-text', { yPercent: 100 }, { yPercent: 0, duration: 1, ease: "power3.out" }, "-=0.4")
            .fromTo('#mainTitle', { y: 100, opacity: 0, rotateX: 20 }, { y: 0, opacity: 1, rotateX: 0, duration: 1.5, ease: "power4.out" }, "-=0.8")
            .fromTo('.split-word', { yPercent: 100 }, { yPercent: 0, duration: 1.2, stagger: 0.04, ease: "power4.out" }, "-=1.2")
            .to('#scrollInd', { opacity: 1, duration: 1 }, "-=0.5");

        // --- 4. Dramatic Scroll Reveals ---

        // Headers
        gsap.utils.toArray('.section-header').forEach(header => {
            const title = header.querySelector('.section-title');
            const line = header.querySelector('.section-line');

            gsap.timeline({ scrollTrigger: { trigger: header, start: "top 80%" } })
                .fromTo(title, { y: 60, opacity: 0 }, { y: 0, opacity: 1, duration: 1.2, ease: "power4.out" })
                .fromTo(line, { scaleX: 0 }, { scaleX: 1, duration: 1.5, ease: "power3.inOut" }, "-=1");
        });

        // Gallery Items (Curtain Reveal + Parallax + Container Scale)
        gsap.utils.toArray('.gallery-item').forEach(item => {
            const inner = item.querySelector('.card-inner');
            const mask = item.querySelector('.img-reveal-mask');
            const imgWrap = item.querySelector('.img-wrap');
            const img = item.querySelector('.img-wrap img');
            const info = item.querySelector('.ticket-info');
            const btn = item.querySelector('.btn-wrap');

            const tl = gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top 85%", // Trigger when top of item hits 85% of viewport
                }
            });

            // Slide entire card up
            tl.fromTo(inner, { y: 100, opacity: 0 }, { y: 0, opacity: 1, duration: 1.2, ease: "expo.out" })
                // Reveal image by sliding mask up
                .to(mask, { scaleY: 0, duration: 1.2, ease: "power4.inOut" }, "-=0.8")
                // Fade in details
                .fromTo([info, btn], { y: 20, opacity: 0 }, { y: 0, opacity: 1, duration: 1, stagger: 0.1, ease: "power3.out" }, "-=1.5");

            // Scroll-Driven Entire Card Scaling (Membesar & Mengecil)
            // Force hardware acceleration to prevent stuttering on mobile
            gsap.set(item, { willChange: "transform" });

            gsap.timeline({
                scrollTrigger: {
                    trigger: item,
                    start: "top bottom",
                    end: "bottom top",
                    scrub: 1 // Apple/Antigravity style smoothing interpolation (1s lag)
                }
            })
                .fromTo(item, { scale: 0.8 }, { scale: 1, duration: 1, ease: "sine.inOut" })
                .to(item, { scale: 0.8, duration: 1, ease: "sine.inOut" });

            // Subtle parallax effect on scroll
            if (!isTouch) {
                gsap.to(item, {
                    y: -50, // Move up slightly as you scroll down
                    ease: "none",
                    scrollTrigger: {
                        trigger: item,
                        start: "top bottom",
                        end: "bottom top",
                        scrub: true
                    }
                });
            }
        });

        // --- 5. Interactive Physics (Tilt + Glare + Magnetic Button) ---
        if (!isTouch) {
            const cards = document.querySelectorAll('.gallery-item');

            cards.forEach(card => {
                const inner = card.querySelector('.card-inner');
                const glare = card.querySelector('.glare');
                const magneticBtn = card.querySelector('.btn-magnetic');

                // 3D Tilt & Glare
                card.addEventListener('mousemove', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    // Tilt
                    const rotateX = ((y - centerY) / centerY) * -8;
                    const rotateY = ((x - centerX) / centerX) * 8;

                    gsap.to(inner, {
                        rotateX: rotateX,
                        rotateY: rotateY,
                        duration: 0.6,
                        ease: "power2.out"
                    });

                    // Glare position
                    const glareX = (x / rect.width) * 100;
                    const glareY = (y / rect.height) * 100;
                    glare.style.background = `radial-gradient(circle at ${glareX}% ${glareY}%, rgba(255,255,255,0.8) 0%, transparent 50%)`;
                });

                card.addEventListener('mouseleave', () => {
                    gsap.to(inner, {
                        rotateX: 0, rotateY: 0,
                        duration: 1.2, ease: "elastic.out(1, 0.3)"
                    });
                });

                // True Magnetic Button
                const btnWrap = card.querySelector('.btn-wrap');
                if (magneticBtn && btnWrap) {
                    btnWrap.addEventListener('mousemove', (e) => {
                        const btnRect = magneticBtn.getBoundingClientRect();
                        const btnCenterX = btnRect.left + btnRect.width / 2;
                        const btnCenterY = btnRect.top + btnRect.height / 2;

                        // Calculate pull
                        const pullX = (e.clientX - btnCenterX) * 0.3; // 30% pull strength
                        const pullY = (e.clientY - btnCenterY) * 0.3;

                        gsap.to(magneticBtn, {
                            x: pullX,
                            y: pullY,
                            scale: 1.05,
                            duration: 0.4,
                            ease: "power2.out"
                        });
                    });

                    btnWrap.addEventListener('mouseleave', () => {
                        gsap.to(magneticBtn, {
                            x: 0, y: 0,
                            scale: 1,
                            duration: 0.8,
                            ease: "elastic.out(1, 0.3)"
                        });
                    });
                }
            });
        }
    </script>
</body>

</html>