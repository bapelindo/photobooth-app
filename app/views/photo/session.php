<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>Sesi Foto Interaktif - Photobooth Airways | Photobooth Event</title>
    <meta name="description"
        content="Enjoy an interactive photo session at Photobooth Airways. Snap unforgettable memories with our virtual photobooth technology.">
    <meta name="keywords"
        content="photobooth, photobooth online, photobooth bapel, photobooth airway, virtual photobooth, photo booth jakarta, photobooth event, sewa photobooth, photobooth wedding">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="Interactive Photo Session - Photobooth Airways">
    <meta property="og:description"
        content="Enjoy an interactive photo session at Photobooth Airways. Snap unforgettable memories with our virtual photobooth technology.">
    <meta property="og:site_name" content="Photobooth Airways">
    <meta property="og:locale" content="id_ID">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Interactive Photo Session - Photobooth Airways">
    <meta name="twitter:description" content="Enjoy an interactive photo session at Photobooth Airways.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #00BFFF;
            --secondary-blue: #007FFF;
            --dark-blue: #1B365D;
            --primary-peach: #FB9F8B;
            --secondary-peach: #F58C75;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(to bottom, #87CEEB 0%, #E0F7FA 60%, #FFDAB9 100%);
        }

        /* ========== ANIMATED CLOUDS ========== */
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

        /* ========== FLYING PLANE ========== */
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
            }

            100% {
                left: 110%;
                top: 20%;
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

        body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: 'Roboto Condensed', sans-serif;
            background: var(--bg-gradient);
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
        }

        .session-container {
            display: grid;
            grid-template-rows: auto 1fr;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
            width: 100%;
            height: 95vh;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);

        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .camera-section {
            background: transparent;
            border-radius: 20px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        .session-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .airline-branding {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .airline-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 0.75rem;
            color: var(--dark-blue);
            letter-spacing: 1px;
            font-weight: 900;
            line-height: 1;
        }

        .airline-sub {
            font-size: 0.55rem;
            color: var(--primary-blue);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .session-info h1 {
            font-family: 'Orbitron', sans-serif;
            color: var(--dark-blue);
            margin: 0;
            font-size: 1.4rem;
            font-weight: 900;
            text-transform: uppercase;
        }

        .session-stats {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--primary-blue);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 2px;
        }

        .timer {
            font-size: 2rem;
            font-weight: bold;
            color: var(--secondary-blue);
            font-family: 'Roboto Mono', monospace;
        }

        .timer.warning {
            color: var(--warning-color);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .camera-section {
            background: rgba(255, 255, 255, 0.55);
            border-radius: 15px;
            position: relative;
            display: flex;
            padding: 10px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
        }

        .safe-zone {
            flex-grow: 1;
            position: relative;
            border-radius: 10px;
            margin: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
            aspect-ratio: 16/9;
            transition: aspect-ratio 0.3s ease;
        }


        .safe-zone-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .safe-zone-shade {
            position: absolute;
            background: rgba(0, 0, 0, 0.2);
            pointer-events: none;
        }

        .safe-zone-clear {
            position: absolute;
            background: transparent;
            border: 2px dashed rgba(253, 253, 253, 0.25);
            border-radius: 8px;
        }

        .safe-zone-label {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.25);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            backdrop-filter: blur(5px);
        }

        .safe-zone-instruction {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.3);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            color: var(--primary-color);
            text-align: center;
            font-size: 0.8rem;
            backdrop-filter: blur(5px);
        }

        #camera-feed {
            width: 100%;
            height: 100%;
            aspect-ratio: 16/9;
            object-fit: contain;
            border-radius: 7px;
            background: #000;
            transition: aspect-ratio 0.3s ease;
            transform: scaleX(-1);
            /* Mirror the camera feed */
        }

        .photo-preview {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 10;
        }

        .preview-image {
            max-width: 80%;
            max-height: 60%;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .preview-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .capture-section {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.3);
            padding: 5px 5px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .filter-dropdown label {
            font-weight: 600;
            color: var(--primary-blue);
            font-size: 0.7rem;
        }

        .filter-dropdown select {
            padding: 5px 5px;
            border: 2px solid var(--primary-blue);
            border-radius: 20px;
            background: white;
            color: var(--primary-blue);
            font-weight: 600;
            font-size: 0.7rem;
            cursor: pointer;
            outline: none;
            min-width: 120px;
        }

        .filter-dropdown select:focus {
            border-color: var(--secondary-blue);
            box-shadow: 0 0 0 2px rgba(0, 191, 255, 0.2);
        }

        .btn {
            padding: 10px 12px;
            border: none;
            border-radius: 25px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.6rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        /* SVG Icon styling */
        .btn svg {
            width: 1.2em;
            height: 1.2em;
        }

        /* Spin Animation for loading icons */
        .spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        .btn-capture {
            background: linear-gradient(135deg, var(--primary-peach) 0%, var(--secondary-peach) 100%);
            color: white;
            font-size: 0.7rem;
            padding: 10px 10px;
            box-shadow: 0 4px 15px rgba(251, 159, 139, 0.4);
            min-width: 150px;
            text-align: center;
        }

        .btn-capture:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 159, 139, 0.5);
        }

        .btn-save {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
        }

        .btn-delete {
            background: linear-gradient(135deg, var(--primary-peach), var(--secondary-peach));
            color: white;
        }

        .btn-continue {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            color: white;
            font-size: 0.7rem;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-fullscreen {
            background: linear-gradient(135deg, var(--dark-blue), var(--secondary-blue));
            color: white;
        }

        .btn-fullscreen:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        #finish-session-btn {
            /* Success Color mixed with Blue for Tech Feel */
            background: linear-gradient(135deg, var(--success-color) 0%, var(--primary-blue) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 191, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 10px 12px;
            border-radius: 25px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.6rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        #finish-session-btn:hover {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        #finish-session-btn svg {
            width: 1.2em;
            height: 1.2em;
        }

        .camera-section.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1000;
            padding: 0;
            margin: 0;
            border-radius: 0;
        }

        .camera-section.fullscreen .safe-zone {
            width: 100%;
            height: 100%;
            margin: 0;
            border-radius: 0;
        }

        .camera-section.fullscreen .camera-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 10px;
            border-radius: 25px;
            z-index: 1001;
            display: flex;
            gap: 15px;
        }

        .fullscreen-timer {
            display: none;
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 20px;
            border-radius: 15px;
            z-index: 1001;
            text-align: center;
        }

        .fullscreen-timer .timer {
            color: white;
        }

        .camera-section.fullscreen .fullscreen-timer {
            display: block;
        }

        /* Fullscreen Controls Overlay */
        .camera-section.fullscreen .camera-controls {
            position: absolute !important;
            bottom: 30px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            width: 90% !important;
            max-width: 500px !important;
            z-index: 2000 !important;
            background: rgba(0, 0, 0, 0.4) !important;
            backdrop-filter: blur(8px) !important;
            border-radius: 30px !important;
            padding: 15px !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }

        .camera-section.fullscreen .capture-section {
            flex-direction: row !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
        }

        /* Adjust filter dropdown in fullscreen */
        .camera-section.fullscreen .filter-dropdown {
            position: absolute !important;
            top: -60px !important;
            left: 50% !important;
            transform: translateX(-50%) !important;
            background: rgba(0, 0, 0, 0.6) !important;
            color: white !important;
            padding: 8px 15px !important;
        }

        .camera-section.fullscreen.preview-mode .camera-controls,
        .camera-section.fullscreen.preview-mode .filter-dropdown {
            display: none !important;
            opacity: 0 !important;
            pointer-events: none !important;
        }

        .camera-section.fullscreen.preview-mode .photo-preview {
            z-index: 3000 !important;
            /* Ensure preview is on top */
        }

        .camera-section.fullscreen .filter-dropdown label {
            color: white !important;
        }

        .fullscreen-active .header-panel {
            display: none;
        }

        .fullscreen-active .sidebar {
            display: none;
        }

        .fullscreen-active .session-container {
            grid-template-columns: 1fr;
            padding: 0;
            gap: 0;
            height: 100vh;
            width: 100vw;
            max-width: 100vw;
            border-radius: 0px;
        }

        .fullscreen-active .camera-section {
            height: 100vh;
            border-radius: 0px;
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow-y: auto;
            gap: 15px;
        }

        .gallery-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 20px;
            min-height: 0;
        }

        .gallery-panel h3 {
            margin: 0 0 15px 0;
            font-family: 'Orbitron', sans-serif;
            color: var(--dark-blue);
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
            max-height: 400px;
            padding: 10px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            align-items: start;
            justify-items: center;
            grid-auto-rows: min-content;
        }

        .photo-gallery::-webkit-scrollbar {
            width: 6px;
        }

        .photo-gallery::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        .photo-gallery::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 3px;
        }


        /* Responsive grid for different screen sizes */
        @media (max-width: 400px) {
            .photo-gallery {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                padding: 6px;
            }

            .gallery-photo {
                max-width: 80px;
                max-height: 80px;
            }
        }

        /* Ensure 16:9 aspect ratio is maintained on all screen sizes */
        @media (max-width: 1200px) {
            .session-container {
                grid-template-columns: 1.8fr 1fr;
                gap: 8px;
                padding: 15px;
            }

            .safe-zone {
                margin: 8px;
            }
        }

        @media (max-width: 768px) {
            .session-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
                height: 100vh;
                padding: 10px;
            }

            .camera-section {
                order: 1;
                min-height: 40vh;
            }

            .sidebar {
                order: 2;
                flex-direction: row;
                gap: 10px;
                max-height: 30vh;
            }

            .gallery-panel,
            .selected-frames {
                flex: 1;
                min-height: 0;
            }
        }

        /* ========== MOBILE FIRST - PHONE ONLY ========== */

        /* Mobile phones (up to 480px) */
        @media (max-width: 480px) {

            /* ========== EDGE-TO-EDGE DESIGN ========== */
            html {
                touch-action: pan-y;
                overflow-x: hidden;
                overflow-y: auto;
            }

            body {
                padding: 0 !important;
                margin: 0 !important;
                overflow-x: hidden !important;
                overflow-y: auto !important;
                overscroll-behavior: auto;
                min-height: 100vh;
                height: auto !important;
                display: block !important;
            }

            .session-container {
                border-radius: 0 !important;
                padding: 0 !important;
                gap: 0 !important;
                height: auto !important;
                width: 100vw !important;
                max-width: 100vw !important;
                display: flex;
                flex-direction: column;
            }

            /* ========== COMPACT HEADER (Full Width) ========== */
            .header-panel {
                padding: 6px 16px !important;
                min-height: 56px;
                max-height: 56px;
                border-radius: 0 !important;
                width: 100% !important;
                box-sizing: border-box;
            }

            /* ========== CAMERA SECTION - 9:16 VERTICAL ========== */
            .camera-section {
                position: relative;
                order: 1;
                height: calc(100vh - 56px);
                max-height: calc(100vh - 56px);
                padding: 0 !important;
                border-radius: 0 !important;
                background: transparent !important;
            }

            /* Change aspect ratio to 9:16 for portrait mobile */
            .safe-zone {
                margin: 0;
                border-radius: 0;
                aspect-ratio: 9/16 !important;
                /* Vertical/portrait for mobile */
            }

            #camera-feed {
                border-radius: 0;
                aspect-ratio: 9/16 !important;
                /* Match safe-zone */
            }

            .session-info h1 {
                font-size: 0.85rem;
                letter-spacing: 0.5px;
            }

            .airline-name {
                font-size: 0.55rem;
            }

            .airline-sub {
                font-size: 0.4rem;
                letter-spacing: 1.5px;
            }

            .session-stats {
                gap: 8px;
            }

            .stat-value {
                font-size: 0.9rem;
            }

            .stat-label {
                font-size: 0.5rem;
            }

            .timer {
                font-size: 1.1rem;
            }

            /* ========== CAMERA SECTION - Full viewport height minus header ========== */
            .camera-section {
                position: relative;
                order: 1;
                height: calc(100vh - 56px);
                max-height: calc(100vh - 56px);
                padding: 0 !important;
                border-radius: 0 !important;
                background: transparent !important;
            }

            .safe-zone {
                margin: 0;
                border-radius: 0;
            }

            #camera-feed {
                border-radius: 0;
            }

            /* ========== FLOATING CONTROLS OVERLAY ========== */
            .camera-controls {
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
                padding: 16px 12px 24px;
                border-radius: 0;
                z-index: 100;
            }

            .capture-section {
                display: flex;
                flex-direction: row;
                flex-wrap: nowrap;
                gap: 6px;
                justify-content: space-between;
                align-items: center;
            }

            .btn {
                font-size: 0.45rem !important;
                /* Even smaller mobile font */
                padding: 5px 6px !important;
                /* Minimal padding */
                white-space: nowrap;
                letter-spacing: 0.5px;
            }

            .btn-capture {
                min-width: 70px !important;
                /* Further reduced width */
                font-size: 0.5rem !important;
                padding: 6px 10px !important;
                box-shadow: 0 4px 12px rgba(251, 159, 139, 0.5);
            }

            .btn:active {
                transform: scale(0.95);
                opacity: 0.9;
            }

            .btn-capture:active {
                transform: scale(0.95);
                box-shadow: 0 2px 8px rgba(251, 159, 139, 0.4);
            }

            .btn-fullscreen {
                padding: 5px 6px !important;
            }

            .finish-session-btn {
                padding: 5px 8px !important;
                font-size: 0.45rem !important;
            }

            .filter-dropdown {
                background: rgba(255, 255, 255, 0.9);
                padding: 3px 5px;
                border-radius: 20px;
                flex-shrink: 0;
            }

            .filter-dropdown label {
                font-size: 0.45rem;
                display: none;
            }

            .filter-dropdown select {
                font-size: 0.45rem !important;
                padding: 2px 5px !important;
                min-width: 50px !important;
                border: 1px solid rgba(0, 191, 255, 0.3);
                background: white;
                color: var(--primary-blue);
                border-radius: 20px;
                text-transform: uppercase;
                height: auto;
            }

            /* ========== MOBILE FULLSCREEN OVERRIDES ========== */
            .camera-section.fullscreen .camera-controls {
                min-height: auto !important;
                padding: 10px 15px !important;
                width: fit-content !important;
                max-width: 95% !important;
                bottom: 25px !important;
                border-radius: 30px !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                left: 50% !important;
                transform: translateX(-50%) !important;
            }

            .camera-section.fullscreen .capture-section {
                justify-content: center !important;
                width: auto !important;
                gap: 6px !important;
                flex-wrap: nowrap !important;
            }

            .camera-section.fullscreen .btn {
                font-size: 0.45rem !important;
                padding: 5px 6px !important;
            }

            .camera-section.fullscreen .btn-capture {
                font-size: 0.5rem !important;
                padding: 6px 10px !important;
                min-width: 70px !important;
            }

            .camera-section.fullscreen .finish-session-btn {
                padding: 5px 8px !important;
                font-size: 0.45rem !important;
            }

            .camera-section.fullscreen .filter-dropdown {
                top: -45px !important;
                /* Lower it closer to controls */
                padding: 4px 10px !important;
            }

            .camera-section.fullscreen .filter-dropdown select {
                font-size: 0.55rem !important;
                /* Increase size */
                padding: 3px 8px !important;
                min-width: 60px !important;
            }

            /* ========== SIDEBAR ========== */
            .sidebar {
                order: 2;
                flex-direction: column;
                gap: 0;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 20px 20px 0 0;
                padding: 12px;
                min-height: 400px;
                overflow-y: auto;
            }

            .gallery-panel,
            .selected-frames {
                background: transparent;
                border-radius: 0;
                padding: 8px 0;
            }

            .gallery-panel {
                min-height: 300px;
            }

            .selected-frames {
                min-height: 200px;
            }

            .gallery-panel h3,
            .selected-frames h3 {
                font-size: 0.9rem;
                margin-bottom: 12px;
            }

            /* ========== GALLERY - 3 Columns, Max 400px ========== */
            .photo-gallery {
                grid-template-columns: repeat(3, 1fr);
                max-height: 400px;
                min-height: 250px;
                max-width: 100%;
                gap: 6px;
                padding: 4px;
            }

            .gallery-photo {
                max-width: 100%;
                min-width: 0;
                aspect-ratio: 1;
            }

            /* ========== SELECTED FRAMES - Footer Section ========== */
            .selected-frames {
                order: 3;
                padding: 8px 0;
            }

            .frames-list {
                display: flex;
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                gap: 10px;
                padding: 4px 0;
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
            }

            .frame-item {
                min-width: fit-content;
                padding: 4px;
                background: rgba(0, 191, 255, 0.1);
                border-radius: 8px;
                scroll-snap-align: start;
            }

            /* Frame thumbnails larger for better visibility on mobile */
            .frame-thumbnail {
                width: 60px;
                height: 90px;
                object-fit: cover;
                border-radius: 8px;
                border: 2px solid rgba(0, 191, 255, 0.3);
            }

            .frame-item {
                min-width: 80px;
                padding: 8px;
                background: rgba(0, 191, 255, 0.15);
                border: 2px solid rgba(0, 191, 255, 0.2);
            }

            .frame-item span {
                display: block;
                font-size: 0.65rem;
                color: var(--dark-blue);
                font-weight: 600;
                text-align: center;
                margin-top: 4px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Fullscreen timer adjust */
            .fullscreen-timer .timer {
                font-size: 1.3rem;
            }
        }

        /* Small phones (up to 360px) */
        @media (max-width: 360px) {
            .header-panel {
                padding: 4px 12px !important;
                min-height: 52px;
                max-height: 52px;
            }

            .session-info h1 {
                font-size: 0.75rem;
            }

            .airline-name {
                font-size: 0.5rem;
            }

            .airline-sub {
                font-size: 0.35rem;
            }

            .session-stats {
                gap: 6px;
            }

            .stat-value {
                font-size: 0.8rem;
            }

            .stat-label {
                font-size: 0.45rem;
            }

            .timer {
                font-size: 1rem;
            }

            .camera-controls {
                padding: 12px 8px 20px;
            }

            .btn {
                font-size: 0.55rem;
                padding: 7px 8px;
            }

            .btn-capture {
                min-width: 75px;
                font-size: 0.6rem;
                padding: 8px 10px;
            }

            .filter-dropdown select {
                font-size: 0.55rem;
                min-width: 60px;
            }

            .sidebar {
                min-height: 350px;
            }

            .gallery-panel {
                min-height: 250px;
            }

            .selected-frames {
                min-height: 180px;
            }

            .photo-gallery {
                gap: 4px;
                padding: 3px;
                max-height: 320px;
                min-height: 200px;
            }

            .gallery-panel h3,
            .selected-frames h3 {
                font-size: 0.7rem;
            }
        }

        /* Very small phones (up to 320px) */
        @media (max-width: 320px) {
            .sidebar {
                min-height: 300px;
            }

            .gallery-panel {
                min-height: 200px;
            }

            .selected-frames {
                min-height: 160px;
            }

            .photo-gallery {
                gap: 3px;
                padding: 2px;
                max-height: 320px;
                min-height: 180px;
            }

            .btn-capture {
                min-width: 65px;
                font-size: 0.55rem;
                padding: 7px 8px;
            }

            .filter-dropdown select {
                min-width: 50px;
                font-size: 0.5rem;
            }

            .header-panel {
                padding: 4px 10px !important;
            }

            .session-info h1 {
                font-size: 0.7rem;
            }
        }

        /* Landscape orientation for mobile - keep 16:9 horizontal */
        @media (max-width: 480px) and (orientation: landscape) {
            .camera-section {
                position: relative;
                height: calc(100vh - 48px);
                max-height: calc(100vh - 48px);
            }

            /* Switch back to 16:9 for landscape */
            .safe-zone {
                aspect-ratio: 16/9 !important;
            }

            #camera-feed {
                aspect-ratio: 16/9 !important;
            }

            .header-panel {
                padding: 4px 16px !important;
                min-height: 48px;
                max-height: 48px;
            }

            .session-info h1 {
                font-size: 0.8rem;
            }

            .stat-value {
                font-size: 0.85rem;
            }

            .stat-label {
                font-size: 0.45rem;
            }

            .timer {
                font-size: 1rem;
            }

            .camera-controls {
                padding: 10px 12px 16px;
            }

            .sidebar {
                min-height: 250px;
            }

            .gallery-panel {
                min-height: 150px;
            }

            .selected-frames {
                min-height: 100px;
            }

            .photo-gallery {
                max-height: 180px;
                min-height: 120px;
            }

            .sidebar {
                padding: 8px;
            }
        }

        /* ========== END MOBILE FIRST ========== */


        .filter-controls {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 10px;
        }



        /* Dynamic CSS filters are applied via inline styles to support database-driven filter values */

        .gallery-photo {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s ease;
            max-width: 85px;
            max-height: 85px;
            width: 100%;
            height: auto;
        }

        .gallery-photo:hover {
            border-color: var(--primary-color);
        }

        .gallery-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-timestamp {
            position: absolute;
            bottom: 22px;
            left: 2px;
            right: 2px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            font-size: 0.6rem;
            text-align: center;
            padding: 1px 2px;
            border-radius: 3px;
        }

        .photo-actions {
            position: absolute;
            bottom: 2px;
            left: 2px;
            right: 2px;
            display: flex;
            gap: 2px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .gallery-photo:hover .photo-actions {
            opacity: 1;
        }

        .photo-action-btn {
            flex: 1;
            border: none;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            font-size: 0.7rem;
            padding: 2px;
            border-radius: 0 0 6px 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .photo-action-btn:hover {
            background: rgba(0, 0, 0, 0.9);
        }

        .save-btn {
            background: rgba(76, 175, 80, 0.8);
        }

        .delete-btn {
            background: rgba(244, 67, 54, 0.8);
        }

        /* Ensure capture button stays visible */
        .camera-controls {
            position: relative;
            display: flex;
            flex-direction: column;
            background: transparent;
            padding: 10px;
            border-radius: 20px;
        }

        .safe-zone-guidelines {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .guideline {
            position: absolute;
            background: rgba(0, 191, 255, 0.2);
            pointer-events: none;
        }

        .guideline.horizontal {
            left: 0;
            right: 0;
            height: 1px;
        }

        .guideline.vertical {
            top: 0;
            bottom: 0;
            width: 1px;
        }

        .selected-frames {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 0;
            flex: 1;
        }

        .selected-frames h3 {
            margin: 0 0 15px 0;
            font-family: 'Orbitron', sans-serif;
            color: var(--dark-blue);
            font-weight: 900;
            font-size: 1rem;
            text-transform: uppercase;
        }

        .frames-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
            overflow-y: auto;
            flex: 1;
        }

        .frame-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: rgba(0, 191, 255, 0.08);
            border-radius: 10px;
        }

        .frame-thumbnail {
            width: 50px;
            height: 75px;
            object-fit: contain;
            border-radius: 5px;
            border: 2px solid rgba(0, 191, 255, 0.3);
            flex-shrink: 0;
        }

        /* Custom scrollbar for frame list */
        .frames-list::-webkit-scrollbar {
            width: 8px;
        }

        .frames-list::-webkit-scrollbar-track {
            background: rgba(0, 191, 255, 0.05);
            border-radius: 10px;
        }

        .frames-list::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, var(--primary-blue), var(--secondary-blue));
            border-radius: 10px;
        }

        .frames-list::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-blue);
        }


        .finish-session-btn {
            background: linear-gradient(135deg, var(--primary-peach), var(--secondary-peach));
            color: white;
            border: none;
            padding: 10px 10px;
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            box-shadow: 0 4px 15px rgba(251, 159, 139, 0.4);
        }

        .finish-session-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(251, 159, 139, 0.5);
        }

        .session-expired {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .expired-modal {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }

        .custom-alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            /* Higher than other overlays */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .custom-alert-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .custom-alert-modal {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 350px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .custom-alert-overlay.show .custom-alert-modal {
            transform: translateY(0);
        }

        .expired-modal h2 {
            color: var(--warning-color);
            font-family: 'Orbitron', sans-serif;
            margin-top: 0;
            font-size: 1.8rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .custom-alert-modal h2 {
            color: var(--primary-peach);
            font-family: 'Orbitron', sans-serif;
            margin-top: 0;
            font-size: 1.5rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .custom-alert-modal p {
            color: #555;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .custom-alert-modal .btn-continue {
            background: linear-gradient(135deg, var(--primary-peach), var(--secondary-peach));
            font-size: 0.9rem;
            padding: 12px 25px;
        }

        /* Special styling for time extension alert */
        #time-extension-overlay .custom-alert-modal {
            border: 3px solid var(--success-color);
            background: linear-gradient(135deg, #f0fff0 0%, #e6ffe6 100%);
        }

        #time-extension-overlay .custom-alert-modal h2 {
            color: var(--success-color);
        }

        #time-extension-overlay .custom-alert-modal .btn-continue {
            background: var(--success-color);
        }

        .timer.extended {
            color: var(--success-color);
            animation: pulse-green 2s ease-in-out;
        }

        .timer.extended-time {
            color: var(--warning-color);
            border: 2px solid var(--warning-color);
            border-radius: 10px;
            padding: 5px 10px;
            background: rgba(255, 152, 0, 0.1);
        }

        @keyframes pulse-green {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Exact same animation as select-frame */
        html {
            height: 100%;
            overflow: hidden;
        }

        body {
            height: 100%;
            margin: 0;
            opacity: 1;
            transition: opacity 0.4s ease-out;
        }

        /* Mobile override for scrolling */
        @media (max-width: 480px) {
            html {
                overflow-y: auto;
            }

            body {
                overflow-y: auto;
                height: auto;
                min-height: 100%;
            }
        }

        body.fade-out {
            opacity: 0;
        }

        .session-container {
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
        }

        .session-container.content-fade-out {
            opacity: 0;
        }

        .session-container>* {
            opacity: 0;
            animation: innerElementFadeIn 0.5s ease-in 0.7s forwards;
        }

        @keyframes contentFadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes innerElementFadeIn {
            to {
                opacity: 1;
            }
        }

        /* Upload Progress Dialog */
        .upload-progress-dialog {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 191, 255, 0.3);
            z-index: 10000;
            min-width: 280px;
            border: 2px solid var(--primary-blue);
            display: none;
        }

        .upload-progress-dialog.active {
            display: block;
            animation: slideInRight 0.3s ease-out;
        }

        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .upload-progress-dialog h4 {
            margin: 0 0 15px 0;
            font-family: 'Orbitron', sans-serif;
            color: var(--primary-blue);
            font-size: 1rem;
            font-weight: 900;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-progress-bar-container {
            width: 100%;
            height: 10px;
            background: #e0e0e0;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .upload-progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-blue), var(--secondary-blue));
            width: 0%;
            border-radius: 10px;
            transition: width 0.3s ease;
        }

        .upload-progress-text {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }

        .upload-progress-dialog.upload-complete {
            border-color: var(--success-color);
        }

        .upload-progress-dialog.upload-complete h4 {
            color: var(--success-color);
        }

        .upload-progress-dialog.upload-complete .upload-progress-bar-fill {
            background: var(--success-color);
        }

        /* FORCE OVERRIDE for Mobile Frames - Modern Card Design */
        @media (max-width: 480px) {
            .selected-frames {
                min-height: 280px !important;
                padding: 10px 0 20px 0 !important;
                background: transparent !important;
            }

            .frames-list {
                padding: 10px 15px !important;
                gap: 15px !important;
            }

            .frame-item {
                min-width: 130px !important;
                background: rgba(255, 255, 255, 0.9) !important;
                border-radius: 16px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
                border: 1px solid rgba(255, 255, 255, 0.5) !important;
                padding: 10px !important;
                flex-direction: column !important;
                justify-content: flex-start !important;
                align-items: center !important;
                gap: 8px !important;
                transition: transform 0.2s ease !important;
            }

            .frame-item:active {
                transform: scale(0.98);
            }

            .frame-thumbnail {
                width: 110px !important;
                height: 165px !important;
                object-fit: cover !important;
                border-radius: 10px !important;
                border: none !important;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05) !important;
                background: #f0f0f0;
            }

            .frame-item span {
                font-size: 0.75rem !important;
                color: #333 !important;
                font-weight: 700 !important;
                text-transform: uppercase;
                letter-spacing: 0.5px;
                margin-top: 0 !important;
                text-align: center;
                width: 100%;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }
        }
    </style>
</head>

<body>

    <!-- Animated Clouds -->
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
    </div>

    <!-- Flying Plane -->
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

    <div class="session-container">
        <div class="header-panel">
            <div class="session-info">
                <div class="airline-branding">
                    <div class="airline-name">PHOTOBOOTH</div>
                    <div class="airline-sub">AIRWAYS</div>
                </div>
                <h1>Photo Session</h1>
            </div>
            <div class="session-stats">
                <div class="stat-item">
                    <div class="stat-value" id="photos-taken">0</div>
                    <div class="stat-label">Foto Diambil</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="photos-saved">0</div>
                    <div class="stat-label">Foto Tersimpan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value">
                        <?= $data['max_save_photos'] ?>
                    </div>
                    <div class="stat-label">Maksimal Simpan</div>
                </div>
                <div class="stat-item">
                    <div class="timer" id="session-timer">
                        <?= sprintf("%02d:%02d", floor($data['session_duration'] / 60), $data['session_duration'] % 60) ?>
                    </div>
                    <div class="stat-label">Waktu Tersisa</div>
                </div>
            </div>
        </div>

        <div class="camera-section">
            <div class="stat-item fullscreen-timer">
                <div class="timer" id="session-timer-fullscreen">
                    <?= sprintf("%02d:%02d", floor($data['session_duration'] / 60), $data['session_duration'] % 60) ?>
                </div>
                <div class="stat-label" style="color: white;">Waktu Tersisa</div>
            </div>
            <div class="safe-zone">
                <div class="safe-zone-overlay" id="safe-zone-overlay">
                    <!-- Safe zone boxes will be dynamically added here -->
                </div>
                <video id="camera-feed" autoplay playsinline></video>
                <canvas id="capture-canvas" style="display: none;"></canvas>

                <div class="photo-preview" id="photo-preview">
                    <img id="preview-image" class="preview-image" src="" alt="Preview">
                    <div class="preview-actions" id="preview-actions">
                        <button class="btn btn-delete" onclick="deletePhoto()"><svg width="1em" height="1em"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path
                                    d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                </path>
                            </svg>
                            HAPUS
                        </button>
                        <button class="btn btn-save" onclick="savePhoto()">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                                <polyline points="17 21 17 13 7 13 7 21"></polyline>
                                <polyline points="7 3 7 8 15 8"></polyline>
                            </svg>
                            SIMPAN
                        </button>
                    </div>
                </div>
            </div>

            <div class="camera-controls">
                <div class="capture-section">
                    <button class="btn btn-capture" id="capture-btn" onclick="capturePhoto()">
                        <svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z">
                            </path>
                            <circle cx="12" cy="13" r="4"></circle>
                        </svg>
                        AMBIL FOTO
                    </button>
                    <button class="btn btn-fullscreen" id="fullscreen-btn">
                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="15 3 21 3 21 9"></polyline>
                            <polyline points="9 21 3 21 3 15"></polyline>
                            <line x1="21" y1="3" x2="14" y2="10"></line>
                            <line x1="3" y1="21" x2="10" y2="14"></line>
                        </svg>
                        FULLSCREEN
                    </button>
                    <button class="finish-session-btn" id="finish-session-btn" onclick="finishSession()">
                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        SELESAI
                    </button>
                    <div class="filter-dropdown">
                        <label for="camera-filter">
                            <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="4" y1="21" x2="4" y2="14"></line>
                                <line x1="4" y1="10" x2="4" y2="3"></line>
                                <line x1="12" y1="21" x2="12" y2="12"></line>
                                <line x1="12" y1="8" x2="12" y2="3"></line>
                                <line x1="20" y1="21" x2="20" y2="16"></line>
                                <line x1="20" y1="12" x2="20" y2="3"></line>
                                <line x1="1" y1="14" x2="7" y2="14"></line>
                                <line x1="9" y1="8" x2="15" y2="8"></line>
                                <line x1="17" y1="16" x2="23" y2="16"></line>
                            </svg>
                            FILTER:
                        </label>
                        <select id="camera-filter" onchange="applyFilter(this.value)">
                            <option value="none">Normal</option>
                            <?php if (isset($data['filters']) && is_array($data['filters'])): ?>
                                <?php foreach ($data['filters'] as $filter): ?>
                                    <option value="<?= htmlspecialchars($filter->path ?? 'none') ?>"
                                        data-filter-name="<?= htmlspecialchars($filter->name) ?>">
                                        <?= htmlspecialchars($filter->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="gallery-panel">
                <h3 id="gallery-title">
                    <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                    GALERI FOTO SESI (0/<?= $data['max_save_photos'] ?>)
                </h3>
                <div class="photo-gallery" id="photo-gallery">
                    <!-- Saved photos will appear here -->
                </div>
            </div>


            <div class="selected-frames">
                <h3>Frame Terpilih</h3>
                <div class="frames-list">
                    <?php foreach ($data['frames'] as $frame): ?>
                        <div class="frame-item">
                            <img src="<?= URLROOT . $frame->path ?>" alt="<?= $frame->name ?>" class="frame-thumbnail">
                            <span><?= $frame->name ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="custom-alert-overlay" id="custom-alert-overlay">
        <div class="custom-alert-modal">
            <h2 id="custom-alert-title">Peringatan!</h2>
            <p id="custom-alert-message"></p>
            <button class="btn btn-continue" onclick="hideCustomAlert()">Oke</button>
        </div>
    </div>

    <div class="session-expired" id="session-expired">
        <div class="expired-modal">
            <h2>
                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                WAKTU HABIS!
            </h2>
            <p>Sesi foto Anda telah berakhir. Mari lanjut ke tahap selanjutnya untuk menata foto-foto terbaik Anda!</p>
            <button class="btn btn-continue" onclick="finishSession()">Lanjutkan</button>
        </div>
    </div>

    <div class="custom-alert-overlay" id="time-extension-overlay">
        <div class="custom-alert-modal">
            <h2 id="extension-alert-title">
                <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                WAKTU DIPERPANJANG!
            </h2>
            <p id="extension-alert-message"></p>
            <button class="btn btn-continue" onclick="hideTimeExtensionAlert()">Lanjutkan Foto</button>
        </div>
    </div>

    <!-- Upload Progress Dialog -->
    <div class="upload-progress-dialog" id="upload-progress-dialog">
        <h4>
            <svg class="spin" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
            </svg>
            MENGUPLOAD FOTO...
        </h4>
        <div class="upload-progress-bar-container">
            <div class="upload-progress-bar-fill" id="upload-progress-bar-fill"></div>
        </div>
        <div class="upload-progress-text" id="upload-progress-text">0%</div>
    </div>

    <script>
        // Session data
        const sessionId = <?= $data['session']->id ?>;
        const sessionDuration = <?= $data['session_duration'] ?>;
        const maxSavePhotos = <?= $data['max_save_photos'] ?>;
        <?php
        $totalSlots = 0;
        foreach ($data['frames'] as $frame) {
            $slotCoords = json_decode($frame->slot_coordinates, true);
            if (is_array($slotCoords)) {
                $totalSlots += count($slotCoords);
            }
        }
        ?>
        const numFrameSlots = <?= $totalSlots ?>;

        let timeRemaining = sessionDuration;
        let photosTaken = 0;
        let photosSaved = 0;
        let currentPhotoBlob = null;
        let savedPhotos = [];
        let isExtendedTime = false; // Track if we're in extended time

        // DOM elements
        const cameraFeed = document.getElementById('camera-feed');
        const captureCanvas = document.getElementById('capture-canvas');
        const photoPreview = document.getElementById('photo-preview');
        const previewImage = document.getElementById('preview-image');
        const photosTakenEl = document.getElementById('photos-taken');
        const photosSavedEl = document.getElementById('photos-saved');
        const timerEl = document.getElementById('session-timer');
        const timerElFullscreen = document.getElementById('session-timer-fullscreen');
        const photoGallery = document.getElementById('photo-gallery');
        const finishBtn = document.getElementById('finish-session-btn');

        // Initialize camera with aspect ratio based on device orientation
        async function initCamera() {
            try {
                // Use 9:16 for mobile portrait, 16:9 for desktop/tablet/landscape
                const isMobilePortrait = window.innerWidth <= 480 && window.innerHeight > window.innerWidth;
                const targetAspect = isMobilePortrait ? 9 / 16 : 16 / 9;

                // Request camera with appropriate aspect ratio
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        width: { ideal: 1920, min: 1280 },
                        height: { ideal: 1080, min: 720 },
                        aspectRatio: { ideal: targetAspect },
                        facingMode: 'user'
                    }
                });
                cameraFeed.srcObject = stream;

                // Ensure camera starts playing
                cameraFeed.onloadedmetadata = () => {
                    cameraFeed.play().then(() => {
                        const isMobilePortrait = window.innerWidth <= 480 && window.innerHeight > window.innerWidth;
                        const aspectStr = isMobilePortrait ? '9:16' : '16:9';
                        console.log(`Camera started successfully with ${aspectStr} aspect ratio`);
                        console.log(`Camera resolution: ${cameraFeed.videoWidth}x${cameraFeed.videoHeight}`);

                        // Calculate safe zone after container adjustment
                        setTimeout(() => {
                            calculateSafeZone();
                        }, 500);
                    }).catch(err => {
                        console.error('Error starting camera playback:', err);
                    });
                };

                // Recalculate when camera feed loads
                cameraFeed.addEventListener('loadeddata', () => {
                    setTimeout(() => {
                        calculateSafeZone();
                    }, 300);
                });

            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera dan mendukung aspek rasio 9:16 atau 16:9.');
            }
        }

        // Capture photo with 3-second countdown
        function capturePhoto() {
            // Prevent capture if max photos reached
            if (photosSaved >= maxSavePhotos) {
                showCustomAlert('Anda telah mencapai batas maksimal foto. Tekan tombol SELESAI untuk melanjutkan.');
                return;
            }

            // Only auto-prevent capture during extended time when requirement is met
            if (isExtendedTime && photosSaved >= numFrameSlots) {
                showCustomAlert('Anda sudah mencapai jumlah foto yang dibutuhkan untuk frame. Tekan tombol SELESAI untuk melanjutkan.');
                return;
            }

            // Prevent multiple captures during preview or countdown
            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn.disabled || photoPreview.style.display === 'flex') {
                return;
            }

            // Start 3-second countdown
            captureBtn.disabled = true;
            let countdown = 2;

            const countdownSvg = `<svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg>`;

            // Show first countdown number immediately
            captureBtn.innerHTML = `${countdownSvg} ${countdown}`;
            captureBtn.style.transform = 'scale(1)';
            countdown--;

            const countdownInterval = setInterval(() => {
                captureBtn.innerHTML = `${countdownSvg} ${countdown}`;
                captureBtn.style.transform = 'scale(1)';

                countdown--;

                if (countdown < 0) {
                    clearInterval(countdownInterval);

                    // Flash effect
                    document.body.style.backgroundColor = 'white';
                    setTimeout(() => {
                        document.body.style.backgroundColor = '';
                    }, 100);

                    // Take the actual photo
                    takePhotoNow(captureBtn);
                }
            }, 1000);
        }

        // Actually capture the photo after countdown
        function takePhotoNow(captureBtn) {
            const canvas = captureCanvas;
            const context = canvas.getContext('2d');

            canvas.width = cameraFeed.videoWidth;
            canvas.height = cameraFeed.videoHeight;

            // Apply current filter to context before drawing
            const currentFilterValue = document.getElementById('camera-filter').value;
            if (currentFilterValue !== 'none' && currentFilterValue) {
                context.filter = getCanvasFilter(currentFilterValue);
            } else {
                context.filter = 'none';
            }

            context.drawImage(cameraFeed, 0, 0);

            canvas.toBlob(blob => {
                currentPhotoBlob = blob;
                const url = URL.createObjectURL(blob);
                const previewActions = document.getElementById('preview-actions');

                // Reset buttons to Hapus/Simpan for new photos
                previewActions.innerHTML = `
                    <button class="btn btn-delete" onclick="deletePhoto()">
                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                        HAPUS
                    </button>
                    <button class="btn btn-save" onclick="savePhoto()">
                        <svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        SIMPAN
                    </button>
                `;

                previewImage.src = url;
                photoPreview.style.display = 'flex';

                // Add preview mode class to camera section
                document.querySelector('.camera-section').classList.add('preview-mode');

                photosTaken++;
                updateStats();

                // Reset button style and keep disabled while preview is showing
                captureBtn.style.fontSize = '';
                captureBtn.style.transform = '';
                captureBtn.innerHTML = `<svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> FOTO DIAMBIL`;
            }, 'image/png', 1.0);
        }

        // Convert CSS filter to canvas filter
        function getCanvasFilter(filterValue) {
            if (filterValue === 'none' || !filterValue) {
                return 'none';
            }

            // If it's already a CSS filter property, use it directly
            if (filterValue.includes('(')) {
                return filterValue;
            }

            // Fallback for legacy hardcoded filters
            switch (filterValue) {
                case 'sepia': return 'sepia(1)';
                case 'grayscale': return 'grayscale(1)';
                case 'blur': return 'blur(2px)';
                case 'brightness': return 'brightness(1.3)';
                case 'contrast': return 'contrast(1.5)';
                case 'saturate': return 'saturate(1.8)';
                case 'hue-rotate': return 'hue-rotate(90deg)';
                case 'invert': return 'invert(1)';
                case 'vintage': return 'sepia(0.5) contrast(1.2) brightness(1.1)';
                default: return filterValue;
            }
        }

        // Delete current photo and auto-capture next
        function deletePhoto() {
            photoPreview.style.display = 'none';
            // Remove preview mode class
            document.querySelector('.camera-section').classList.remove('preview-mode');

            currentPhotoBlob = null;
            URL.revokeObjectURL(previewImage.src);

            // Re-enable capture button and auto-start next capture
            const captureBtn = document.getElementById('capture-btn');
            if (!isMaxPhotosReached() && !viewingGalleryPhoto) {
                captureBtn.disabled = false;
                captureBtn.style.opacity = '1';
                captureBtn.innerHTML = `<svg class="spin" width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> MEMULAI...`;

                // Auto-start next capture
                setTimeout(() => {
                    if (!isMaxPhotosReached() && !viewingGalleryPhoto) {
                        capturePhoto();
                    } else {
                        captureBtn.innerHTML = `<svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> AMBIL FOTO`;
                    }
                }, 1500);
            } else {
                captureBtn.disabled = false;
                captureBtn.style.opacity = '1';
                captureBtn.innerHTML = `<svg width="1.2em" height="1.2em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path><circle cx="12" cy="13" r="4"></circle></svg> AMBIL FOTO`;
            }
        }

        // Queue system for robust sequential uploads
        let pendingUploads = 0;
        let uploadQueue = [];
        let isUploading = false;

        function savePhoto() {
            if (!currentPhotoBlob || photosSaved >= maxSavePhotos) return;

            const blobToUpload = currentPhotoBlob;
            pendingUploads++;

            // Add to queue
            uploadQueue.push({
                blob: blobToUpload,
                retryCount: 0
            });

            // IMMEDIATELY clear preview and re-enable capture for next shot
            photoPreview.style.display = 'none';
            document.querySelector('.camera-section').classList.remove('preview-mode');
            currentPhotoBlob = null;

            const captureBtn = document.getElementById('capture-btn');
            if (!isMaxPhotosReached() && !viewingGalleryPhoto) {
                captureBtn.disabled = false;
                captureBtn.style.opacity = '1';
                captureBtn.innerHTML = '⏳ Memulai...';

                // Start next capture immediately without waiting for upload!
                setTimeout(() => {
                    if (!isMaxPhotosReached() && !viewingGalleryPhoto) {
                        capturePhoto();
                    } else {
                        captureBtn.innerHTML = '📸 Ambil Foto';
                    }
                }, 1500); // Short delay for UI update
            }

            // Process queue if not already uploading
            processUploadQueue();
        }

        function processUploadQueue() {
            if (isUploading || uploadQueue.length === 0) return;

            isUploading = true;
            const currentItem = uploadQueue[0];

            // Show/update dialog
            const uploadDialog = document.getElementById('upload-progress-dialog');
            const progressBarFill = document.getElementById('upload-progress-bar-fill');
            const progressText = document.getElementById('upload-progress-text');

            if (!uploadDialog.classList.contains('active')) {
                uploadDialog.classList.add('active');
            }

            uploadDialog.querySelector('h4').innerHTML = `<svg class="spin" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> UPLOAD (${pendingUploads} ANTRIAN)...`;
            progressBarFill.style.width = '0%';
            progressText.textContent = '0%';

            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('photo', currentItem.blob, 'photo.png');

            const xhr = new XMLHttpRequest();

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBarFill.style.width = percentComplete + '%';
                    progressText.textContent = percentComplete + '%';
                }
            });

            xhr.addEventListener('load', () => {
                if (xhr.status === 200) {
                    try {
                        const data = JSON.parse(xhr.responseText);
                        if (data.success) {
                            // Success! Remove from queue
                            uploadQueue.shift();
                            pendingUploads--;

                            savedPhotos.push(data.photo);
                            photosSaved++;
                            updateStats();
                            addToGallery(data.photo);

                            if (pendingUploads === 0) {
                                uploadDialog.classList.add('upload-complete');
                                uploadDialog.querySelector('h4').innerHTML = `<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> SEMUA SELESAI!`;
                                progressText.textContent = '100%';

                                setTimeout(() => {
                                    if (pendingUploads === 0) uploadDialog.classList.remove('active', 'upload-complete');
                                }, 1500);
                            }

                            // Check max photos
                            if (isExtendedTime && photosSaved >= numFrameSlots) {
                                autoFinishExtendedTime();
                            } else if (photosSaved >= maxSavePhotos) {
                                autoFinishMaxTime();
                            }

                            isUploading = false;
                            processUploadQueue(); // Process next in queue
                        } else {
                            handleUploadError(currentItem, data.error || data.message || 'Unknown error');
                        }
                    } catch (e) {
                        handleUploadError(currentItem, 'Invalid server response');
                    }
                } else {
                    handleUploadError(currentItem, `HTTP Error ${xhr.status}`);
                }
            });

            xhr.addEventListener('error', () => handleUploadError(currentItem, 'Network Error'));
            xhr.addEventListener('timeout', () => handleUploadError(currentItem, 'Timeout'));

            xhr.timeout = 180000; // 3 minutes per photo timeout
            xhr.open('POST', '<?= URLROOT ?>/photo/save-session-photo');
            xhr.send(formData);
        }

        function handleUploadError(item, errorMsg) {
            if (item.retryCount < 3) {
                item.retryCount++;
                console.warn(`Upload failed (${errorMsg}). Retrying (${item.retryCount}/3)...`);
                
                // Show retry status
                const uploadDialog = document.getElementById('upload-progress-dialog');
                if (uploadDialog) {
                    uploadDialog.querySelector('h4').innerHTML = `<svg class="spin" width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"></path></svg> MENGULANG UPLOAD (${item.retryCount}/3)...`;
                }

                isUploading = false;
                setTimeout(processUploadQueue, 2000); // Wait 2 secs before retry
            } else {
                alert(`Gagal upload setelah 3 percobaan: ${errorMsg}\nCobalah periksa koneksi internet Anda.`);
                uploadQueue.shift(); // Drop it
                pendingUploads--;
                isUploading = false;

                if (pendingUploads === 0) {
                    document.getElementById('upload-progress-dialog').classList.remove('active');
                }
                processUploadQueue();
            }
        }

        function autoFinishExtendedTime() {
            const captureBtn = document.getElementById('capture-btn');
            const finishBtn = document.getElementById('finish-session-btn');
            captureBtn.disabled = true;
            captureBtn.style.opacity = '0.5';
            captureBtn.innerHTML = '✅ Cukup';
            finishBtn.innerHTML = `<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> SELESAI SESI`;
            finishBtn.style.background = 'var(--success-color)';
            finishBtn.style.animation = 'pulse 1s infinite';
            setTimeout(() => { showCustomAlert(`Selamat! Anda telah mencapai ${numFrameSlots} foto yang dibutuhkan. Tekan tombol SELESAI untuk melanjutkan ke layout.`); }, 1000);
        }

        function autoFinishMaxTime() {
            const captureBtn = document.getElementById('capture-btn');
            const finishBtn = document.getElementById('finish-session-btn');
            captureBtn.disabled = true;
            captureBtn.style.opacity = '0.5';
            captureBtn.innerHTML = '📸 Maksimal';
            finishBtn.innerHTML = `<svg width="1em" height="1em" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg> SELESAI SESI`;
            finishBtn.style.background = 'var(--success-color)';
            finishBtn.style.animation = 'pulse 1s infinite';
            setTimeout(() => { showCustomAlert(`Anda telah menyimpan ${maxSavePhotos} foto maksimal. Tekan tombol SELESAI untuk melanjutkan ke layout.`); }, 1000);
        }

        // Add photo to gallery
        function addToGallery(photo) {
            const timestamp = Date.now();
            const photoElement = document.createElement('div');
            photoElement.className = 'gallery-photo';
            photoElement.dataset.timestamp = timestamp;
            photoElement.dataset.saved = 'true';
            photoElement.dataset.photoId = photo.id;

            // Ensure we have the right path format
            const imagePath = photo.file_path || photo.path || '';
            const imageUrl = imagePath.startsWith('/') ? '<?= URLROOT ?>' + imagePath : '<?= URLROOT ?>/' + imagePath;

            photoElement.innerHTML = `
                <img src="${imageUrl}" alt="Session Photo" onerror="this.alt='Failed to load image'; this.style.background='#f0f0f0'; this.style.color='#999';">
                <div class="photo-timestamp">${new Date().toLocaleTimeString()}</div>
                <div class="photo-actions">
                    <button class="photo-action-btn delete-btn" onclick="deleteFromGallery(this, ${photo.id})" title="Hapus">🗑️</button>
                    </div>
            `;

            // Add click handler to show preview (skip if clicking on action buttons)
            photoElement.addEventListener('click', (e) => {
                if (!e.target.classList.contains('photo-action-btn')) {
                    // Set flag to prevent auto-capture when returning to camera
                    viewingGalleryPhoto = true;

                    const previewImage = document.getElementById('preview-image');
                    const photoPreview = document.getElementById('photo-preview');
                    const previewActions = document.getElementById('preview-actions');

                    // Show the saved photo
                    previewImage.src = imageUrl;
                    photoPreview.style.display = 'flex';

                    // Add preview mode class
                    document.querySelector('.camera-section').classList.add('preview-mode');

                    // Change buttons to "Kembali" only for saved photos
                    previewActions.innerHTML = '<button class="btn btn-continue" onclick="returnToCamera()">🔙 Kembali ke Kamera</button>';

                    // Clear any current photo blob since we're viewing a saved photo
                    currentPhotoBlob = null;
                }
            });

            photoGallery.appendChild(photoElement);
            allPhotos.push({ url: imageUrl, timestamp, saved: true, data: photo });

            // Update gallery title
            updateGalleryTitle();

            // Show finish button if we have saved photos
            if (photosSaved > 0) {
                finishBtn.style.display = 'inline-block';
            }
        }

        // Update statistics
        function updateStats() {
            photosTakenEl.textContent = photosTaken;
            photosSavedEl.textContent = photosSaved;
            updateDeleteButtonStates();
        }

        // Helper function to check if max photos reached
        function isMaxPhotosReached() {
            return photosSaved >= maxSavePhotos || (isExtendedTime && photosSaved >= numFrameSlots);
        }

        // Update delete button visual states
        function updateDeleteButtonStates() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => {
                // Case 1: Photos < minimum OR deleting would go < minimum
                if (photosSaved < numFrameSlots || (photosSaved - 1) < numFrameSlots) {
                    btn.style.opacity = '0.3';
                    if (photosSaved < numFrameSlots) {
                        btn.title = `Tidak boleh hapus - baru ${photosSaved} foto, minimal ${numFrameSlots}`;
                    } else {
                        btn.title = `Tidak boleh hapus - akan jadi ${photosSaved - 1} foto, minimal ${numFrameSlots}`;
                    }
                } else {
                    // Safe to delete
                    btn.style.opacity = '1';
                    btn.title = 'Hapus foto ini';
                }
            });
        }

        // Timer functionality
        function startTimer() {
            const timer = setInterval(() => {
                timeRemaining--;

                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (timerEl) timerEl.textContent = timeString;
                if (timerElFullscreen) timerElFullscreen.textContent = timeString;

                // Warning when 1 minute remaining
                if (timeRemaining <= 60) {
                    if (timerEl) timerEl.classList.add('warning');
                    if (timerElFullscreen) {
                        timerElFullscreen.classList.add('warning');
                    }
                }

                // Session expired
                if (timeRemaining <= 0) {
                    clearInterval(timer);

                    // Check if user has enough photos for frame slots
                    if (photosSaved < numFrameSlots) {
                        // Extend time by 3 minutes if insufficient photos
                        const extensionTime = 180; // 3 minutes in seconds
                        timeRemaining = extensionTime;
                        isExtendedTime = true; // Mark as extended time

                        console.log(`Time extended: Need ${numFrameSlots} photos, have ${photosSaved}, extending by ${extensionTime / 60} minutes`);

                        // Show extension notification with special modal
                        const photosNeeded = numFrameSlots - photosSaved;
                        showTimeExtensionAlert(`Waktu diperpanjang ${extensionTime / 60} menit! Anda perlu ${photosNeeded} foto lagi untuk melengkapi semua frame slot.`);

                        // Restart timer
                        startTimer();

                        // Remove warning styling and add extended styling
                        if (timerEl) {
                            timerEl.classList.remove('warning');
                            timerEl.classList.add('extended');
                            setTimeout(() => {
                                timerEl.classList.remove('extended');
                                timerEl.classList.add('extended-time'); // Persistent extended time indicator
                            }, 3000);
                        }
                        if (timerElFullscreen) {
                            timerElFullscreen.classList.remove('warning');
                            timerElFullscreen.classList.add('extended');
                            setTimeout(() => {
                                timerElFullscreen.classList.remove('extended');
                                timerElFullscreen.classList.add('extended-time'); // Persistent extended time indicator
                            }, 3000);
                        }
                    } else {
                        // Show normal session expired modal if enough photos
                        document.getElementById('session-expired').style.display = 'flex';
                    }
                }
            }, 1000);
        }

        function showCustomAlert(message) {
            const overlay = document.getElementById('custom-alert-overlay');
            const msgElement = document.getElementById('custom-alert-message');
            msgElement.textContent = message;
            overlay.classList.add('show');
        }

        function hideCustomAlert() {
            const overlay = document.getElementById('custom-alert-overlay');
            overlay.classList.remove('show');
        }

        function showTimeExtensionAlert(message) {
            const overlay = document.getElementById('time-extension-overlay');
            const msgElement = document.getElementById('extension-alert-message');
            msgElement.textContent = message;
            overlay.classList.add('show');
        }

        function hideTimeExtensionAlert() {
            const overlay = document.getElementById('time-extension-overlay');
            overlay.classList.remove('show');
        }

        // Finish session
        async function finishSession() {
            if (photosSaved < numFrameSlots) {
                showCustomAlert('Simpan minimal ' + numFrameSlots + ' foto sebelum melanjutkan!');
                return;
            }

            // Wait for all pending uploads to complete before proceeding
            if (pendingUploads > 0) {
                const uploadDialog = document.getElementById('upload-progress-dialog');
                const dialogTitle = uploadDialog.querySelector('h4');
                const progressText = document.getElementById('upload-progress-text');

                dialogTitle.innerHTML = `⏳ Menunggu ${pendingUploads} upload...`;
                progressText.textContent = `Pending: ${pendingUploads}`;

                console.log(`Waiting for ${pendingUploads} uploads to complete...`);

                await waitForAllUploads(30); // Wait max 30 seconds for all uploads

                // Hide dialog after all uploads complete
                uploadDialog.classList.remove('active');
                dialogTitle.innerHTML = '⏳ Mengupload Foto...';
                progressText.textContent = '0%';
            }

            // Same fade-out animation as select-frame
            document.body.classList.add('fade-out');

            // Update session status
            fetch('<?= URLROOT ?>/photo/complete-session', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id: sessionId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Allow navigation for successful session completion
                        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
                            allowNavigation = true;
                        <?php endif; ?>

                        // Same timing as select-frame (500ms)
                        setTimeout(() => {
                            window.location.href = `<?= URLROOT ?>/photo/ai-enhance/${sessionId}`;
                        }, 500);
                    }
                });
        }

        // Function to wait for all pending uploads to complete
        async function waitForAllUploads(maxWaitSeconds = 30) {
            const startTime = Date.now();
            const maxWaitMs = maxWaitSeconds * 1000;

            return new Promise((resolve) => {
                const checkInterval = setInterval(() => {
                    if (pendingUploads === 0) {
                        clearInterval(checkInterval);
                        console.log('All uploads completed!');
                        resolve(true);
                    } else {
                        const elapsed = Date.now() - startTime;
                        if (elapsed > maxWaitMs) {
                            clearInterval(checkInterval);
                            console.warn(`Timeout waiting for uploads. Still pending: ${pendingUploads}`);
                            resolve(false); // Timeout but continue anyway
                        } else {
                            // Update dialog with progress
                            const uploadDialog = document.getElementById('upload-progress-dialog');
                            if (uploadDialog && uploadDialog.classList.contains('active')) {
                                const progressText = document.getElementById('upload-progress-text');
                                progressText.textContent = `Pending: ${pendingUploads} (${Math.round((maxWaitMs - elapsed) / 1000)}s)`;
                            }
                        }
                    }
                }, 100); // Check every 100ms
            });
        }

        // Filter gallery photos
        let allPhotos = [];
        let galleryFilter = 'all';

        function filterPhotos(filter) {
            galleryFilter = filter;

            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');

            const photoGallery = document.getElementById('photo-gallery');
            const photos = photoGallery.querySelectorAll('.gallery-photo');

            photos.forEach(photo => {
                const timestamp = parseInt(photo.dataset.timestamp || '0');
                const saved = photo.dataset.saved === 'true';
                const now = Date.now();
                const isRecent = (now - timestamp) < 60000; // Last minute

                let show = false;
                switch (filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'saved':
                        show = saved;
                        break;
                    case 'recent':
                        show = isRecent;
                        break;
                }

                photo.style.display = show ? 'block' : 'none';
            });
        }


        function updateGalleryTitle() {
            const saved = document.querySelectorAll('.gallery-photo[data-saved="true"]').length;
            const total = maxSavePhotos;
            document.getElementById('gallery-title').textContent = `📸 Galeri Foto Sesi (${saved}/${total})`;
        }

        function calculateSafeZone() {
            const safeZoneOverlay = document.getElementById('safe-zone-overlay');
            const frames = <?= json_encode($data['frames']) ?>;
            let allSlots = [];

            // Collect all slots from selected frames
            frames.forEach(frame => {
                try {
                    // Make sure slot_coordinates is a string before parsing
                    if (typeof frame.slot_coordinates !== 'string') {
                        console.warn('Slot coordinates for frame is not a string:', frame.name);
                        return;
                    }
                    const slotCoords = JSON.parse(frame.slot_coordinates || '[]');

                    if (Array.isArray(slotCoords)) {
                        slotCoords.forEach(slot => {
                            // Validate that the slot has the required properties
                            if (slot && typeof slot === 'object' && slot.width && slot.height) {
                                allSlots.push({
                                    width: parseFloat(slot.width),
                                    height: parseFloat(slot.height)
                                });
                            } else {
                                console.warn('Invalid slot data found in frame:', frame.name, slot);
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error parsing slot coordinates for frame:', frame.name, e);
                }
            });

            if (allSlots.length === 0) {
                console.log("No valid slots found, showing basic safe zone.");
                showBasicSafeZone();
                return;
            }

            // Calculate unified safe zone that accommodates all slots
            const unifiedSafeZone = calculateUnifiedSafeZone(allSlots);
            updateSafeZoneDisplay(unifiedSafeZone);
        }

        function showBasicSafeZone() {
            const safeZoneOverlay = document.getElementById('safe-zone-overlay');
            safeZoneOverlay.innerHTML = '';

            // Create basic safe zone (80% of container, centered)
            const safeZoneClear = document.createElement('div');
            safeZoneClear.className = 'safe-zone-clear';
            safeZoneClear.style.cssText = `
                left: 10%;
                top: 10%;
                width: 80%;
                height: 80%;
            `;

            safeZoneOverlay.appendChild(safeZoneClear);

            const instruction = document.createElement('div');
            instruction.className = 'safe-zone-instruction';
            instruction.textContent = 'Pastikan subjek berada di dalam area ini untuk hasil foto terbaik';
            safeZoneOverlay.appendChild(instruction);
        }

        function updateSafeZoneDisplay(unifiedSafeZone) {
            const overlay = document.getElementById('safe-zone-overlay');
            if (!overlay) return;

            // Clear existing zones
            overlay.innerHTML = '';

            if (!unifiedSafeZone || !unifiedSafeZone.hasValidIntersection) {
                showBasicSafeZone();
                return;
            }

            // Create shaded areas around the safe zone
            const topShade = document.createElement('div');
            topShade.className = 'safe-zone-shade';
            topShade.style.cssText = `top: 0; left: 0; right: 0; height: ${unifiedSafeZone.top}%;`;

            const bottomShade = document.createElement('div');
            bottomShade.className = 'safe-zone-shade';
            bottomShade.style.cssText = `bottom: 0; left: 0; right: 0; height: ${100 - (unifiedSafeZone.top + unifiedSafeZone.height)}%;`;

            const leftShade = document.createElement('div');
            leftShade.className = 'safe-zone-shade';
            leftShade.style.cssText = `top: ${unifiedSafeZone.top}%; left: 0; width: ${unifiedSafeZone.left}%; height: ${unifiedSafeZone.height}%;`;

            const rightShade = document.createElement('div');
            rightShade.className = 'safe-zone-shade';
            rightShade.style.cssText = `top: ${unifiedSafeZone.top}%; right: 0; width: ${100 - (unifiedSafeZone.left + unifiedSafeZone.width)}%; height: ${unifiedSafeZone.height}%;`;

            overlay.append(topShade, bottomShade, leftShade, rightShade);

            // Create the safe zone indicator
            const safeZoneClear = document.createElement('div');
            safeZoneClear.className = 'safe-zone-clear';
            safeZoneClear.style.cssText = `
                left: ${unifiedSafeZone.left}%;
                top: ${unifiedSafeZone.top}%;
                width: ${unifiedSafeZone.width}%;
                height: ${unifiedSafeZone.height}%;
            `;

            const label = document.createElement('div');
            label.className = 'safe-zone-label';
            label.textContent = `Zona Aman (${unifiedSafeZone.cameraUsage.toFixed(0)}% Terlihat)`;
            safeZoneClear.appendChild(label);

            overlay.appendChild(safeZoneClear);

            console.log(`Safe zone updated based on 'contain' logic.`);
        }

        function calculateUnifiedSafeZone(allSlots) {
            if (!allSlots || allSlots.length === 0) {
                // Jika tidak ada slot, tampilkan safe zone default yang lebih masuk akal.
                return { left: 10, top: 10, width: 80, height: 80, hasValidIntersection: true, cameraUsage: 64 };
            }

            // Determine camera aspect ratio based on orientation
            const isMobilePortrait = window.innerWidth <= 480 && window.innerHeight > window.innerWidth;
            const cameraAspect = isMobilePortrait ? 9 / 16 : 16 / 9; // 9:16 vertical for mobile portrait, 16:9 horizontal otherwise
            const frameAspect = 1 / 3;   // Rasio aspek photostrip (tinggi)

            // Area aman dimulai dari 100% tampilan kamera, yang kemudian akan diperkecil.
            let safeIntersection = { left: 0, top: 0, right: 100, bottom: 100 };

            allSlots.forEach((slot) => {
                const slotWidthPercent = slot.width;
                const slotHeightPercent = slot.height;

                if (isNaN(slotWidthPercent) || isNaN(slotHeightPercent) || slotWidthPercent <= 0 || slotHeightPercent <= 0) {
                    return; // Lewati slot yang tidak valid
                }

                // --- INI BAGIAN PENTING YANG DIPERBAIKI ---
                // Menghitung rasio aspek slot yang sebenarnya dengan memperhitungkan rasio aspek frame.
                // Kita gunakan unit proporsional: anggap lebar frame = 1, maka tingginya = 3.
                const realSlotWidth = (slotWidthPercent / 100) * 1; // Lebar slot proporsional
                const realSlotHeight = (slotHeightPercent / 100) * 3; // Tinggi slot proporsional
                const slotAspect = realSlotWidth / realSlotHeight;

                if (slotAspect > cameraAspect) {
                    // Slot lebih LEBAR dari kamera. Bagian atas/bawah kamera akan terpotong.
                    const scaledCameraHeight = realSlotWidth / cameraAspect;
                    const visibleHeightRatio = realSlotHeight / scaledCameraHeight;
                    const margin = (1 - visibleHeightRatio) / 2;

                    safeIntersection.top = Math.max(safeIntersection.top, margin * 100);
                    safeIntersection.bottom = Math.min(safeIntersection.bottom, 100 - (margin * 100));

                } else {
                    // Slot lebih TINGGI/SEMPIT dari kamera. Bagian kiri/kanan kamera akan terpotong.
                    const scaledCameraWidth = realSlotHeight * cameraAspect;
                    const visibleWidthRatio = realSlotWidth / scaledCameraWidth;
                    const margin = (1 - visibleWidthRatio) / 2;

                    safeIntersection.left = Math.max(safeIntersection.left, margin * 100);
                    safeIntersection.right = Math.min(safeIntersection.right, 100 - (margin * 100));
                }
            });

            // Hitung dimensi akhir dari persimpangan (intersection) semua area aman.
            const finalWidth = Math.max(0, safeIntersection.right - safeIntersection.left);
            const finalHeight = Math.max(0, safeIntersection.bottom - safeIntersection.top);

            const result = {
                left: safeIntersection.left,
                top: safeIntersection.top,
                width: finalWidth,
                height: finalHeight,
                hasValidIntersection: finalWidth > 0 && finalHeight > 0,
                cameraUsage: (finalWidth * finalHeight) / 100
            };

            console.log(`SAFE ZONE: Posisi (${result.left.toFixed(1)}%, ${result.top.toFixed(1)}%), Ukuran (${result.width.toFixed(1)}% x ${result.height.toFixed(1)}%), Area Terlihat: ${result.cameraUsage.toFixed(1)}%`);

            return result;
        }
        // Apply camera filter
        let currentFilter = 'none';

        function applyFilter(filterValue) {
            const cameraFeed = document.getElementById('camera-feed');

            // Remove any existing inline filter styles
            cameraFeed.style.filter = '';

            // Update select value if called programmatically
            document.getElementById('camera-filter').value = filterValue;

            // Apply new filter
            currentFilter = filterValue;
            if (filterValue !== 'none' && filterValue) {
                // Apply CSS filter directly to the video element
                cameraFeed.style.filter = filterValue;
            }
        }

        // Gallery photo action functions

        // Return to camera function for gallery back button
        let viewingGalleryPhoto = false; // Flag to track if viewing saved photo from gallery

        function returnToCamera() {
            // Set flag to prevent auto-capture
            viewingGalleryPhoto = true;

            // Hide any open preview modal
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview.style.display === 'flex') {
                photoPreview.style.display = 'none';
                // Remove preview mode class
                document.querySelector('.camera-section').classList.remove('preview-mode');
            }

            // Focus back on camera - scroll to camera section if needed
            const cameraSection = document.querySelector('.camera-section');
            cameraSection.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Re-enable capture button if it was disabled due to preview
            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn.disabled && !isMaxPhotosReached()) {
                captureBtn.disabled = false;
                captureBtn.style.opacity = '1';
                captureBtn.innerHTML = '📸 Ambil Foto';
            }

            // Clear any current photo blob that might be in preview
            if (currentPhotoBlob) {
                currentPhotoBlob = null;
                const previewImage = document.getElementById('preview-image');
                if (previewImage.src) {
                    URL.revokeObjectURL(previewImage.src);
                }
            }

            // Clear flag after a delay to allow normal auto-capture to resume
            setTimeout(() => {
                viewingGalleryPhoto = false;
            }, 1000);
        }

        function deleteFromGallery(btn, photoId) {
            // Case 1: If current photos < minimum, cannot delete at all
            if (photosSaved < numFrameSlots) {
                showCustomAlert(`Tidak boleh menghapus! Anda baru punya ${photosSaved} foto, minimal diperlukan ${numFrameSlots} foto.`);
                return;
            }

            // Case 2: If deleting would make photos < minimum, cannot delete
            if ((photosSaved - 1) < numFrameSlots) {
                showCustomAlert(`Tidak boleh menghapus! Setelah dihapus akan tersisa ${photosSaved - 1} foto, minimal diperlukan ${numFrameSlots} foto.`);
                return;
            }

            // Case 3: Safe to delete (will still have >= minimum after deletion)
            if (!confirm('Hapus foto ini dari galeri?')) {
                return;
            }

            {
                const photoElement = btn.closest('.gallery-photo');

                btn.disabled = true;
                btn.textContent = '⏳';

                // Remove from server
                fetch('<?= URLROOT ?>/photo/deleteSessionPhoto', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        session_id: sessionId,
                        photo_id: photoId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Remove from UI
                            photoElement.remove();
                            photosSaved--;

                            // Remove from allPhotos array
                            const photoData = allPhotos.find(p => p.data && p.data.id === photoId);
                            if (photoData) {
                                const index = allPhotos.indexOf(photoData);
                                if (index > -1) allPhotos.splice(index, 1);
                            }

                            updateStats();
                            updateGalleryTitle();

                            // Hide finish button if no photos left
                            if (photosSaved === 0) {
                                finishBtn.style.display = 'none';
                            }
                        } else {
                            btn.disabled = false;
                            btn.textContent = '🗑️';
                            alert('Gagal menghapus foto: ' + (data.message || 'Unknown error'));
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting photo:', error);
                        btn.disabled = false;
                        btn.textContent = '🗑️';
                        alert('Terjadi kesalahan saat menghapus foto');
                    });
            }
        }

        // Recalculate safe zone when window resizes or orientation changes
        function handleResize() {
            clearTimeout(window.resizeTimer);
            window.resizeTimer = setTimeout(() => {
                const isMobilePortrait = window.innerWidth <= 480 && window.innerHeight > window.innerWidth;
                const aspectStr = isMobilePortrait ? '9:16 (portrait)' : '16:9 (landscape)';
                console.log(`Window resized/orientation changed, adjusting camera for ${aspectStr}`);
                calculateSafeZone();
            }, 300);
        }

        // Listen for orientation changes
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                handleResize();
            }, 100); // Small delay to allow layout to settle
        });



        // Initialize everything
        document.addEventListener('DOMContentLoaded', () => {
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const cameraSection = document.querySelector('.camera-section');

            fullscreenBtn.addEventListener('click', () => {
                toggleFullScreen(cameraSection);
            });

            function toggleFullScreen(elem) {
                if (!document.fullscreenElement) {
                    elem.requestFullscreen().catch(err => {
                        alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                    });
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }

            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    cameraSection.classList.remove('fullscreen');
                    document.body.classList.remove('fullscreen-active');
                } else {
                    cameraSection.classList.add('fullscreen');
                    document.body.classList.add('fullscreen-active');
                }
                // Recalculate safe zone on fullscreen change
                setTimeout(calculateSafeZone, 300);
            });

            initCamera();
            startTimer();

            // Add resize listener
            window.addEventListener('resize', handleResize);


            // Initial safe zone calculation (fallback if camera doesn't trigger)
            setTimeout(() => {
                if (!window.safeZoneCalculated) {
                    calculateSafeZone();
                }
            }, 1500);

            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.code === 'Space') {
                    e.preventDefault();
                    if (photoPreview.style.display === 'none') {
                        // Prevent space if max photos reached
                        if (photosSaved >= maxSavePhotos) {
                            showCustomAlert('Anda telah mencapai batas maksimal foto. Tekan tombol SELESAI untuk melanjutkan.');
                            // Only prevent space during extended time when requirement is met
                        } else if (isExtendedTime && photosSaved >= numFrameSlots) {
                            showCustomAlert('Anda sudah mencapai jumlah foto yang dibutuhkan untuk frame. Tekan tombol SELESAI untuk melanjutkan.');
                        } else {
                            capturePhoto();
                        }
                    }
                } else if (e.code === 'KeyD' && e.ctrlKey) {
                    // Ctrl+D to toggle slot debug visualization
                    e.preventDefault();
                    window.showSlotDebug = !window.showSlotDebug;
                    console.log('Slot debug visualization:', window.showSlotDebug ? 'ON' : 'OFF');
                    calculateSafeZone(); // Recalculate to show/hide debug indicators
                }
            });
        });

        // Simple back/refresh protection with popup
        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
            let allowNavigation = false;

            // Handle refresh attempts
            window.addEventListener('beforeunload', function (e) {
                if (allowNavigation) {
                    return;
                }

                e.preventDefault();
                e.returnValue = '';
                return '';
            });

            // Handle browser back button
            let currentUrl = window.location.href;
            window.history.pushState({}, '', currentUrl);

            window.addEventListener('popstate', function (e) {
                if (allowNavigation) {
                    return;
                }

                // Show confirmation for back button
                if (confirm('⚠️ PERINGATAN!\n\nAnda mencoba kembali ke halaman sebelumnya. Foto yang belum disimpan akan hilang.\n\nApakah Anda yakin ingin melanjutkan?')) {
                    allowNavigation = true;
                    window.history.go(-1);
                } else {
                    // Stay on current page
                    window.history.pushState({}, '', currentUrl);
                }
            });

            console.log('Simple back/refresh protection loaded for photo session');
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