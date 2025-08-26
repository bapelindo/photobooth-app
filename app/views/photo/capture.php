<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saatnya Berpose! ✨</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html { overflow: hidden; }
        :root {
            --primary-color: #6C63FF; --secondary-color: #FF6584; --accent-color: #FFD166;
            --card-bg: #FFFFFF; --dark-text: #333; --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }
        body {
            font-family: var(--font-main); background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0; padding: 5px; display: flex; justify-content: center; align-items: center;
            min-height: 100vh; box-sizing: border-box; overflow: hidden; opacity: 0;
            animation: fadeIn 0.5s ease-in forwards;
        }
        .photobooth-container {
            display: grid; 
            grid-template-columns: auto 1fr; /* Changed back to auto */
            grid-template-rows: auto 1fr;
            gap: 20px; width: 100%; max-width: 1200px; height: 90vh;
            background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);
            border-radius: 20px; padding: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            opacity: 0; transform: scale(0.98);
            animation: fadeIn 0.5s ease-out forwards;
        }
        .capture-sidebar, .top-panel, .capture-main-stage {
            opacity: 0;
            animation: fadeInElements 1s ease-out 1s forwards; 
        }
        @keyframes fadeIn { to { opacity: 1; transform: scale(1); } }
        @keyframes fadeInElements { to { opacity: 1; } }
        
