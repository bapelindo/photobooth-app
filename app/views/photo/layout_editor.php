<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHOTOBOOTH AIRWAYS - Flight Layout Editor</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            margin: 0;
            font-family: 'Roboto Condensed', sans-serif;
        }

        /* ========== SKY BACKGROUND WITH CLOUDS ========== */
        body {
            background: linear-gradient(120deg, #c2e9fb 0%, #a1c4fd 50%, #e2d0cb 100%);
            position: relative;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out;
            min-height: 100vh;
            overflow-y: auto;
        }

        /* Animated Clouds */
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
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
            animation: float linear infinite;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
        }

        .cloud1 {
            width: 120px;
            height: 50px;
            top: 15%;
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
            top: 65%;
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
            height: 30px;
            top: -10px;
            right: 15px;
        }

        .cloud3 {
            width: 140px;
            height: 60px;
            top: 35%;
            left: -180px;
            animation-duration: 60s;
            animation-delay: 15s;
        }

        .cloud3::before {
            width: 70px;
            height: 60px;
            top: -30px;
            left: 25px;
        }

        .cloud3::after {
            width: 80px;
            height: 50px;
            top: -20px;
            right: 25px;
        }

        @keyframes float {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(120vw);
            }
        }

        /* Firefox Scrollbar */
        html {
            scrollbar-width: thin;
            /* "auto" or "thin" */
            scrollbar-color: rgba(254, 214, 227, 1) rgba(255, 255, 255, 0.95);
            /* thumb and track color */
        }

        /* Exact same animation as select-frame */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        body.fade-out {
            opacity: 0;
        }

        .layout-container {
            display: grid;
            grid-template-columns: 320px 1fr;
            grid-template-rows: auto 1fr;
            gap: 15px;
            height: 95vh;
            max-height: 95vh;
            width: 100%;
            max-width: 1600px;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.1);
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
            position: relative;
            z-index: 1;
            border: 2px solid rgba(255, 255, 255, 0.8);
            overflow: hidden;
        }

        .layout-container.content-fade-out {
            opacity: 0;
        }

        .layout-container>* {
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

        .header-panel {
            grid-column: 1 / -1;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 12px;
            padding: 15px 25px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }

        .header-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-panel h1 {
            font-family: 'Orbitron', sans-serif;
            color: white;
            margin: 0;
            font-size: 1.8rem;
            font-weight: 900;
            letter-spacing: 3px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
            position: relative;
            z-index: 1;
        }

        .header-subtitle {
            font-family: 'Roboto Condensed', sans-serif;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.85rem;
            letter-spacing: 2px;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .photos-panel {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 18px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border: 2px solid #2a5298;
            position: relative;
            overflow: visible;
        }

        .photos-panel::before {
            content: 'BOARDING PASS';
            position: absolute;
            top: -12px;
            left: 20px;
            background: #2a5298;
            color: white;
            padding: 4px 15px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 4px;
            letter-spacing: 1px;
        }

        .photos-panel h3 {
            margin: 0 0 15px 0;
            font-family: 'Orbitron', sans-serif;
            color: #1e3c72;
            font-size: 1rem;
            font-weight: 700;
            letter-spacing: 1px;
            padding-bottom: 10px;
            border-bottom: 2px dashed #2a5298;
        }

        .photo-source {
            flex-grow: 1;
            overflow-y: auto;
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 10px;
            max-height: calc(100vh - 250px);
            align-content: flex-start;
        }

        .draggable-photo {
            width: calc(33.33% - 8px);
            box-sizing: border-box;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: grab;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            user-select: none;
            -webkit-user-drag: element;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        .draggable-photo:hover {
            border-color: var(--primary-color);
            transform: scale(1.08);
            box-shadow: 0 8px 30px rgba(108, 99, 255, 0.4);
            z-index: 10;
        }

        .draggable-photo.dragging {
            cursor: grabbing;
            opacity: 0.5;
            transform: scale(0.9);
            z-index: 1000;
            position: relative;
            filter: blur(1px);
        }

        .draggable-photo.used {
            opacity: 0.6;
            border: 2px solid var(--success-color);
            position: relative;
        }

        .draggable-photo.used::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .draggable-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            pointer-events: none;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        .workspace {
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            padding: 20px;
            backdrop-filter: blur(10px);
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            position: relative;
            overflow: visible;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 2px solid rgba(42, 82, 152, 0.2);
        }

        .frame-tabs {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 0;
            overflow-y: auto;
            max-height: 100%;
        }

        .frame-tab {
            background: linear-gradient(135deg, #ffffff 0%, #f0f2f5 100%);
            border: 2px solid #2a5298;
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 700;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            min-width: 85px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .frame-tab::before {
            content: 'FLIGHT';
            position: absolute;
            top: 2px;
            left: 5px;
            font-size: 0.55rem;
            font-weight: 700;
            color: #2a5298;
            opacity: 0.6;
        }

        .frame-tab:hover {
            background: linear-gradient(135deg, #e8f0fe 0%, #c3cfe2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(42, 82, 152, 0.2);
        }

        .frame-tab.active {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            border-color: #1e3c72;
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.4);
        }

        .frame-tab.active::before {
            color: rgba(255, 255, 255, 0.7);
        }

        .frame-thumb {
            width: 50px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
        }

        .photostrip-canvas-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .photostrip-canvas-container canvas {
            border: 3px dashed #ddd !important;
            border-radius: 15px !important;
        }

        #photostrip-container {
            height: 100%;
            width: 100%;
            overflow: hidden;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .controls-panel {
            background: transparent;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            /* Align items to the bottom */
            gap: 15px;
        }

        .btn {
            padding: 12px 16px;
            border: none;
            border-radius: 6px;
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
        }

        .side-actions {
            display: flex;
            gap: 10px;
        }

        .btn-side-action {
            flex: 1;
            background: linear-gradient(135deg, #ffffff 0%, #f0f2f5 100%);
            color: #1e3c72;
            border: 2px solid #2a5298;
        }

        .btn-side-action:hover {
            background: linear-gradient(135deg, #e8f0fe 0%, #c3cfe2 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(42, 82, 152, 0.2);
        }

        .btn-continue {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            padding: 14px 18px;
            font-weight: 900;
            letter-spacing: 2px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
        }

        .btn-continue:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.5);
        }

        .btn-secondary {
            background: linear-gradient(135deg, #28a745 0%, #218838 100%);
            color: white;
            font-weight: 700;
            border: 2px solid #1e7e34;
        }

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(40, 167, 69, 0.3);
        }

        .btn:disabled {
            background: #e0e0e0;
            color: #9e9e9e;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .progress-indicator {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 12px 15px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #2a5298;
            margin-bottom: auto;
            box-shadow: 0 2px 8px rgba(42, 82, 152, 0.15);
            position: relative;
        }

        .progress-indicator::before {
            content: 'STATUS';
            position: absolute;
            top: -8px;
            left: 10px;
            background: #2a5298;
            color: white;
            padding: 2px 8px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            border-radius: 3px;
            letter-spacing: 1px;
        }

        #progress-text {
            font-family: 'Roboto Condensed', sans-serif;
            font-weight: 700;
            color: #1e3c72;
            font-size: 0.85rem;
            margin-bottom: 10px;
            display: block;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .progress-bar {
            width: 100%;
            height: 6px;
            background: rgba(30, 60, 114, 0.1);
            border-radius: 3px;
            overflow: hidden;
            border: 1px solid rgba(30, 60, 114, 0.2);
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            border-radius: 3px;
            width: 0%;
            transition: width 0.5s ease;
            box-shadow: 0 0 6px rgba(30, 60, 114, 0.4);
        }

        .empty-state {
            text-align: center;
            color: #999;
            padding: 40px;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(76, 175, 80, 0.95);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.error {
            background: rgba(244, 67, 54, 0.95);
        }

        /* Preview Modal Styles */
        .preview-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(30, 60, 114, 0.85);
            backdrop-filter: blur(5px);
            z-index: 10000;
            display: none;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .preview-modal.show {
            display: flex;
            opacity: 1;
        }

        .preview-content {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border: 2px solid #2a5298;
            border-radius: 12px;
            padding: 30px;
            max-width: 50vw;
            max-height: 50vh;
            overflow: auto;
            position: relative;
            box-shadow: 0 20px 60px rgba(30, 60, 114, 0.4);
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }

        .preview-modal.show .preview-content {
            transform: scale(1);
        }

        .preview-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px dashed #2a5298;
        }

        .preview-header h2 {
            font-family: 'Orbitron', sans-serif;
            color: #1e3c72;
            margin: 0;
            font-size: 1.5rem;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .close-preview {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            border-radius: 6px;
            width: 40px;
            height: 40px;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .close-preview:hover {
            background: linear-gradient(135deg, #c82333 0%, #bd2130 100%);
            transform: scale(1.1);
        }

        .print-preview-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .photostrip-paper {
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            border: 2px solid #2a5298;
            border-radius: 8px;
            padding: 15px;
            position: relative;
            transform-style: preserve-3d;
            transform: perspective(1000px) rotateX(2deg);
            max-width: 320px;
            max-height: 80vh;
            overflow: auto;
        }

        .photostrip-paper::before {
            content: '';
            position: absolute;
            top: -5px;
            left: 10px;
            right: 10px;
            height: 3px;
            background: linear-gradient(90deg, transparent 0%, #ddd 20%, #ddd 80%, transparent 100%);
            border-radius: 50%;
            opacity: 0.6;
        }

        .photostrip-image {
            width: 200px;
            height: 600px;
            border-radius: 8px;
            object-fit: contain;
            border: 1px solid #e0e0e0;
        }

        .print-info {
            text-align: center;
            color: #666;
            font-size: 0.9rem;
            max-width: 400px;
        }

        .preview-actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 20px;
        }

        .btn-close {
            position: absolute;
            top: 15px;
            right: 15px;
            background: rgba(255, 255, 255, 0.9);
            color: #666;
            border: 2px solid #ddd;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(5px);
        }

        .btn-close:hover {
            background: #ff5757;
            color: white;
            border-color: #ff5757;
            transform: scale(1.1);
        }

        /* ========== MOBILE RESPONSIVE ========== */
        @media (max-width: 1024px) {
            .layout-container {
                grid-template-columns: 280px 1fr;
                gap: 12px;
                padding: 15px;
            }

            .header-panel h1 {
                font-size: 1.5rem;
            }

            .header-subtitle {
                font-size: 0.75rem;
            }

            .frame-tab {
                min-width: 75px;
                font-size: 0.7rem;
            }

            .btn {
                font-size: 0.8rem;
                padding: 10px 12px;
            }
        }

        @media (max-width: 768px) {
            body {
                padding: 10px;
                overflow-y: auto;
                min-height: 100vh;
            }

            .layout-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
                gap: 12px;
                height: auto;
                max-height: none;
                padding: 12px;
                border-radius: 12px;
                overflow: visible;
            }

            .header-panel {
                padding: 12px 20px;
            }

            .header-panel h1 {
                font-size: 1.3rem;
                letter-spacing: 2px;
            }

            .header-subtitle {
                font-size: 0.7rem;
            }

            /* Photos panel with frames integrated */
            .photos-panel {
                order: 1;
                padding: 12px;
                max-height: 320px;
                overflow-y: auto;
                display: flex;
                flex-direction: column;
            }

            .photos-panel h3 {
                font-size: 0.9rem;
                padding-bottom: 6px;
                margin-bottom: 8px;
            }

            .photo-source {
                gap: 10px;
                padding: 5px;
            }

            .draggable-photo {
                width: calc(48% - 5px);
                border-width: 2px;
            }

            /* Frame tabs - normal flow, horizontal scroll */
            .workspace {
                order: 2;
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr auto;
                gap: 12px;
                padding: 12px;
                overflow: visible;
            }

            .frame-tabs {
                flex-direction: row;
                overflow-x: auto;
                overflow-y: hidden;
                padding: 8px 10px;
                gap: 10px;
                background: linear-gradient(135deg, rgba(245, 247, 250, 0.98) 0%, rgba(195, 207, 226, 0.98) 100%);
                backdrop-filter: blur(15px);
                border-radius: 8px;
                border: 2px solid #2a5298;
                box-shadow: 0 2px 8px rgba(42, 82, 152, 0.15);
                /* Add smooth snap scrolling */
                scroll-snap-type: x mandatory;
                -webkit-overflow-scrolling: touch;
                order: 1;
            }

            .frame-tab {
                min-width: 80px;
                flex-shrink: 0;
                padding: 10px 8px;
                scroll-snap-align: start;
                position: relative;
            }

            .frame-tab::after {
                content: '';
                position: absolute;
                bottom: -2px;
                left: 50%;
                transform: translateX(-50%);
                width: 0;
                height: 3px;
                background: #1e3c72;
                transition: width 0.3s ease;
                border-radius: 2px;
            }

            .frame-tab.active::after {
                width: 60%;
            }

            .frame-thumb {
                width: 45px;
                height: 67px;
            }

            .photostrip-canvas-container {
                order: 2;
                min-height: 280px;
                position: relative;
            }

            /* Current frame indicator */
            .photostrip-canvas-container::before {
                content: 'FLIGHT ' attr(data-current-frame);
                position: absolute;
                top: -28px;
                left: 50%;
                transform: translateX(-50%);
                background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
                color: white;
                padding: 4px 12px;
                font-family: 'Roboto Condensed', sans-serif;
                font-size: 0.7rem;
                font-weight: 700;
                border-radius: 4px;
                letter-spacing: 1px;
                z-index: 10;
                box-shadow: 0 2px 8px rgba(42, 82, 152, 0.3);
            }

            .progress-indicator {
                padding: 10px 12px;
                margin-bottom: 5px;
            }

            .progress-indicator::before {
                font-size: 0.5rem;
                padding: 2px 6px;
            }

            #progress-text {
                font-size: 0.75rem;
                margin-bottom: 8px;
            }

            .side-actions {
                flex-wrap: wrap;
            }

            .btn-side-action {
                padding: 10px 12px;
                font-size: 0.75rem;
            }

            .btn-continue {
                width: 100%;
                padding: 12px 16px;
                font-size: 0.9rem;
            }

            .btn svg {
                width: 16px;
                height: 16px;
            }

            .preview-content {
                padding: 20px;
                margin: 10px;
                max-width: 90vw;
            }

            .preview-header h2 {
                font-size: 1.2rem;
            }

            .photostrip-paper {
                max-width: 90vw;
                padding: 10px;
            }

            .notification {
                top: 10px;
                right: 10px;
                left: 10px;
                font-size: 0.8rem;
                padding: 10px 15px;
            }

            /* Cloud animations slower on mobile */
            .cloud1 {
                animation-duration: 60s;
            }

            .cloud2 {
                animation-duration: 70s;
            }

            .cloud3 {
                animation-duration: 75s;
            }
        }

        @media (max-width: 480px) {
            body {
                padding: 8px;
            }

            .layout-container {
                padding: 10px;
                gap: 10px;
            }

            .header-panel h1 {
                font-size: 1.1rem;
                letter-spacing: 1px;
            }

            .header-subtitle {
                font-size: 0.65rem;
                letter-spacing: 1px;
            }

            .photos-panel {
                padding: 10px;
                max-height: 300px;
            }

            .photos-panel h3 {
                font-size: 0.8rem;
            }

            .photo-source {
                gap: 8px;
            }

            .draggable-photo {
                width: calc(48% - 4px);
                border-width: 2px;
            }

            .frame-tabs {
                padding: 6px 8px;
                gap: 8px;
            }

            .frame-tab {
                min-width: 70px;
                font-size: 0.65rem;
                padding: 8px 6px;
            }

            .frame-tab::before {
                font-size: 0.5rem;
                top: 1px;
                left: 3px;
            }

            .frame-thumb {
                width: 38px;
                height: 57px;
            }

            .photostrip-canvas-container {
                min-height: 240px;
            }

            .btn {
                font-size: 0.75rem;
                padding: 8px 10px;
            }

            .btn svg {
                width: 14px;
                height: 14px;
            }

            .photostrip-canvas-container canvas {
                max-width: 100%;
            }
        }

        /* Touch device optimizations */
        @media (hover: none) and (pointer: coarse) {
            .draggable-photo:hover {
                transform: none;
            }

            .draggable-photo:active {
                transform: scale(0.95);
            }

            .btn:hover {
                transform: none;
            }

            .btn:active {
                transform: scale(0.97);
            }

            .frame-tab:hover {
                transform: none;
            }

            .frame-tab:active {
                transform: translateY(-1px);
            }
        }
    </style>
</head>

<body>
    <!-- Animated Clouds Background -->
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>

    <div class="layout-container">
        <div class="header-panel">
            <h1>PHOTOBOOTH AIRWAYS</h1>
            <div class="header-subtitle">Flight Layout Editor</div>
        </div>

        <div class="photos-panel">
            <h3>✈️ CARGO MANIFEST</h3>
            <div style="font-family: 'Roboto Mono', monospace; font-size: 0.7rem; color: #1e3c72; margin-bottom: 12px; display: flex; justify-content: space-between;">
                <span>GATE: A<?= $data['session']->id ?></span>
                <span>SEAT: <?= count($data['photos']) ?>A</span>
            </div>
            <div class="photo-source" id="photo-source">
                <?php foreach ($data['photos'] as $photo): ?>
                    <div class="draggable-photo" draggable="true" data-photo-id="<?= $photo->id ?>"
                        data-photo-path="<?= $photo->file_path ?>">
                        <img src="<?= URLROOT . $photo->file_path ?>" alt="Foto Sesi">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="workspace">
            <div class="frame-tabs" id="frame-tabs">
                <?php foreach ($data['frames'] as $index => $frame): ?>
                    <div class="frame-tab <?= $index === 0 ? 'active' : '' ?>" data-frame-id="<?= $frame->id ?>">
                        <img src="<?= URLROOT . $frame->path ?>" alt="<?= $frame->name ?>" class="frame-thumb">
                        <span><?= $frame->name ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="photostrip-container">
                <?php if (empty($data['frames'])): ?>
                    <div class="empty-state">
                        <h3>Tidak Ada Frame</h3>
                        <p>Tidak ada frame yang dipilih untuk sesi ini.</p>
                    </div>
                <?php else: ?>
                    <!-- Single canvas container that will be reused for all frames -->
                    <div class="photostrip-canvas-container" id="main-canvas-container">
                        <canvas id="main-canvas"></canvas>
                    </div>
                <?php endif; ?>
            </div>

            <div class="controls-panel">
                <div class="progress-indicator">
                    <span id="progress-text">Lengkapi semua slot foto!</span>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                </div>

                <div class="side-actions">
                    <button class="btn btn-side-action" onclick="clearAllSlots()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                            </path>
                        </svg>
                        <span>Bersihkan</span>
                    </button>
                    <button class="btn btn-side-action" onclick="previewPhotostrips()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <span>Preview</span>
                    </button>

                    <button class="btn btn-secondary" id="autofill-btn" onclick="autoFillPhotos()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="12" y1="8" x2="12" y2="16"></line>
                            <line x1="8" y1="12" x2="16" y2="12"></line>
                        </svg>
                        <span>Otomatis</span>
                    </button>
                </div>

                <button class="btn btn-continue" id="continue-btn" onclick="saveLayouts()" disabled>
                    <span>Lanjut ke Dekorasi</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14"></path>
                        <path d="M12 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const FRAMES_DATA = <?= json_encode($data['frames']) ?> || [];
        const URLROOT = '<?= URLROOT ?>';
        let mainCanvas = null;
        const frameData = [];
        let currentFrameIndex = 0;
        let draggedItemData = null;
        let totalSlots = 0;

        // Calculate total slots
        FRAMES_DATA.forEach(frameData => {
            const slotCoords = frameData.slot_coordinates ? JSON.parse(frameData.slot_coordinates) : [];
            totalSlots += slotCoords.length || 4; // Default 4 slots if no coordinates
        });

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, initializing fabric.js layout editor...');
            console.log('Available frames:', FRAMES_DATA);
            console.log('Total slots:', totalSlots);

            if (!FRAMES_DATA || FRAMES_DATA.length === 0) {
                alert('Error: No frames available for layout editing!');
                return;
            }

            try {
                // Initialize frame data for all frames
                FRAMES_DATA.forEach((frame, index) => {
                    const slotCoords = frame.slot_coordinates ? JSON.parse(frame.slot_coordinates) : [];
                    frameData[index] = {
                        id: frame.id,
                        name: frame.name,
                        path: frame.path,
                        slots: slotCoords,
                        images: {}
                    };
                });

                initializeMainCanvas();
                initializeFrameTabs();
                initializeDragAndDrop();
                loadCurrentFrame();

                console.log('Fabric.js layout editor initialized successfully');
            } catch (error) {
                console.error('Error initializing layout editor:', error);
                alert('Error initializing layout editor: ' + error.message);
            }

            // Update used status on photos in the gallery
            updatePhotoUsedStatus();

            // Initialize mobile frame indicator
            updateMobileFrameIndicator(currentFrameIndex + 1);
        });

        function updatePhotoUsedStatus() {
            // Remove all 'used' classes first
            document.querySelectorAll('.draggable-photo.used').forEach(el => el.classList.remove('used'));

            // Re-apply 'used' status based on current frame data
            frameData.forEach(frame => {
                if (frame.images) {
                    Object.values(frame.images).forEach(imgData => {
                        if (imgData && imgData.photoId) {
                            const photoElement = document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`);
                            if (photoElement) {
                                photoElement.classList.add('used');
                            }
                        }
                    });
                }
            });
        }

        function initializeMainCanvas() {
            const container = document.getElementById('photostrip-container');
            const canvasEl = document.getElementById('main-canvas');

            if (!container || !canvasEl) {
                console.error('Canvas elements not found');
                return;
            }

            // Debounce resize events
            let resizeTimeout;
            const resizeObserver = new ResizeObserver(entries => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const entry = entries[0];
                    const containerRect = entry.contentRect;
                    let canvasWidth, canvasHeight;

                    const targetAspectRatio = 1 / 3; // 2:6 ratio
                    const containerAspectRatio = containerRect.width / containerRect.height;

                    if (containerAspectRatio > targetAspectRatio) {
                        // Container is wider than target, so height is the constraint
                        canvasHeight = containerRect.height;
                        canvasWidth = canvasHeight * targetAspectRatio;
                    } else {
                        // Container is taller or equal, so width is the constraint
                        canvasWidth = containerRect.width;
                        canvasHeight = canvasWidth / targetAspectRatio;
                    }

                    mainCanvas.setDimensions({
                        width: canvasWidth,
                        height: canvasHeight
                    });

                    // Reload frame to redraw background and slots with new dimensions
                    loadCurrentFrame();

                }, 100); // 100ms debounce
            });

            resizeObserver.observe(container);

            // Initial setup
            const initialRect = container.getBoundingClientRect();
            let initialWidth, initialHeight;
            const targetAspectRatio = 1 / 3;
            const containerAspectRatio = initialRect.width / initialRect.height;

            if (containerAspectRatio > targetAspectRatio) {
                initialHeight = initialRect.height;
                initialWidth = initialHeight * targetAspectRatio;
            } else {
                initialWidth = initialRect.width;
                initialHeight = initialWidth / targetAspectRatio;
            }

            mainCanvas = new fabric.Canvas(canvasEl, {
                width: initialWidth,
                height: initialHeight,
                selection: true,
                stopContextMenu: true
            });

            mainCanvas.allowTouchScrolling = true;

            mainCanvas.on('dragover', function (opt) {
                opt.e.preventDefault();
                opt.e.dataTransfer.dropEffect = 'copy';
            });

            mainCanvas.on('drop', function (opt) {
                opt.e.preventDefault();
                if (!draggedItemData) {
                    console.log('❌ No dragged item data');
                    return;
                }
                const pointer = mainCanvas.getPointer(opt.e);
                const currentFrame = frameData[currentFrameIndex];
                let foundSlot = null;
                if (currentFrame && currentFrame.slotObjects) {
                    for (const key in currentFrame.slotObjects) {
                        if (currentFrame.slotObjects[key].containsPoint(pointer)) {
                            foundSlot = currentFrame.slotObjects[key];
                            break;
                        }
                    }
                }
                if (foundSlot) {
                    handleDrop(mainCanvas, foundSlot);
                } else {
                    showNotification('Lepas foto di area slot yang tersedia!', 'error');
                }
                draggedItemData = null;
            });
        }

        function loadCurrentFrame() {
            if (!mainCanvas || !frameData[currentFrameIndex]) {
                console.error('Canvas or frame data not available');
                return;
            }

            const currentFrame = frameData[currentFrameIndex];
            console.log('Loading frame:', currentFrame.name);

            // Clear canvas
            mainCanvas.clear();
            mainCanvas.backgroundColor = 'lightblue';

            const imageUrl = URLROOT + currentFrame.path;
            console.log('Attempting to load frame from URL:', imageUrl);

            // Load frame as a regular object (not background) so it can be layered
            fabric.Image.fromURL(imageUrl, (img, isError) => {
                if (isError || !img) {
                    console.error(`Failed to load frame from ${imageUrl}. isError: ${isError}`);
                    const errorRect = new fabric.Rect({
                        width: mainCanvas.width,
                        height: mainCanvas.height,
                        fill: 'rgba(255, 0, 0, 0.2)',
                        stroke: 'red',
                        strokeWidth: 2
                    });
                    mainCanvas.add(errorRect);
                    mainCanvas.sendToBack(errorRect);
                    mainCanvas.renderAll();
                    return;
                }

                console.log('Image loaded successfully. Setting as background layer.', img);

                // Add frame as bottom layer
                img.set({
                    scaleX: mainCanvas.width / img.width,
                    scaleY: mainCanvas.height / img.height,
                    selectable: false,
                    evented: false,
                    id: 'frame-bottom'
                });
                mainCanvas.add(img);
                mainCanvas.sendToBack(img);

                // Clone and add frame as top layer (will be on top of photos)
                img.clone((clonedImg) => {
                    clonedImg.set({
                        id: 'frame-top',
                        selectable: false,
                        evented: false
                    });
                    mainCanvas.add(clonedImg);
                    mainCanvas.bringToFront(clonedImg);
                    mainCanvas.renderAll();
                    console.log('Frame layers added: bottom and top');
                });
            }, { crossOrigin: 'anonymous' });

            // Create slot rectangles from current frame data
            const slotCoords = currentFrame.slots;

            // Default slots if none provided
            const defaultSlots = [
                { left: 8.33, top: 6.67, width: 83.33, height: 20 },
                { left: 8.33, top: 30, width: 83.33, height: 20 },
                { left: 8.33, top: 53.33, width: 83.33, height: 20 },
                { left: 8.33, top: 76.67, width: 83.33, height: 20 }
            ];

            const slotsToUse = slotCoords.length > 0 ? slotCoords : defaultSlots;

            // Create slot rectangles
            slotsToUse.forEach((slotCoordData, slotIndex) => {
                const slotRect = new fabric.Rect({
                    left: mainCanvas.width * (slotCoordData.left / 100),
                    top: mainCanvas.height * (slotCoordData.top / 100),
                    width: mainCanvas.width * (slotCoordData.width / 100),
                    height: mainCanvas.height * (slotCoordData.height / 100),
                    fill: 'rgba(108, 99, 255, 0.2)',
                    stroke: '#6C63FF',
                    strokeDashArray: [6, 6],
                    strokeWidth: 3,
                    selectable: false,
                    evented: false,
                    hoverCursor: 'copy',
                    isSlot: true,
                    frameIndex: currentFrameIndex,
                    slotIndex: slotIndex,
                    rx: 10,
                    ry: 10
                });

                console.log(`Created slot ${slotIndex}:`, {
                    left: slotRect.left,
                    top: slotRect.top,
                    width: slotRect.width,
                    height: slotRect.height
                });
                mainCanvas.add(slotRect);

                // Add slot number text
                const slotText = new fabric.Text(`📷 ${slotIndex + 1}`, {
                    left: slotRect.left + slotRect.width / 2,
                    top: slotRect.top + slotRect.height / 2,
                    fontSize: 16,
                    fill: '#6C63FF',
                    textAlign: 'center',
                    originX: 'center',
                    originY: 'center',
                    selectable: false,
                    evented: false,
                    fontWeight: 'bold'
                });
                mainCanvas.add(slotText);

                // Store slot reference in frame data
                if (!currentFrame.slotObjects) {
                    currentFrame.slotObjects = {};
                }
                currentFrame.slotObjects[slotIndex] = slotRect;
            });

            // Drag and drop handled by HTML5 events in initializeMainCanvas
            console.log('✅ Frame loaded with slot objects:', Object.keys(currentFrame.slotObjects || {}));

            // Load any saved images for this frame
            if (currentFrame.images) {
                Object.keys(currentFrame.images).forEach(slotIndex => {
                    const imageData = currentFrame.images[slotIndex];
                    if (imageData && currentFrame.slotObjects[slotIndex]) {
                        // Restore saved image
                        fabric.Image.fromURL(imageData.src, (img) => {
                            const slot = currentFrame.slotObjects[slotIndex];
                            img.set({
                                left: imageData.left || slot.left,
                                top: imageData.top || slot.top,
                                scaleX: imageData.scaleX,
                                scaleY: imageData.scaleY,
                                clipPath: new fabric.Rect({
                                    left: slot.left,
                                    top: slot.top,
                                    width: slot.width,
                                    height: slot.height,
                                    absolutePositioned: true
                                }),
                                selectable: true,
                                hasControls: false,
                                hasBorders: true,
                                moveCursor: 'move',
                                hoverCursor: 'move',
                                lockScalingX: true,
                                lockScalingY: true,
                                lockRotation: true
                            });

                            // Add panning constraints for restored image
                            img.on('moving', function () {
                                const slotWidth = slot.width;
                                const slotHeight = slot.height;
                                const rightBound = slot.left;
                                const leftBound = slot.left - (this.getScaledWidth() - slotWidth);
                                const bottomBound = slot.top;
                                const topBound = slot.top - (this.getScaledHeight() - slotHeight);

                                if (this.left > rightBound) this.left = rightBound;
                                if (this.left < leftBound) this.left = leftBound;
                                if (this.top > bottomBound) this.top = bottomBound;
                                if (this.top < topBound) this.top = topBound;
                            });

                            // Save position when restored photo stops moving
                            img.on('modified', function () {
                                // Update position in frame data
                                if (currentFrame.images[slotIndex]) {
                                    currentFrame.images[slotIndex].left = this.left;
                                    currentFrame.images[slotIndex].top = this.top;
                                }
                            });

                            mainCanvas.add(img);
                            // Restore the fabricImage reference
                            imageData.fabricImage = img;
                        });
                    }
                });
            }

            // CRITICAL: Ensure frame-top is always on top after restoring images
            mainCanvas.getObjects().forEach(obj => {
                if (obj.id === 'frame-top') {
                    mainCanvas.bringToFront(obj);
                }
            });
            mainCanvas.renderAll();
        }

        function initializeDragAndDrop() {
            console.log('Initializing drag and drop...');

            const photoSource = document.getElementById('photo-source');
            if (!photoSource) {
                console.error('Photo source element not found');
                return;
            }

            // Add event listeners to each draggable photo individually
            const draggablePhotos = photoSource.querySelectorAll('.draggable-photo');
            console.log('Found draggable photos:', draggablePhotos.length);

            draggablePhotos.forEach((photo, index) => {
                console.log(`Setting up photo ${index}:`, photo.dataset);

                // Test if photo is actually draggable
                console.log('Photo draggable attribute:', photo.getAttribute('draggable'));
                console.log('Photo element:', photo);

                photo.addEventListener('dragstart', (e) => {
                    console.log('🚀 DRAGSTART EVENT TRIGGERED!', e);

                    draggedItemData = {
                        photoSrc: URLROOT + photo.dataset.photoPath,
                        photoId: photo.dataset.photoId,
                        photoPath: photo.dataset.photoPath
                    };
                    console.log('✅ Drag started:', draggedItemData);
                    photo.classList.add('dragging');

                    // Set drag effect
                    if (e.dataTransfer) {
                        e.dataTransfer.effectAllowed = 'copy';
                        e.dataTransfer.setData('text/plain', photo.dataset.photoId);
                        console.log('DataTransfer set successfully');
                    } else {
                        console.error('No dataTransfer object available');
                    }
                });

                photo.addEventListener('dragend', (e) => {
                    photo.classList.remove('dragging');
                    console.log('Drag ended for photo:', photo.dataset.photoId);
                });

                // Ensure draggable attribute is set
                photo.setAttribute('draggable', 'true');
                photo.style.cursor = 'grab';

                // Test click event to ensure element is interactive
                photo.addEventListener('click', (e) => {
                    console.log('📸 Photo clicked!', photo.dataset.photoId);
                });

                // Add hover effects
                photo.addEventListener('mousedown', () => {
                    photo.style.cursor = 'grabbing';
                });

                photo.addEventListener('mouseup', () => {
                    photo.style.cursor = 'grab';
                });

                photo.addEventListener('mouseover', () => {
                    if (!photo.classList.contains('dragging')) {
                        photo.style.transform = 'scale(1.05)';
                        photo.style.boxShadow = '0 8px 25px rgba(108, 99, 255, 0.4)';
                    }
                });

                photo.addEventListener('mouseout', () => {
                    if (!photo.classList.contains('dragging')) {
                        photo.style.transform = '';
                        photo.style.boxShadow = '';
                    }
                });
            });

            console.log('Drag and drop initialized');
        }

        function handleDrop(canvas, slot) {
            if (!draggedItemData) return;

            const { photoSrc, photoId: newPhotoId, photoPath: newPhotoPath } = draggedItemData;
            const { frameIndex, slotIndex } = slot;

            console.log('Drop detected:', { photoSrc, newPhotoId, frameIndex, slotIndex });

            const currentFrame = frameData[currentFrameIndex];

            // Step 1: Remove old image from target slot (if any)
            const existingImageInTargetSlot = currentFrame.images[slotIndex];
            if (existingImageInTargetSlot) {
                const oldPhotoId = existingImageInTargetSlot.photoId;
                document.querySelector(`.draggable-photo[data-photo-id="${oldPhotoId}"]`)?.classList.remove('used');
                canvas.remove(existingImageInTargetSlot.fabricImage);
            }

            // Step 2: Find & remove same image from OTHER slots in ALL frames (if user moves photo)
            frameData.forEach((frame, fIdx) => {
                if (!frame.images) return;
                Object.entries(frame.images).forEach(([sIdx, imgData]) => {
                    if (imgData && imgData.photoId === newPhotoId) {
                        // If it's current frame, remove from canvas
                        if (fIdx === currentFrameIndex) {
                            canvas.remove(imgData.fabricImage);
                        }
                        // Remove from frame data
                        delete frame.images[sIdx];
                    }
                });
            });

            // Remove 'used' status from all photos, then reapply based on state
            document.querySelectorAll('.draggable-photo.used').forEach(el => el.classList.remove('used'));

            // Step 3: Add new image
            fabric.Image.fromURL(photoSrc, (photoImg) => {
                const slotWidth = slot.width;
                const slotHeight = slot.height;
                const scale = Math.max(slotWidth / photoImg.width, slotHeight / photoImg.height);

                photoImg.set({
                    originX: 'left', originY: 'top',
                    left: slot.left, top: slot.top,
                    scaleX: scale, scaleY: scale,
                    clipPath: new fabric.Rect({
                        originX: 'left', originY: 'top',
                        left: slot.left, top: slot.top,
                        width: slotWidth, height: slotHeight,
                        absolutePositioned: true
                    }),
                    selectable: true,
                    hasControls: false,
                    hasBorders: true,
                    moveCursor: 'move',
                    hoverCursor: 'move',
                    lockScalingX: true,
                    lockScalingY: true,
                    lockRotation: true
                });

                // Center the image in the slot
                photoImg.left -= (photoImg.getScaledWidth() - slotWidth) / 2;
                photoImg.top -= (photoImg.getScaledHeight() - slotHeight) / 2;

                canvas.add(photoImg);

                // Initialize images object if needed
                if (!currentFrame.images) {
                    currentFrame.images = {};
                }

                // Update state after new image is added
                currentFrame.images[slotIndex] = {
                    fabricImage: photoImg,
                    photoId: newPhotoId,
                    src: photoSrc,
                    photoPath: newPhotoPath,
                    scaleX: scale,
                    scaleY: scale,
                    left: photoImg.left,
                    top: photoImg.top
                };

                // Update 'used' status in gallery based on latest state
                updatePhotoUsedStatus();

                checkIfDone();

                // Add panning constraints
                photoImg.on('moving', function () {
                    const rightBound = slot.left;
                    const leftBound = slot.left - (this.getScaledWidth() - slotWidth);
                    const bottomBound = slot.top;
                    const topBound = slot.top - (this.getScaledHeight() - slotHeight);

                    if (this.left > rightBound) this.left = rightBound;
                    if (this.left < leftBound) this.left = leftBound;
                    if (this.top > bottomBound) this.top = bottomBound;
                    if (this.top < topBound) this.top = topBound;
                });

                // Save position when photo stops moving
                photoImg.on('modified', function () {
                    // Update position in frame data
                    if (currentFrame.images[slotIndex]) {
                        currentFrame.images[slotIndex].left = this.left;
                        currentFrame.images[slotIndex].top = this.top;
                    }
                });

                canvas.setActiveObject(photoImg);

                // CRITICAL: Ensure frame-top is always on top after adding new image
                canvas.getObjects().forEach(obj => {
                    if (obj.id === 'frame-top') {
                        canvas.bringToFront(obj);
                    }
                });

                canvas.renderAll();

                console.log('Image added successfully to slot', slotIndex);
                showNotification('✅ Foto berhasil ditempatkan!', 'success');

            }, { crossOrigin: 'anonymous' });
        }

        function initializeFrameTabs() {
            console.log('Initializing frame tabs...');
            const frameTabs = document.querySelectorAll('.frame-tab');
            console.log('Found frame tabs:', frameTabs.length);

            if (frameTabs.length === 0) {
                console.error('No frame tabs found!');
                return;
            }

            frameTabs.forEach((tab, index) => {
                console.log(`Setting up tab ${index}:`, tab.dataset.frameId);

                tab.addEventListener('click', () => {
                    console.log('Frame tab clicked:', tab.dataset.frameId, 'index:', index);

                    try {
                        // Remove active class from all tabs
                        frameTabs.forEach(t => t.classList.remove('active'));
                        // Add active class to clicked tab
                        tab.classList.add('active');

                        // Switch to the selected frame
                        currentFrameIndex = index;
                        console.log('Frame switched to index:', currentFrameIndex);

                        // Load the new frame
                        loadCurrentFrame();

                        // Update mobile frame indicator
                        updateMobileFrameIndicator(index + 1);

                        checkIfDone();
                    } catch (error) {
                        console.error('Error switching frame:', error);
                    }
                });
            });

            console.log('Frame tabs initialized');
        }

        function checkIfDone() {
            let filledSlots = 0;
            frameData.forEach(state => {
                filledSlots += Object.keys(state.images).length;
            });

            const continueBtn = document.getElementById('continue-btn');
            const progressFill = document.getElementById('progress-fill');
            const progressText = document.getElementById('progress-text');

            const progress = totalSlots > 0 ? Math.round((filledSlots / totalSlots) * 100) : 0;

            if (progressFill) progressFill.style.width = `${progress}%`;
            if (progressText) progressText.textContent = `${progress}%`;
            if (continueBtn) continueBtn.disabled = filledSlots !== totalSlots;

            console.log(`Progress: ${filledSlots}/${totalSlots} slots filled (${progress}%)`);
        }

        function clearAllSlots() {
            if (!confirm('Hapus semua foto dari layout saat ini?')) return;

            const currentFrame = frameData[currentFrameIndex];
            if (!mainCanvas || !currentFrame) return;

            // Remove all images from the current frame's data and canvas
            if (currentFrame.images) {
                Object.values(currentFrame.images).forEach(imgData => {
                    if (imgData && imgData.fabricImage) {
                        mainCanvas.remove(imgData.fabricImage);
                    }
                    // Unmark the photo in the gallery
                    document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`)?.classList.remove('used');
                });
            }

            // Clear the images state for the current frame
            currentFrame.images = {};

            // Reload the frame to show empty slots
            loadCurrentFrame();
            checkIfDone();

            showNotification('🗑️ Layout dibersihkan!', 'success');
        }

        function updateMobileFrameIndicator(frameNumber) {
            const canvasContainer = document.querySelector('.photostrip-canvas-container');
            if (canvasContainer) {
                canvasContainer.setAttribute('data-current-frame', frameNumber);
            }
        }

        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }

            // Create new notification
            const notification = document.createElement('div');
            notification.className = `notification ${type === 'error' ? 'error' : ''}`;
            notification.textContent = message;
            document.body.appendChild(notification);

            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);

            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function previewPhotostrips() {
            const frame = frameData[currentFrameIndex];
            if (!frame || Object.keys(frame.images).length === 0) {
                showNotification('Tidak ada foto untuk di-preview di frame ini.', 'error');
                return;
            }

            // Hide slots for preview
            if (frame.slotObjects) {
                Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: false }));
            }

            // CRITICAL: Ensure frame-top is on top for preview
            const objects = mainCanvas.getObjects();
            for (const obj of objects) {
                if (obj.id === 'frame-top') {
                    mainCanvas.bringToFront(obj);
                    break;
                }
            }
            mainCanvas.renderAll();

            const dataURL = mainCanvas.toDataURL({ format: 'png', quality: 0.9 });

            // Show slots again
            if (frame.slotObjects) {
                Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: true }));
            }
            mainCanvas.renderAll();

            // Create preview content for modal
            var previewContent = document.getElementById('previewContent');
            previewContent.innerHTML = '<div class="photostrip-preview-content">' +
                '<h3 style="margin: 0 0 15px 0; color: #FF6584; text-align: center; font-weight: bold;">📷 ' + frame.name + '</h3>' +
                '<img src="' + dataURL + '" style="width: 180px; height: auto; box-shadow: 0 4px 8px rgba(0,0,0,0.1);" alt="Photostrip Preview">' +
                '</div>';

            // Store dataURL for printing
            window.currentPreviewDataURL = dataURL;
            window.currentFrameName = frame.name;

            // Show modal
            var modal = document.getElementById('previewModal');
            modal.style.display = 'flex';
            setTimeout(function () {
                modal.style.opacity = '1';
            }, 10);

            showNotification('👁️ Preview photostrip siap!', 'success');
        }

        function closePreview() {
            var modal = document.getElementById('previewModal');
            modal.style.opacity = '0';
            setTimeout(function () {
                modal.style.display = 'none';
            }, 300);
        }

        function autoFillPhotos() {
            const canvas = mainCanvas;
            const currentFrame = frameData[currentFrameIndex];

            if (!currentFrame.slotObjects) {
                showNotification('Tidak ada slot di frame ini!', 'error');
                return;
            }

            // Get all available photos (not used yet)
            const availablePhotos = [];
            document.querySelectorAll('.draggable-photo:not(.used)').forEach(photoEl => {
                availablePhotos.push({
                    photoId: photoEl.getAttribute('data-photo-id'),
                    photoPath: photoEl.getAttribute('data-photo-path'),
                    photoSrc: photoEl.querySelector('img').src
                });
            });

            if (availablePhotos.length === 0) {
                showNotification('Tidak ada foto tersedia!', 'error');
                return;
            }

            // Get all empty slots
            const emptySlots = [];
            Object.keys(currentFrame.slotObjects).forEach(slotIndex => {
                if (!currentFrame.images || !currentFrame.images[slotIndex]) {
                    const slot = currentFrame.slotObjects[slotIndex];
                    emptySlots.push({
                        index: slotIndex,
                        element: slot,
                        left: slot.left,
                        top: slot.top,
                        width: slot.width,
                        height: slot.height
                    });
                }
            });

            if (emptySlots.length === 0) {
                showNotification('Semua slot sudah terisi!', 'success');
                return;
            }

            // Fill slots with available photos
            const photosToUse = availablePhotos.slice(0, emptySlots.length);
            let filledCount = 0;

            photosToUse.forEach((photo, idx) => {
                if (idx >= emptySlots.length) return;

                const slot = emptySlots[idx];
                const { photoSrc, photoId: newPhotoId, photoPath: newPhotoPath } = photo;

                // Step 1: Remove old image from target slot (if any)
                const existingImageInTargetSlot = currentFrame.images[slot.index];
                if (existingImageInTargetSlot) {
                    const oldPhotoId = existingImageInTargetSlot.photoId;
                    document.querySelector(`.draggable-photo[data-photo-id="${oldPhotoId}"]`)?.classList.remove('used');
                    canvas.remove(existingImageInTargetSlot.fabricImage);
                }

                // Step 2: Remove same photo from other slots
                frameData.forEach((frame, fIdx) => {
                    if (!frame.images) return;
                    Object.entries(frame.images).forEach(([sIdx, imgData]) => {
                        if (imgData && imgData.photoId === newPhotoId) {
                            if (fIdx === currentFrameIndex) {
                                canvas.remove(imgData.fabricImage);
                            }
                            delete frame.images[sIdx];
                        }
                    });
                });

                // Step 3: Add new image to slot
                fabric.Image.fromURL(photoSrc, (photoImg) => {
                    const slotWidth = slot.width;
                    const slotHeight = slot.height;
                    const scale = Math.max(slotWidth / photoImg.width, slotHeight / photoImg.height);

                    photoImg.set({
                        originX: 'left',
                        originY: 'top',
                        left: slot.left,
                        top: slot.top,
                        scaleX: scale,
                        scaleY: scale,
                        clipPath: new fabric.Rect({
                            originX: 'left',
                            originY: 'top',
                            left: slot.left,
                            top: slot.top,
                            width: slotWidth,
                            height: slotHeight,
                            absolutePositioned: true
                        }),
                        selectable: true,
                        hasControls: false,
                        hasBorders: true,
                        moveCursor: 'move',
                        hoverCursor: 'move',
                        lockScalingX: true,
                        lockScalingY: true,
                        lockRotation: true
                    });

                    // Center the image in the slot
                    photoImg.left -= (photoImg.getScaledWidth() - slotWidth) / 2;
                    photoImg.top -= (photoImg.getScaledHeight() - slotHeight) / 2;

                    canvas.add(photoImg);

                    // Initialize images object if needed
                    if (!currentFrame.images) {
                        currentFrame.images = {};
                    }

                    // Update state
                    currentFrame.images[slot.index] = {
                        fabricImage: photoImg,
                        photoId: newPhotoId,
                        src: photoSrc,
                        photoPath: newPhotoPath,
                        scaleX: scale,
                        scaleY: scale,
                        left: photoImg.left,
                        top: photoImg.top
                    };

                    // Update 'used' status
                    updatePhotoUsedStatus();
                    filledCount++;

                    // Check if all slots are filled
                    checkIfDone();

                    // Add panning constraints
                    photoImg.on('moving', function () {
                        const rightBound = slot.left;
                        const leftBound = slot.left - (this.getScaledWidth() - slotWidth);
                        const bottomBound = slot.top;
                        const topBound = slot.top - (this.getScaledHeight() - slotHeight);

                        if (this.left > rightBound) this.left = rightBound;
                        if (this.left < leftBound) this.left = leftBound;
                        if (this.top > bottomBound) this.top = bottomBound;
                        if (this.top < topBound) this.top = topBound;
                    });

                    canvas.renderAll();

                    // Show notification after filling all
                    if (filledCount === photosToUse.length) {
                        showNotification(`✅ Berisi ${filledCount} slot otomatis!`, 'success');
                    }
                }, { crossOrigin: 'anonymous' });
            });

            // If not enough photos for all slots
            if (photosToUse.length < emptySlots.length) {
                showNotification(`⚠️ Hanya ${photosToUse.length} foto tersedia untuk ${emptySlots.length} slot kosong`, 'warning');
            }
        }


        async function saveLayouts() {
            const continueBtn = document.getElementById('continue-btn');
            if (continueBtn.disabled) {
                alert('Lengkapi semua slot sebelum melanjutkan!');
                return;
            }

            continueBtn.disabled = true;
            continueBtn.textContent = 'Menyimpan...';

            const finalImages = [];
            const payloadFrameData = [];

            // Generate final images for each frame
            for (let frameIndex = 0; frameIndex < frameData.length; frameIndex++) {
                const frame = frameData[frameIndex];

                // Switch to this frame if not current
                if (frameIndex !== currentFrameIndex) {
                    currentFrameIndex = frameIndex;
                    loadCurrentFrame();

                    // Wait a bit for frame to fully load and render
                    await new Promise(resolve => setTimeout(resolve, 100));
                }

                // Hide slots before generating final image
                if (frame.slotObjects) {
                    Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: false }));
                }

                // CRITICAL: Ensure frame-top is always on top before saving
                const objects = mainCanvas.getObjects();
                for (const obj of objects) {
                    if (obj.id === 'frame-top') {
                        mainCanvas.bringToFront(obj);
                        console.log('Brought frame-top to front before saving');
                        break;
                    }
                }

                // Force render multiple times to ensure all elements are properly positioned
                mainCanvas.renderAll();
                await new Promise(resolve => setTimeout(resolve, 50));
                mainCanvas.renderAll();
                await new Promise(resolve => setTimeout(resolve, 50));

                // Calculate multiplier to get exactly 600x1800 output (important for sticker positioning!)
                const targetWidth = 600;
                const currentWidth = mainCanvas.width;
                const multiplier = targetWidth / currentWidth;

                console.log(`Canvas: ${currentWidth}x${mainCanvas.height}, multiplier for 600px width: ${multiplier.toFixed(2)}`);

                // Generate final image with exact 600x1800 size
                finalImages.push(mainCanvas.toDataURL({ format: 'png', quality: 0.9, multiplier: multiplier }));

                // Show slots again
                if (frame.slotObjects) {
                    Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: true }));
                }
                mainCanvas.renderAll();

                // Prepare frame data for payload
                const photos = frame.images ? Object.entries(frame.images).map(([slotIndex, imageData]) => {
                    const fabricImage = imageData.fabricImage;
                    const slot = frame.slotObjects[slotIndex];

                    let panX = 0.5;
                    let panY = 0.5;

                    if (fabricImage && slot) {
                        const slotWidth = slot.width;
                        const slotHeight = slot.height;
                        const scaledWidth = fabricImage.getScaledWidth();
                        const scaledHeight = fabricImage.getScaledHeight();

                        const rightBoundX = slot.left;
                        const leftBoundX = slot.left - (scaledWidth - slotWidth);

                        const bottomBoundY = slot.top;
                        const topBoundY = slot.top - (scaledHeight - slotHeight);

                        if (scaledWidth > slotWidth) {
                            panX = (fabricImage.left - leftBoundX) / (rightBoundX - leftBoundX);
                        }

                        if (scaledHeight > slotHeight) {
                            panY = (fabricImage.top - topBoundY) / (bottomBoundY - topBoundY);
                        }
                    }

                    return {
                        slot: parseInt(slotIndex),
                        photoId: imageData.photoId,
                        photoPath: imageData.photoPath,
                        panX: isNaN(panX) ? 0.5 : panX,
                        panY: isNaN(panY) ? 0.5 : panY,
                        left: fabricImage ? fabricImage.left : 0,
                        top: fabricImage ? fabricImage.top : 0,
                        scaleX: fabricImage ? fabricImage.scaleX : 1,
                        scaleY: fabricImage ? fabricImage.scaleY : 1
                    };
                }) : [];

                payloadFrameData.push({
                    frame_id: frame.id,
                    photos: photos
                });
            }

            // Save to backend
            fetch(`${URLROOT}/photo/save-layouts`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    final_images: finalImages,
                    frame_data: payloadFrameData
                })
            })
                .then(response => {
                    if (!response.ok) {
                        // Try to get error message from body
                        return response.json().then(err => {
                            throw new Error(err.message || `HTTP error! status: ${response.status}`);
                        }).catch(() => {
                            // If body is not json or empty
                            throw new Error(`HTTP error! status: ${response.status}`);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Allow navigation for successful save
                        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
                            allowNavigation = true;
                        <?php endif; ?>

                        // Same fade-out animation as select-frame
                        document.body.classList.add('fade-out');

                        setTimeout(() => {
                            window.location.href = `${URLROOT}/photo/decoration/${sessionId}`;
                        }, 500);
                    } else {
                        throw new Error(data.message || 'An unknown error occurred.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Tidak bisa Lanjut ke Dekorasi: ' + error.message);
                    continueBtn.disabled = false;
                    continueBtn.textContent = '🎨 Lanjut ke Dekorasi';
                });
        }
    </script>

    <!-- Preview Modal -->
    <div id="previewModal" class="preview-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3>📸 Preview Photostrip</h3>
                <button onclick="closePreview()" class="btn-close">×</button>
            </div>
            <div class="photostrip-container">
                <div id="photostripPaper" class="photostrip-paper">
                    <div id="previewContent"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
                if (confirm('⚠️ PERINGATAN!\n\nAnda mencoba kembali ke halaman sebelumnya. Layout yang belum disimpan akan hilang.\n\nApakah Anda yakin ingin melanjutkan?')) {
                    allowNavigation = true;
                    window.history.go(-1);
                } else {
                    // Stay on current page
                    window.history.pushState({}, '', currentUrl);
                }
            });

            console.log('Simple back/refresh protection loaded for layout editor');
        <?php endif; ?>
    </script>
</body>

</html>