<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Dekorasi - Hias Photostrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #00BFFF;
            --secondary-color: #E63946;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(120deg, #a1c4fd 0%, #c2e9fb 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --dark-text: #1B365D;
        }

        /* Firefox Scrollbar */
        html {
            scrollbar-width: thin;
            /* "auto" or "thin" */
            scrollbar-color: rgba(252, 182, 159, 1) rgba(255, 255, 255, 0.95);
            /* thumb and track color */
        }

        /* Exact same animation as select-frame */
        html,
        body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            /* Desktop default: App mode */
        }

        /* ========== SKY BACKGROUND WITH CLOUDS ========== */
        body {
            background: linear-gradient(120deg, #c2e9fb 0%, #a1c4fd 50%, #e2d0cb 100%);
            position: relative;
            padding: 20px;
            font-family: 'Roboto Condensed', sans-serif;
            user-select: none;
            display: flex;
            justify-content: center;
            align-items: center;
            box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out;
            min-height: 100vh;
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

        body.fade-out {
            opacity: 0;
        }

        .decoration-container {
            display: grid;
            grid-template-columns: 200px 150px 1fr 200px;
            grid-template-rows: auto 1fr auto;
            grid-template-areas:
                "header header header header"
                "stickers tabs workspace tools"
                "layers tabs workspace tools";
            gap: 10px;
            height: 100%;
            /* Fill the body/viewport */
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
            position: relative;
            z-index: 1;
            /* Ensure above clouds */
            max-width: 1600px;
            /* Match layout_editor */
            margin: 0 auto;
            /* Center if grid */
        }

        .decoration-container.content-fade-out {
            opacity: 0;
        }

        .decoration-container>* {
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
            grid-area: header;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            border-radius: 12px;
            padding: 15px;
            text-align: center;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
            height: fit-content;
        }

        .header-panel::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
            border-radius: 50%;
        }

        .header-panel h1 {
            font-family: 'Orbitron', sans-serif;
            color: white;
            margin: 0 0 5px 0;
            font-size: 1.5rem;
            letter-spacing: 1px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            position: relative;
            z-index: 1;
        }

        .header-panel p {
            margin: 0;
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            position: relative;
            z-index: 1;
        }

        .stickers-panel {
            grid-area: stickers;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 15px;
            padding-top: 25px;
            /* Space for label */
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            overflow: visible !important;
            /* Allow label overlay */
            border: 2px solid #2a5298;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stickers-panel::before {
            content: 'PACKAGES';
            position: absolute;
            top: -12px;
            /* Overflow outside */
            left: 20px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 4px 12px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 6px;
            letter-spacing: 1.5px;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .stickers-panel h3 {
            display: none;
            /* Hide old header */
        }

        .stickers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
            gap: 6px;
            flex-grow: 1;
            overflow-y: auto;
            padding: 5px;
        }

        .sticker-item {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: grab;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            background: rgba(108, 99, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sticker-item:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .sticker-item img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .tabs-panel {
            grid-area: tabs;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 10px;
            padding-top: 25px;
            /* Space for label */
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 8px;
            overflow: visible !important;
            /* Allow label overlay */
            border: 2px solid #2a5298;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .tabs-panel::before {
            content: 'SESSION';
            position: absolute;
            top: -10px;
            /* Overflow outside */
            left: 10px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 2px 8px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.6rem;
            font-weight: 700;
            border-radius: 3px;
            letter-spacing: 1px;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .workspace {
            grid-area: workspace;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 12px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        #photostrip-container {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photostrip-tabs {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .photostrip-tab {
            background: linear-gradient(135deg, #ffffff 0%, #f0f2f5 100%);
            border: 2px solid #2a5298;
            padding: 5px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 0.75rem;
            font-weight: 700;
            transition: all 0.3s ease;
            position: relative;
            padding-top: 15px;
            /* Space for FLIGHT label */
            gap: 2px;
            min-height: 100px;
            /* Ensure consistent height */
            justify-content: center;
        }

        .photostrip-tab::before {
            content: 'FLIGHT';
            position: absolute;
            top: 2px;
            left: 5px;
            font-size: 0.5rem;
            font-weight: 700;
            color: #2a5298;
            opacity: 0.6;
        }

        .photostrip-tab:hover {
            background: linear-gradient(135deg, #e8f0fe 0%, #c3cfe2 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(42, 82, 152, 0.2);
        }

        .photostrip-tab.active {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            border-color: #1e3c72;
            box-shadow: 0 4px 12px rgba(30, 60, 114, 0.4);
        }

        .photostrip-tab.active::before {
            color: rgba(255, 255, 255, 0.7);
        }

        .frame-thumb {
            width: 40px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .photostrip-canvas {
            background: white;
            border: 2px solid #ddd;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            /* 2:6 inch ratio = 1:3 */
            height: 65vh !important;
            /* Force reduced height */
            min-height: 300px;
            /* Prevent shrinking too small */
            max-height: 90% !important;
            /* Ensure it doesn't overflow container */
            width: auto;
            /* Width calculated from aspect ratio */
            aspect-ratio: 1 / 3;
            max-width: 100%;
            margin: auto;
            /* Center in flex container */
        }

        .canvas-inner {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 13px;
        }

        .photostrip-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 2;
            /* Frame on top of photos */
        }

        .photo-layer {
            position: relative;
            z-index: 1;
            /* Photos below frame */
            display: block;
        }

        .photo-slot-container {
            z-index: 1;
            /* Photos below frame */
        }

        .decoration-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 3;
            /* User-added stickers on top of everything */
            pointer-events: none;
        }

        .sticker-element {
            position: absolute;
            cursor: move;
            pointer-events: all;
            border: 2px dashed transparent;
            transition: border-color 0.3s ease;
            user-select: none;
        }

        .sticker-element:hover {
            border-color: var(--primary-color);
        }

        .sticker-element.selected {
            border-color: var(--secondary-color);
            border-style: solid;
        }

        .sticker-element img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
        }

        .resize-handle {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: var(--secondary-color);
            border-radius: 50%;
            cursor: se-resize;
            border: 2px solid white;
        }

        .delete-handle {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 16px;
            height: 16px;
            background: var(--secondary-color);
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        .layout-manager {
            grid-area: layers;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tools-panel {
            grid-area: tools;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            gap: 12px;
        }

        .tools-panel h3 {
            margin: 0 0 5px 0;
            font-family: 'Orbitron', sans-serif;
            color: var(--dark-text);
            font-size: 1rem;
            letter-spacing: 1px;
        }

        .tool-group {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 0px;
            padding: 15px;
        }

        .tool-group h4 {
            margin: 0 0 10px 0;
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .tool-btn {
            width: 100%;
            padding: 8px;
            margin-bottom: 6px;
            border: 2px solid #2a5298;
            border-radius: 6px;
            background: linear-gradient(135deg, #ffffff 0%, #f0f2f5 100%);
            /* Restore Flight Theme */
            color: #2a5298;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            backdrop-filter: blur(5px);
        }

        .tool-btn:hover {
            background: linear-gradient(135deg, #e8f0fe 0%, #c3cfe2 100%);
            transform: translateY(-1px);
        }

        .tool-btn.danger {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        .tool-btn.danger:hover {
            background: #ff4f6d;
            border-color: #ff4f6d;
        }

        .layer-list {
            max-height: 120px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 8px;
            flex-grow: 1;
        }

        .layer-item {
            padding: 6px;
            margin-bottom: 4px;
            background: white;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .layer-item.selected {
            background: var(--primary-color);
            color: white;
        }


        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-finish {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            /* Restore Premium Gradient */
            color: white;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            padding: 14px 18px;
            font-weight: 900;
            letter-spacing: 2px;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.4);
            border-radius: 6px;
            border: none;
        }

        .btn-finish:hover {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(30, 60, 114, 0.5);
        }

        /* Tablet (768px - 1200px) */
        @media (min-width: 768px) and (max-width: 1200px) {

            html,
            body {
                display: block;
                height: auto;
                min-height: 100vh;
                overflow-y: auto;
                /* Allow scroll on tablet */
                padding: 20px;
            }

            .decoration-container {
                grid-template-columns: 220px 1fr;
                grid-template-rows: auto auto 1fr;
                grid-template-areas:
                    "header header"
                    "workspace workspace"
                    "stickers stickers"
                    "tools tools"
                    "tabs layers";
                height: auto;
                min-height: 100vh;
                overflow-y: visible;
            }

            .photostrip-canvas {
                width: auto;
                height: 60vh;
                margin: 0 auto;
            }

            .tabs-panel {
                flex-direction: row;
                overflow-x: auto;
            }

            .photostrip-tabs {
                flex-direction: row;
            }

            .stickers-panel {
                max-height: 300px;
            }
        }

        /* Mobile (< 768px) */
        @media (max-width: 767px) {

            html,
            body {
                display: block;
                padding: 10px;
                height: auto;
                min-height: 100%;
                overflow-y: auto;
                /* Allow scroll on mobile */
            }

            .decoration-container {
                display: flex;
                flex-direction: column;
                height: auto;
                min-height: 100%;
                padding: 10px;
                gap: 15px;
                overflow-y: visible;
            }

            /* Order Changes for Mobile: Header -> Canvas -> Tabs -> Stickers -> Tools -> Layers */
            .header-panel {
                order: 1;
            }

            .workspace {
                order: 2;
                height: auto;
                min-height: 400px;
            }

            .tabs-panel {
                order: 3;
            }

            .stickers-panel {
                order: 4;
            }

            .tools-panel {
                order: 5;
            }

            .layout-manager {
                order: 6;
            }

            .photostrip-canvas {
                height: 50vh !important;
                /* Override desktop 65vh */
                /* Allow substantial height for canvas */
                width: auto;
                max-width: 100%;
                margin: 0 auto !important;
                /* Force center */
                display: block;
                /* Ensure block level for margin auto */
            }

            .stickers-grid {
                grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
                max-height: 150px;
            }

            .photostrip-tabs {
                flex-direction: row;
                overflow-x: auto;
                padding-bottom: 5px;
            }

            .photostrip-tab {
                flex-direction: row;
                min-width: 120px;
                flex-shrink: 0;
                /* Prevent squishing */
            }

            .frame-thumb {
                width: 30px;
                height: 60px;
            }

            .resize-handle {
                width: 24px;
                /* Larger touch target */
                height: 24px;
                right: -10px;
                bottom: -10px;
            }

            .delete-handle {
                width: 24px;
                /* Larger touch target */
                height: 24px;
                top: -10px;
                right: -10px;
                font-size: 14px;
            }
        }

        /* Shared Mobile/Tablet Optimizations for Tools Panel */
        @media (max-width: 1200px) {
            .tools-panel {
                flex-direction: row;
                overflow-x: auto;
                padding: 10px;
                gap: 10px;
                align-items: center;
                white-space: nowrap;
                height: auto;
                min-height: unset;
                /* Reset any min-height */
            }

            .tool-group {
                display: flex;
                flex-direction: row;
                align-items: center;
                gap: 8px;
                padding: 5px;
                min-width: fit-content;
                /* Allow container to size to content */
            }

            .tool-group h4 {
                display: none;
                /* Hide headers to save space */
            }

            .tool-btn {
                width: auto;
                margin-bottom: 0;
                padding: 8px 15px;
                font-size: 0.8rem;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                height: 40px;
            }

            /* Move the last tool group (Finish button) to the far left */
            .tool-group:last-child {
                order: -1;
            }
        }

        .tool-icon {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            fill: currentColor;
            flex-shrink: 0;
        }

        .delete-handle svg {
            width: 14px;
            height: 14px;
            fill: white;
            pointer-events: none;
        }
    </style>
</head>

<body>
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>

    <div class="decoration-container">
        <div class="header-panel">
            <h1>PHOTOBOOTH AIRWAYS</h1>
            <p style="font-size: 0.85rem; letter-spacing: 2px; text-transform: uppercase;">DECORATION CLASS / EDITOR</p>
        </div>

        <div class="stickers-panel">
            <h3>🎭 Pustaka Stiker</h3>
            <div class="stickers-grid" id="stickers-grid">
                <?php if (empty($data['stickers'])): ?>
                    <p style="text-align: center; grid-column: 1 / -1; color: #999; font-size: 0.8rem;">
                        Tidak ada stiker tersedia
                    </p>
                <?php else: ?>
                    <?php foreach ($data['stickers'] as $sticker): ?>
                        <div class="sticker-item" data-sticker-id="<?= $sticker->id ?>"
                            data-sticker-path="<?= $sticker->path ?>">
                            <img src="<?= URLROOT . $sticker->path ?>" alt="<?= $sticker->name ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="layout-manager">
            <h3
                style="margin: 0 0 10px 0; font-family: 'Orbitron', sans-serif; color: var(--dark-text); font-size: 1rem; letter-spacing: 1px;">
                📋 Layer Manager</h3>
            <div class="layer-list" id="layer-list"></div>
        </div>

        <div class="tabs-panel">
            <div class="photostrip-tabs" id="photostrip-tabs">
                <?php foreach ($data['photostrips'] as $index => $photostrip): ?>
                    <div class="photostrip-tab <?= $index === 0 ? 'active' : '' ?>"
                        data-photostrip-id="<?= $photostrip->id ?>">
                        <img src="<?= URLROOT . $photostrip->frame_path ?>" alt="<?= $photostrip->frame_name ?>"
                            class="frame-thumb">
                        <span><?= $photostrip->frame_name ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="workspace">

            <div id="photostrip-container">
                <?php foreach ($data['photostrips'] as $index => $photostrip): ?>
                    <div class="photostrip-canvas" id="canvas-<?= $photostrip->id ?>"
                        style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
                        <div class="canvas-inner">
                            <img src="<?= URLROOT . $photostrip->frame_path ?>" alt="Frame" class="photostrip-background">

                            <?php
                            $layoutData = json_decode($photostrip->layout_data, true) ?: [];
                            // Get actual slot coordinates from frame asset
                            $frameSlotCoordinates = json_decode($photostrip->slot_coordinates ?? '[]', true);

                            // Default slots as fallback if no coordinates in database
                            $defaultSlots = [
                                0 => ['left' => 8.33, 'top' => 6.67, 'width' => 83.33, 'height' => 20],
                                1 => ['left' => 8.33, 'top' => 30, 'width' => 83.33, 'height' => 20],
                                2 => ['left' => 8.33, 'top' => 53.33, 'width' => 83.33, 'height' => 20],
                                3 => ['left' => 8.33, 'top' => 76.67, 'width' => 83.33, 'height' => 20]
                            ];

                            // Use actual coordinates or fallback to defaults
                            $slotsToUse = count($frameSlotCoordinates) > 0 ? $frameSlotCoordinates : $defaultSlots;

                            foreach ($layoutData as $slotIndex => $photo):
                                $slotCoordData = $slotsToUse[$slotIndex] ?? $defaultSlots[0];

                                // Use percentage positioning for responsive design
                                $slot = [
                                    'left' => $slotCoordData['left'],
                                    'top' => $slotCoordData['top'],
                                    'width' => $slotCoordData['width'],
                                    'height' => $slotCoordData['height']
                                ];

                                // Calculate object-position for panning
                                $objectPosition = '50% 50%'; // Default center
                                if (isset($photo['panX']) && isset($photo['panY'])) {
                                    // panX is 0 (left-most) to 1 (right-most) from the user's panning action.
                                    // object-position: 0% aligns the left edge of the image with the left edge of the container.
                                    // object-position: 100% aligns the right edge of the image with the right edge of the container.
                                    // When user pans "left" (sees more of the right side of the image), panX becomes 0.
                                    // This corresponds to object-position: 100%.
                                    // When user pans "right" (sees more of the left side of the image), panX becomes 1.
                                    // This corresponds to object-position: 0%.
                                    // So, we invert the panX value.
                                    $posX = (1 - $photo['panX']) * 100;
                                    $posY = (1 - $photo['panY']) * 100;

                                    // Clamp values between 0 and 100
                                    $posX = max(0, min(100, $posX));
                                    $posY = max(0, min(100, $posY));

                                    $objectPosition = $posX . '% ' . $posY . '%';
                                }
                                ?>
                                <div class="photo-slot-container"
                                    style="position: absolute; left: <?= $slot['left'] ?>%; top: <?= $slot['top'] ?>%; width: <?= $slot['width'] ?>%; height: <?= $slot['height'] ?>%; overflow: hidden; border-radius: 0;">
                                    <img src="<?= URLROOT . $photo['photoPath'] ?>" class="photo-layer" style="width: 100%; height: 100%;
                                                object-fit: cover; 
                                                object-position: <?= $objectPosition ?>;">
                                </div>
                            <?php endforeach; ?>

                            <div class="decoration-layer" id="decoration-<?= $photostrip->id ?>">
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tools-panel">
            <div class="tool-group">
                <h4>Aksi Stiker</h4>
                <button class="tool-btn" onclick="duplicateSelected()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path
                            d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z" />
                    </svg>
                    <span>Duplikasi</span>
                </button>
                <button class="tool-btn" onclick="bringToFront()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path
                            d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zm2 4v-2H3c0 1.1.9 2 2 2zM3 9h2V7H3v2zm12 12h2v-2h-2v2zm4-18H9c-1.11 0-2 .9-2 2v10c0 1.1.89 2 2 2h10c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 12H9V5h10v10zm-8 6h2v-2h-2v2zm-4 0h2v-2H7v2z" />
                    </svg>
                    <span>Depan</span>
                </button>
                <button class="tool-btn" onclick="sendToBack()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path
                            d="M9 7H7v2h2V7zm0 4H7v2h2v-2zm0-8c-1.11 0-2 .9-2 2h2V3zm4 12h-2v2h2v-2zm6-12v2h2c0-1.1-.9-2-2-2zm-6 0h-2v2h2V3zM9 17v-2H7c0 1.1.89 2 2 2h2zm10-4h2v-2h-2v2zm0-4h2V7h-2v2zm0 8c1.1 0 2-.9 2-2h-2v2zM5 7H3v12c0 1.1.89 2 2 2h12v-2H5V7zm10-2h2V3h-2v2zm0 12h2v-2h-2v2z" />
                    </svg>
                    <span>Belakang</span>
                </button>
                <button class="tool-btn danger" onclick="deleteSelected()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z" />
                    </svg>
                    <span>Hapus</span>
                </button>
            </div>

            <div class="tool-group">
                <h4>Reset & Clear</h4>
                <button class="tool-btn danger" onclick="clearCurrentPhotostrip()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path
                            d="M15 16h4v2h-4zm0-8h7v2h-7zm0 4h6v2h-6zM3 18c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2V8H3v10zM14 5h-3l-1-1H6L5 5H2v2h12z" />
                    </svg>
                    <span>Bersihkan</span>
                </button>
                <button class="tool-btn danger" onclick="clearAllDecorations()">
                    <svg class="tool-icon" viewBox="0 0 24 24">
                        <path
                            d="M17.65 6.35C16.2 4.9 14.21 4 12 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08c-.82 2.33-3.04 4-5.65 4-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z" />
                    </svg>
                    <span>Reset</span>
                </button>
            </div>

            <div class="tool-group">
                <button class="btn btn-finish" id="finish-btn" onclick="finishDecorations()"
                    style="width: 100%; margin: 0; padding: 12px; font-size: 0.9rem; display: flex; align-items: center; justify-content: center;">
                    <svg class="tool-icon" style="width: 20px; height: 20px;" viewBox="0 0 24 24">
                        <path
                            d="M19 8h-1V3H6v5H5c-1.66 0-3 1.34-3 3v6h3v4h12v-4h3v-6c0-1.66-1.34-3-3-3zM8 5h8v3H8V5zm8 12v4H8v-4h8zm2-2v-2H6v2H4v-4c0-.55.45-1 1-1h14c.55 0 1 .45 1 1v4h-2z" />
                        <circle cx="18" cy="11.5" r="1" />
                    </svg>
                    <span>Cetak!</span>
                </button>
            </div>
        </div>
    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const photostrips = <?= json_encode($data['photostrips']) ?>;
        let currentPhotostripId = photostrips[0]?.id;
        let selectedSticker = null;
        let decorations = {};
        let stickerCounter = 0;
        let isDragging = false;
        let isResizing = false;

        // Initialize decorations for each photostrip
        photostrips.forEach(photostrip => {
            decorations[photostrip.id] = [];
        });

        document.addEventListener('DOMContentLoaded', () => {
            initializeStickerLibrary();
            initializePhotostripTabs();
            initializeCanvas();
            updateLayerList();
        });

        function initializeStickerLibrary() {
            const stickerItems = document.querySelectorAll('.sticker-item');
            stickerItems.forEach(item => {
                item.addEventListener('click', () => {
                    addStickerToCanvas(item.dataset.stickerPath, item.dataset.stickerId);
                });
            });
        }

        function initializePhotostripTabs() {
            const tabs = document.querySelectorAll('.photostrip-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');

                    // Hide all canvases
                    document.querySelectorAll('.photostrip-canvas').forEach(canvas => {
                        canvas.style.display = 'none';
                    });

                    // Show selected canvas
                    currentPhotostripId = tab.dataset.photostripId;
                    document.getElementById(`canvas-${currentPhotostripId}`).style.display = 'block';

                    // Deselect any selected sticker
                    deselectAllStickers();
                    updateLayerList();
                });
            });
        }

        function initializeCanvas() {
            // Initialize each decoration layer
            photostrips.forEach(photostrip => {
                const decorationLayer = document.getElementById(`decoration-${photostrip.id}`);
                if (decorationLayer) {
                    decorationLayer.addEventListener('click', (e) => {
                        if (e.target === decorationLayer) {
                            deselectAllStickers();
                        }
                    });
                }
            });
        }

        function addStickerToCanvas(stickerPath, stickerId) {
            const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
            const canvasWidth = decorationLayer.offsetWidth;
            const canvasHeight = decorationLayer.offsetHeight;

            stickerCounter++;
            const sticker = {
                id: `sticker-${stickerCounter}`,
                stickerPath: stickerPath,
                stickerAssetId: stickerId,
                x: Math.random() * (canvasWidth > 40 ? canvasWidth - 40 : 0),
                y: Math.random() * (canvasHeight > 40 ? canvasHeight - 40 : 0),
                width: 40,
                height: 40,
                rotation: 0,
                zIndex: stickerCounter
            };

            decorations[currentPhotostripId].push(sticker);
            renderSticker(sticker);
            selectSticker(sticker.id);
            updateLayerList();
        }

        function renderSticker(sticker) {
            const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
            const stickerElement = document.createElement('div');
            stickerElement.className = 'sticker-element';
            stickerElement.id = sticker.id;
            stickerElement.style.left = sticker.x + 'px';
            stickerElement.style.top = sticker.y + 'px';
            stickerElement.style.width = sticker.width + 'px';
            stickerElement.style.height = sticker.height + 'px';
            stickerElement.style.transform = `rotate(${sticker.rotation}deg)`;
            stickerElement.style.zIndex = sticker.zIndex;

            stickerElement.innerHTML = `
                <img src="<?= URLROOT ?>${sticker.stickerPath}" alt="Sticker">
                <div class="resize-handle"></div>
                <div class="delete-handle">
                    <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </div>
            `;

            // Add event listeners (Mouse & Touch)
            stickerElement.addEventListener('mousedown', startDrag);
            stickerElement.addEventListener('touchstart', startDrag, { passive: false });

            stickerElement.addEventListener('click', (e) => {
                e.stopPropagation();
                selectSticker(sticker.id);
            });
            // Handle tap for mobile selection if click doesn't fire due to prevDefault
            stickerElement.addEventListener('touchend', (e) => {
                if (!isDragging && !isResizing) {
                    e.stopPropagation();
                    selectSticker(sticker.id);
                }
            });

            const resizeHandle = stickerElement.querySelector('.resize-handle');
            resizeHandle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                startResize(e, sticker.id);
            });
            resizeHandle.addEventListener('touchstart', (e) => {
                e.stopPropagation();
                startResize(e, sticker.id);
            }, { passive: false });

            const deleteHandle = stickerElement.querySelector('.delete-handle');
            deleteHandle.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteSticker(sticker.id);
            });
            deleteHandle.addEventListener('touchstart', (e) => {
                e.stopPropagation();
                e.preventDefault(); // Prevent phantom clicks
                deleteSticker(sticker.id);
            });

            decorationLayer.appendChild(stickerElement);
        }

        // Helper to get coordinates from mouse or touch
        function getClientPos(e) {
            if (e.touches && e.touches.length > 0) {
                return { x: e.touches[0].clientX, y: e.touches[0].clientY };
            }
            return { x: e.clientX, y: e.clientY };
        }

        function startDrag(e) {
            if (isResizing) return;

            // Only prevent default on touch to prevent scrolling, but allow clicks
            if (e.type === 'touchstart') {
                // We don't preventDefault here to allow 'click' to trigger if it's just a tap
                // specific tap logic is handled in touchend
            }

            isDragging = true;
            const stickerElement = e.currentTarget;
            const stickerId = stickerElement.id;
            const decorationLayer = stickerElement.parentElement;

            selectSticker(stickerId);

            const rect = stickerElement.getBoundingClientRect();
            const parentRect = decorationLayer.getBoundingClientRect();

            const clientPos = getClientPos(e);
            const offsetX = clientPos.x - rect.left;
            const offsetY = clientPos.y - rect.top;

            function drag(e) {
                if (!isDragging) return;
                if (e.type === 'touchmove') e.preventDefault(); // Prevent scrolling while dragging

                const curPos = getClientPos(e);
                const newX = curPos.x - parentRect.left - offsetX;
                const newY = curPos.y - parentRect.top - offsetY;

                stickerElement.style.left = Math.max(0, Math.min(decorationLayer.offsetWidth - stickerElement.offsetWidth, newX)) + 'px';
                stickerElement.style.top = Math.max(0, Math.min(decorationLayer.offsetHeight - stickerElement.offsetHeight, newY)) + 'px';

                // Update sticker data
                const sticker = decorations[currentPhotostripId].find(s => s.id === stickerId);
                if (sticker) {
                    sticker.x = parseInt(stickerElement.style.left);
                    sticker.y = parseInt(stickerElement.style.top);
                }
            }

            function stopDrag() {
                isDragging = false;
                document.removeEventListener('mousemove', drag);
                document.removeEventListener('mouseup', stopDrag);
                document.removeEventListener('touchmove', drag);
                document.removeEventListener('touchend', stopDrag);
            }

            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchmove', drag, { passive: false });
            document.addEventListener('touchend', stopDrag);
        }

        function startResize(e, stickerId) {
            isResizing = true;
            const stickerElement = document.getElementById(stickerId);

            const startPos = getClientPos(e);
            const startX = startPos.x;
            const startY = startPos.y;

            const startWidth = stickerElement.offsetWidth;
            const startHeight = stickerElement.offsetHeight;

            function resize(e) {
                if (!isResizing) return;
                if (e.type === 'touchmove') e.preventDefault();

                const curPos = getClientPos(e);
                const deltaX = curPos.x - startX;
                const deltaY = curPos.y - startY;

                const newWidth = Math.max(20, startWidth + deltaX);
                const newHeight = Math.max(20, startHeight + deltaY);

                stickerElement.style.width = newWidth + 'px';
                stickerElement.style.height = newHeight + 'px';

                // Update sticker data
                const sticker = decorations[currentPhotostripId].find(s => s.id === stickerId);
                if (sticker) {
                    sticker.width = newWidth;
                    sticker.height = newHeight;
                }
            }

            function stopResize() {
                isResizing = false;
                document.removeEventListener('mousemove', resize);
                document.removeEventListener('mouseup', stopResize);
                document.removeEventListener('touchmove', resize);
                document.removeEventListener('touchend', stopResize);
            }

            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);
            document.addEventListener('touchmove', resize, { passive: false });
            document.addEventListener('touchend', stopResize);
        }

        function selectSticker(stickerId) {
            deselectAllStickers();
            const element = document.getElementById(stickerId);
            if (element) {
                element.classList.add('selected');
                selectedSticker = stickerId;
            }
            updateLayerList();
        }

        function deselectAllStickers() {
            document.querySelectorAll('.sticker-element').forEach(el => {
                el.classList.remove('selected');
            });
            selectedSticker = null;
            updateLayerList();
        }

        function deleteSticker(stickerId) {
            const element = document.getElementById(stickerId);
            if (element) {
                element.remove();
                decorations[currentPhotostripId] = decorations[currentPhotostripId].filter(s => s.id !== stickerId);
                if (selectedSticker === stickerId) {
                    selectedSticker = null;
                }
                updateLayerList();
            }
        }

        function deleteSelected() {
            if (selectedSticker) {
                deleteSticker(selectedSticker);
            }
        }

        function duplicateSelected() {
            if (selectedSticker) {
                const originalSticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (originalSticker) {
                    stickerCounter++;
                    const newSticker = {
                        ...originalSticker,
                        id: `sticker-${stickerCounter}`,
                        x: originalSticker.x + 20,
                        y: originalSticker.y + 20,
                        zIndex: stickerCounter
                    };

                    decorations[currentPhotostripId].push(newSticker);
                    renderSticker(newSticker);
                    selectSticker(newSticker.id);
                    updateLayerList();
                }
            }
        }

        function bringToFront() {
            if (selectedSticker) {
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (sticker) {
                    const maxZ = Math.max(...decorations[currentPhotostripId].map(s => s.zIndex)) + 1;
                    sticker.zIndex = maxZ;
                    document.getElementById(selectedSticker).style.zIndex = maxZ;
                    updateLayerList();
                }
            }
        }

        function sendToBack() {
            if (selectedSticker) {
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (sticker) {
                    const minZ = Math.min(...decorations[currentPhotostripId].map(s => s.zIndex)) - 1;
                    sticker.zIndex = minZ;
                    document.getElementById(selectedSticker).style.zIndex = minZ;
                    updateLayerList();
                }
            }
        }

        function clearCurrentPhotostrip() {
            if (confirm('Hapus semua dekorasi dari photostrip ini?')) {
                const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
                decorationLayer.innerHTML = '';
                decorations[currentPhotostripId] = [];
                selectedSticker = null;
                updateLayerList();
            }
        }

        function clearAllDecorations() {
            if (confirm('Hapus semua dekorasi dari semua photostrip?')) {
                photostrips.forEach(photostrip => {
                    const decorationLayer = document.getElementById(`decoration-${photostrip.id}`);
                    decorationLayer.innerHTML = '';
                    decorations[photostrip.id] = [];
                });
                selectedSticker = null;
                updateLayerList();
            }
        }

        function updateLayerList() {
            const layerList = document.getElementById('layer-list');
            const currentDecorations = decorations[currentPhotostripId] || [];

            layerList.innerHTML = '';

            if (currentDecorations.length === 0) {
                layerList.innerHTML = '<div style="text-align: center; color: #999; font-style: italic;">Tidak ada stiker</div>';
                return;
            }

            // Sort by z-index (highest first)
            const sortedDecorations = [...currentDecorations].sort((a, b) => b.zIndex - a.zIndex);

            sortedDecorations.forEach(sticker => {
                const layerItem = document.createElement('div');
                layerItem.className = 'layer-item';
                if (selectedSticker === sticker.id) {
                    layerItem.classList.add('selected');
                }
                layerItem.innerHTML = `
                    <span>Stiker ${sticker.id.split('-')[1]}</span>
                    <span>Z:${sticker.zIndex}</span>
                `;
                layerItem.addEventListener('click', () => selectSticker(sticker.id));
                layerList.appendChild(layerItem);
            });
        }

        async function finishDecorations() {
            const decorationData = {};
            const activeCanvas = document.querySelector('.photostrip-canvas:not([style*="display: none"])');
            if (!activeCanvas) {
                alert("Error: Tidak dapat menemukan kanvas aktif.");
                return;
            }

            // [PERBAIKAN FINAL] Dapatkan ukuran konten yang sebenarnya dari .canvas-inner
            // Ini adalah elemen yang paling akurat untuk dijadikan referensi karena tidak memiliki border/padding sendiri.
            const contentArea = activeCanvas.querySelector('.canvas-inner');
            if (!contentArea) {
                alert("Error: Tidak dapat menemukan .canvas-inner.");
                return;
            }

            const onScreenWidth = contentArea.offsetWidth;
            const onScreenHeight = contentArea.offsetHeight;

            // Debug logging
            console.log('=== DECORATION FINALIZE DEBUG ===');
            console.log('Canvas dimensions:', onScreenWidth, 'x', onScreenHeight);
            console.log('Content area:', contentArea);

            // Process each photostrip
            for (const photostrip of photostrips) {
                const decorationsForStrip = decorations[photostrip.id] || [];

                // Log sticker data for debugging
                if (decorationsForStrip.length > 0) {
                    console.log(`Photostrip ${photostrip.id} stickers:`, decorationsForStrip);
                }

                // Load sticker images dan hitung ukuran actual (maintain aspect ratio)
                const processedStickers = await Promise.all(decorationsForStrip.map(async (sticker) => {
                    // Load image untuk mendapatkan ukuran asli
                    const img = new Image();
                    img.src = '<?= URLROOT ?>' + sticker.stickerPath;
                    await new Promise((resolve) => {
                        img.onload = resolve;
                        img.onerror = resolve; // Continue even if load fails
                    });

                    const stickerWidth = img.naturalWidth || 89;  // Fallback ke ukuran diketahui
                    const stickerHeight = img.naturalHeight || 15;

                    // [FIXED] Konversi position ke 600x1800 DULU sebelum hitung centering
                    const bboxX_600 = (sticker.x / onScreenWidth) * 600;
                    const bboxY_600 = (sticker.y / onScreenHeight) * 1800;
                    const bboxWidth_600 = (sticker.width / onScreenWidth) * 600;
                    const bboxHeight_600 = (sticker.height / onScreenHeight) * 1800;

                    // Hitung ukuran actual sticker (maintain aspect ratio) dalam bounding box (600x1800)
                    // Ini simulates CSS object-fit: contain
                    const stickerRatio = stickerWidth / stickerHeight;
                    const bboxRatio_600 = bboxWidth_600 / bboxHeight_600;

                    let actualWidth_600, actualHeight_600;
                    if (stickerRatio > bboxRatio_600) {
                        // Width is constraint - sticker lebih lebar dari box
                        actualWidth_600 = bboxWidth_600;
                        actualHeight_600 = bboxWidth_600 / stickerRatio;
                    } else {
                        // Height is constraint - sticker lebih tinggi dari box
                        actualHeight_600 = bboxHeight_600;
                        actualWidth_600 = bboxHeight_600 * stickerRatio;
                    }

                    // [FIXED] Hitung centering offset LANGSUNG di 600x1800 coordinate system
                    const centerOffsetX_600 = (bboxWidth_600 - actualWidth_600) / 2;
                    const centerOffsetY_600 = (bboxHeight_600 - actualHeight_600) / 2;

                    // [FIXED] Position akhir di 600x1800 (bbox position + centering offset)
                    const finalX_600 = bboxX_600 + centerOffsetX_600;
                    const finalY_600 = bboxY_600 + centerOffsetY_600;

                    console.log(`═══════════════════════════════════════════════════════════`);
                    console.log(`Sticker ID: ${sticker.id}`);
                    console.log(`File natural size: ${stickerWidth} x ${stickerHeight} px`);
                    console.log(`File aspect ratio: ${stickerRatio.toFixed(4)}`);
                    console.log(``);
                    console.log(`VIEWPORT (on-screen):`);
                    console.log(`  Canvas size: ${Math.round(onScreenWidth)} x ${Math.round(onScreenHeight)} px`);
                    console.log(`  Bounding box: x=${Math.round(sticker.x)}, y=${Math.round(sticker.y)}, w=${Math.round(sticker.width)} x ${Math.round(sticker.height)}`);
                    console.log(`  Bounding box ratio: ${(sticker.width / sticker.height).toFixed(4)}`);
                    console.log(``);
                    console.log(`600x1800 OUTPUT (final):`);
                    console.log(`  Bounding box: x=${Math.round(bboxX_600)}, y=${Math.round(bboxY_600)}, w=${Math.round(bboxWidth_600)} x ${Math.round(bboxHeight_600)}`);
                    console.log(`  Bounding box ratio: ${bboxRatio_600.toFixed(4)}`);
                    console.log(`  Actual sticker: w=${Math.round(actualWidth_600)} x ${Math.round(actualHeight_600)}`);
                    console.log(`  Centering offset: x=${Math.round(centerOffsetX_600)}, y=${Math.round(centerOffsetY_600)}`);
                    console.log(`  FINAL POSITION: x=${Math.round(finalX_600)}, y=${Math.round(finalY_600)}`);
                    console.log(`═══════════════════════════════════════════════════════════`);

                    return {
                        ...sticker,
                        // Semua koordinat LANGSUNG dalam 600x1800
                        x: finalX_600,
                        y: finalY_600,
                        width: actualWidth_600,
                        height: actualHeight_600,
                        // DEBUG INFO - simpan calculation details
                        _debug: {
                            natural_size: `${stickerWidth}x${stickerHeight}`,
                            viewport_canvas: `${Math.round(onScreenWidth)}x${Math.round(onScreenHeight)}`,
                            viewport_bbox: `x=${Math.round(sticker.x)}, y=${Math.round(sticker.y)}, w=${Math.round(sticker.width)}x${Math.round(sticker.height)}`,
                            bbox_600: `x=${Math.round(bboxX_600)}, y=${Math.round(bboxY_600)}, w=${Math.round(bboxWidth_600)}x${Math.round(bboxHeight_600)}`,
                            actual_600: `w=${Math.round(actualWidth_600)}x${Math.round(actualHeight_600)}`,
                            centering_offset_600: `x=${Math.round(centerOffsetX_600)}, y=${Math.round(centerOffsetY_600)}`,
                            final_pos_600: `x=${Math.round(finalX_600)}, y=${Math.round(finalY_600)}`
                        }
                    };
                }));

                // Kirim data dengan koordinat standar 600x1800 + debug info
                decorationData[photostrip.id] = {
                    canvas_context: {
                        width: 600,
                        height: 1800
                    },
                    _debug_viewport: {
                        width: Math.round(onScreenWidth),
                        height: Math.round(onScreenHeight),
                        timestamp: new Date().toISOString()
                    },
                    stickers: processedStickers
                };
            }

            // Proses pengiriman (fetch) tidak perlu diubah
            console.log(`══════════════════ SENDING TO BACKEND ══════════════════`);
            console.log(`Session ID: ${sessionId}`);
            console.log(`Decoration Data:`, JSON.stringify(decorationData, null, 2));
            console.log(`═══════════════════════════════════════════════════════════`);

            fetch('<?= URLROOT ?>/photo/save-decorations', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    decorations: decorationData
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Allow navigation for successful save
                        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
                            allowNavigation = true;
                        <?php endif; ?>

                        // Same fade-out animation as select-frame
                        document.body.classList.add('fade-out');

                        setTimeout(() => {
                            window.location.href = `<?= URLROOT ?>/photo/finalize/${sessionId}`;
                        }, 500);
                    } else {
                        alert('Gagal menyimpan dekorasi: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menyimpan dekorasi.');
                });
        }
        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (selectedSticker) {
                switch (e.key) {
                    case 'Delete':
                    case 'Backspace':
                        e.preventDefault();
                        deleteSelected();
                        break;
                    case 'd':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            duplicateSelected();
                        }
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        moveSelected(0, -1);
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        moveSelected(0, 1);
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        moveSelected(-1, 0);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        moveSelected(1, 0);
                        break;
                }
            }
        });

        function moveSelected(deltaX, deltaY) {
            if (selectedSticker) {
                const element = document.getElementById(selectedSticker);
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (element && sticker) {
                    const decorationLayer = element.parentElement;
                    sticker.x = Math.max(0, Math.min(decorationLayer.offsetWidth - sticker.width, sticker.x + deltaX));
                    sticker.y = Math.max(0, Math.min(decorationLayer.offsetHeight - sticker.height, sticker.y + deltaY));
                    element.style.left = sticker.x + 'px';
                    element.style.top = sticker.y + 'px';
                }
            }
        }

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
                if (confirm('⚠️ PERINGATAN!\n\nAnda mencoba kembali ke halaman sebelumnya. Dekorasi yang belum disimpan akan hilang.\n\nApakah Anda yakin ingin melanjutkan?')) {
                    allowNavigation = true;
                    window.history.go(-1);
                } else {
                    // Stay on current page
                    window.history.pushState({}, '', currentUrl);
                }
            });

            console.log('Simple back/refresh protection loaded for decoration editor');
        <?php endif; ?>
    </script>
</body>

</html>