.sidebar {
            grid-row: 1 / 3; background-color: rgba(255, 255, 255, 0.7);
            border-radius: 20px; padding: 20px; display: flex; flex-direction: column;
            gap: 15px; overflow: hidden;
            background-image: url('<?= isset($data['selected_frame']) ? URLROOT . htmlspecialchars($data['selected_frame']->path) : '' ?>');
            background-size: cover; background-position: center; background-repeat: no-repeat;
            max-height: 100%; /* Ensure it doesn't exceed container height */
            max-width: 100%;  /* Ensure it doesn't exceed container width */
            aspect-ratio: 2 / 6; /* Maintain 2x6 inch aspect ratio */
            height: auto;
            width: auto;
        }
        .preview-slot {
            width: 100%; aspect-ratio: 4 / 3; background: rgba(224, 232, 240, 0.8);
            border: 3px dashed #c0d1e6; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; font-size: 0.9em;
            color: #555; overflow: hidden; transition: transform 0.2s ease;
        }
        .preview-slot img { width: 100%; height: 100%; object-fit: cover; }
        .preview-slot.active { border-color: var(--secondary-color); transform: scale(1.05); }

        .top-panel {
            grid-column: 2 / 3;
            display: flex;
            gap: 20px;
            align-items: stretch;
        }
                .info-panel {
            flex-grow: 1;
            text-align: center; padding: 10px;
            background-color: var(--card-bg); border-radius: 20px;
            display: flex; /* Changed to flex */
            flex-direction: row; /* Changed to row */
            align-items: center; /* Vertically align them in the middle */
            justify-content: center; /* Horizontally center them */
            gap: 50px; /* Add a small gap between h1 and p */
        }
        .info-panel h1, .info-panel p {
            vertical-align: top; 
            line-height: 20px;
            margin: 0;
        }
        .info-panel h1 { font-family: var(--font-display); color: var(--primary-color); font-size: 1.5rem; }
        .info-panel p { color: #555; }
        .info-panel #retake-count { font-weight: bold; color: var(--secondary-color); }

        .main-stage {
            grid-column: 2 / 3; 
            grid-row: 2 / 3;
            position: relative; display: flex;
            justify-content: center; align-items: center; background: #000;
            border-radius: 20px; overflow: hidden;
            aspect-ratio: 16 / 9; /* Re-added this */
            max-width: 100%; /* Added this */
            max-height: 100%; /* Added this */
        }
        .controls-panel { 
            position: absolute; 
            bottom: 20px; 
            left: 50%; 
            transform: translateX(-50%); 
            display: flex; 
            align-items: center;
            gap: 15px; 
            padding: 10px; 
            background: rgba(0, 0, 0, 0.4); 
            border-radius: 50px; 
            z-index: 5; 
        }
        .action-button { font-family: var(--font-display); font-size: 1rem; padding: 10px 20px; border: none; border-radius: 50px; cursor: pointer; color: var(--dark-text); transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .action-button:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.3); }
        #capture-btn { background-color: var(--accent-color); }
        #keep-btn { background-color: #28a745; color: white; }
        #retake-photo-btn { background-color: #ffc107; }
        #finish-btn { background-color: var(--primary-color); color: white; }
        .action-button:disabled { background-color: #ccc; cursor: not-allowed; transform: none; }
        #live-preview { width: 100%; height: 100%; object-fit: contain; transform: scaleX(-1); transition: filter 0.3s ease-out; }
        #capture-canvas { display: none; }
        #countdown { position: absolute; font-family: var(--font-display); font-size: 200px; color: var(--accent-color); text-shadow: 5px 5px 10px rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 10; animation: countdown-pop 1s infinite; }
        @keyframes countdown-pop { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        body.fade-out { opacity: 0; transition: opacity 0.4s ease-out; }

        .filter-container { position: relative; }
        #filter-btn { background-color: var(--primary-color); color: white; }
        .filter-options {
            display: none;
            position: absolute;
            bottom: 120%; /* Position above the button */
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--card-bg);
            border-radius: 15px;
            padding: 10px;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.2);
            z-index: 10;
            width: 200px;
            max-height: 250px; /* Re-added max-height */
            overflow-y: auto; /* Re-added overflow-y */
            /* Hide scrollbar for WebKit browsers */
            -webkit-overflow-scrolling: touch; /* For smooth scrolling on iOS */
        }
        /* Hide scrollbar for WebKit browsers */
        .filter-options::-webkit-scrollbar {
            display: none;
        }
        /* Hide scrollbar for Firefox */
        .filter-options {
            scrollbar-width: none; /* Firefox */
        }
        .filter-option {
            padding: 12px 15px;
            cursor: pointer;
            transition: background-color 0.2s ease;
            border-radius: 10px;
            font-weight: 600;
        }
        .filter-option:hover { background-color: #f0f0f0; }
        .filter-option.active { background-color: var(--accent-color); color: var(--dark-text); }

        /* Default filter button positioning for desktop (not fullscreen) */
        .controls-panel .filter-container {
            margin-left: auto; /* Pushes filter button to the right by default */
        }

        /* Styles for fullscreen (min-width: 1600px) */
        @media (min-height: 600px) {

            .controls-panel .filter-container {
                margin-left: 0; /* Reset margin for fullscreen */
            }
            .info-panel {
            flex-grow: 1;
            text-align: center; padding: 25px;
            background-color: var(--card-bg); border-radius: 20px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .info-panel h1 { font-family: var(--font-display); color: var(--primary-color); margin: 0; font-size: 2rem; }
        .info-panel p { margin: 0px 0 0; color: #555; }
        .info-panel #retake-count { font-weight: bold; color: var(--secondary-color); }
        .info-panel h1, .info-panel p {
            vertical-align: baseline; 
            line-height: 0px;
            margin: 0;
        }

        }
    </style>
</head>
<body>
    <div class="photobooth-container">
        <aside class="sidebar capture-sidebar">
            <?php for ($i = 0; $i < $data['package']->photo_limit; $i++): ?>
                <div class="preview-slot" id="slot-<?= $i; ?>">Slot <?= $i + 1; ?></div>
            <?php endfor; ?>
        </aside>

        <div class="top-panel">
            <div class="info-panel">
                <h1 id="info-text"></h1>
                <p> Sisa Kesempatan Ulang: <span id="retake-count"><?= $data['retakes_left']; ?></span> ❤️</p>
            </div>
        </div>

        <div class="main-stage capture-main-stage">
            <video id="live-preview" autoplay playsinline muted></video>
            <div id="countdown"></div>
            <canvas id="capture-canvas"></canvas>
            
            <div class="controls-panel">
                <button id="capture-btn" class="action-button">📸 Ambil Foto</button>
                
                <?php if (!empty($data['filters'])): ?>
                <div class="filter-container">
                    <button id="filter-btn" class="action-button">🎨 Pilih Filter</button>
                    <div class="filter-options">
                        <div class="filter-option active" data-filter="none">Normal</div>
                        <?php foreach($data['filters'] as $filter): ?>
                            <div class="filter-option" data-filter="<?= htmlspecialchars($filter->path); ?>">
                                <?= htmlspecialchars($filter->name); ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div id="photo-controls" style="display: none;">
                    <button id="keep-btn" class="action-button">👍 Simpan</button>
                    <button id="retake-photo-btn" class="action-button">🔄 Ulang</button>
                </div>
                <button id="finish-btn" class="action-button" style="display: none;">🎉 Proses & Selesai!</button>
            </div>
        </div>
    </div>

    <script>
        const PHOTO_LIMIT = <?= $data['package']->photo_limit; ?>;
        let RETAKES_LEFT = <?= $data['retakes_left']; ?>;
        const TRANSACTION_ID = '<?= $data['transaction_id']; ?>';
        const FRAME_PATH = '<?= isset($data['selected_frame']) ? $data['selected_frame']->path : "" ?>';
        const URLROOT = '<?= URLROOT; ?>';
    </script>
    <script src="<?= URLROOT; ?>/js/photobooth.js"></script>
</body>
</html>
