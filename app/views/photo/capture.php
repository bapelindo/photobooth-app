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
        :root {
            --primary-color: #6C63FF; --secondary-color: #FF6584; --accent-color: #FFD166;
            --card-bg: #FFFFFF; --dark-text: #333; --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }
        body {
            font-family: var(--font-main); background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center;
            min-height: 100vh; box-sizing: border-box; overflow: hidden; opacity: 0;
            animation: fadeIn 0.5s ease-in forwards;
        }
        .photobooth-container {
            display: grid; 
            grid-template-columns: 200px 1fr; 
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
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .info-panel h1 { font-family: var(--font-display); color: var(--primary-color); margin: 0; font-size: 2rem; }
        .info-panel p { margin: 5px 0 0; color: #555; }
        .info-panel #retake-count { font-weight: bold; color: var(--secondary-color); }

        .main-stage {
            grid-column: 2 / 3; 
            grid-row: 2 / 3;
            position: relative; display: flex;
            justify-content: center; align-items: center; background: #000;
            border-radius: 20px; overflow: hidden;
        }
        .controls-panel { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); display: flex; gap: 15px; padding: 10px; background: rgba(0, 0, 0, 0.4); border-radius: 50px; z-index: 5; }
        .action-button { font-family: var(--font-display); font-size: 1.2rem; padding: 15px 30px; border: none; border-radius: 50px; cursor: pointer; color: var(--dark-text); transition: all 0.3s ease; display: flex; align-items: center; gap: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .action-button:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,0.3); }
        #capture-btn { background-color: var(--accent-color); }
        #keep-btn { background-color: #28a745; color: white; }
        #retake-photo-btn { background-color: #ffc107; }
        #finish-btn { background-color: var(--primary-color); color: white; }
        .action-button:disabled { background-color: #ccc; cursor: not-allowed; transform: none; }
        #live-preview { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); transition: filter 0.3s ease-out; }
        #capture-canvas { display: none; }
        #countdown { position: absolute; font-family: var(--font-display); font-size: 200px; color: var(--accent-color); text-shadow: 5px 5px 10px rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 10; animation: countdown-pop 1s infinite; }
        @keyframes countdown-pop { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.1); } }
        body.fade-out { opacity: 0; transition: opacity 0.4s ease-out; }

        /* --- MODIFIED: Styling for Filter Dropdown --- */
        .filter-controls {
            background: var(--card-bg);
            padding: 10px 20px;
            border-radius: 20px;
            text-align: center;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .filter-controls h4 {
            margin-top: 0;
            margin-bottom: 10px;
            font-family: var(--font-display);
            color: var(--primary-color);
        }
        #filter-select {
            padding: 8px 12px;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            font-family: var(--font-main);
            font-size: 1rem;
            cursor: pointer;
            background-color: white;
        }
        #filter-select:focus {
            outline: none;
            box-shadow: 0 0 5px var(--primary-color);
        }
        /* --- END OF MODIFICATION --- */

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
            <div class="info-panel capture-info-panel">
                <h1 id="info-text">Siap Berpose!</h1>
                <p>Sisa Kesempatan Ulang: <span id="retake-count"><?= $data['retakes_left']; ?></span> ❤️</p>
            </div>
            
            <?php if (!empty($data['filters'])): ?>
            <div class="filter-controls">
                <h4>Pilih Filter</h4>
                <select id="filter-select">
                    <option value="none">Normal</option>
                    <?php foreach($data['filters'] as $filter): ?>
                        <option value="<?= htmlspecialchars($filter->path); ?>">
                            <?= htmlspecialchars($filter->name); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
        </div>

        <div class="main-stage capture-main-stage">
            <video id="live-preview" autoplay playsinline muted></video>
            <div id="countdown"></div>
            <canvas id="capture-canvas"></canvas>
            
            <div class="controls-panel">
                <button id="capture-btn" class="action-button">📸 Ambil Foto</button>
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