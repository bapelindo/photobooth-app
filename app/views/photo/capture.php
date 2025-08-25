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
        /* === CSS BARU UNTUK REDESIGN === */
        :root {
            --bg-color: #F0F4F8;
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --accent-color: #FFD166;
            --dark-text: #333;
            --light-text: #FFF;
            --border-radius: 20px;
            --font-main: 'Poppins', sans-serif;
            --font-display: 'Fredoka One', cursive;
        }

        body {
            font-family: var(--font-main);
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            box-sizing: border-box;
            overflow: hidden;
        }

        .photobooth-container {
            display: grid;
            grid-template-columns: 200px 1fr;
            grid-template-rows: auto 1fr;
            gap: 20px;
            width: 100%;
            max-width: 1200px;
            height: 90vh;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
        }

        /* --- Panel Samping (Pratinjau Foto) --- */
        .sidebar {
            grid-row: 1 / 3;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: var(--border-radius);
            padding: 15px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            overflow: hidden;
        }

        .preview-slot {
            /* --- PERBAIKAN: Gunakan Flexbox untuk distribusi ruang --- */
            flex-grow: 1; /* Biarkan slot mengisi ruang yang tersedia */
            min-height: 0; /* Penting untuk flex-grow di container yang tinggi-nya terbatas */
            width: 100%;
            background: #e0e8f0;
            border: 3px dashed #c0d1e6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            color: #555;
            overflow: hidden;
            transition: transform 0.2s ease;
        }
        .preview-slot img {
            width: 100%; height: 100%; object-fit: cover;
        }
        .preview-slot.active {
            border-color: var(--secondary-color);
            transform: scale(1.05);
        }

        /* --- Panel Atas (Informasi) --- */
        .info-panel {
            grid-column: 2 / 3;
            text-align: center;
            padding: 10px;
            background-color: var(--light-text);
            border-radius: var(--border-radius);
        }
        .info-panel h1 {
            font-family: var(--font-display);
            color: var(--primary-color);
            margin: 0;
            font-size: 2rem;
        }
        .info-panel p { margin: 5px 0 0; color: #555; }
        .info-panel #retake-count { font-weight: bold; color: var(--secondary-color); }

        /* --- Area Kamera Utama --- */
        .main-stage {
            grid-column: 2 / 3;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            border-radius: var(--border-radius);
            overflow: hidden;
        }
        #live-preview {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        #capture-canvas { display: none; }
        #selected-frame-preview {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            pointer-events: none;
        }
        #countdown {
            position: absolute;
            font-family: var(--font-display);
            font-size: 200px;
            color: var(--accent-color);
            text-shadow: 5px 5px 10px rgba(0,0,0,0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 10;
            animation: countdown-pop 1s infinite;
        }
        @keyframes countdown-pop {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        /* --- Tombol-tombol Aksi --- */
        .controls-panel {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            padding: 10px;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 50px;
            z-index: 5;
        }
        
        .action-button {
            font-family: var(--font-display);
            font-size: 1.2rem;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            color: var(--dark-text);
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        .action-button:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        
        #capture-btn { background-color: var(--accent-color); }
        #keep-btn { background-color: #28a745; color: var(--light-text); }
        #retake-photo-btn { background-color: #ffc107; }
        #finish-btn { background-color: var(--primary-color); color: var(--light-text); }
        
        .action-button:disabled {
            background-color: #ccc;
            cursor: not-allowed;
            transform: none;
        }
    </style>
</head>
<body>
    <div class="photobooth-container">
        <div class="sidebar">
            <?php for ($i = 0; $i < $data['package']->photo_limit; $i++): ?>
                <div class="preview-slot" id="slot-<?= $i; ?>">Slot <?= $i + 1; ?></div>
            <?php endfor; ?>
        </div>

        <div class="info-panel">
            <h1 id="info-text">Siap Berpose!</h1>
            <p>Sisa Kesempatan Ulang: <span id="retake-count"><?= $data['retakes_left']; ?></span> ❤️</p>
        </div>

        <div class="main-stage">
            <video id="live-preview" autoplay playsinline></video>
            <?php if (isset($data['selected_frame']) && $data['selected_frame']): ?>
                <img id="selected-frame-preview" src="<?= URLROOT . htmlspecialchars($data['selected_frame']->path); ?>" alt="Selected Frame">
            <?php endif; ?>
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