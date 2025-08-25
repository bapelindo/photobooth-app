<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hias Fotomu!</title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/public/css/photobooth.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .editor-container {
            display: flex;
            gap: 1.5rem;
            width: 95%;
            max-width: 1200px;
            height: 90vh;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        .editor-main {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .editor-canvas-container {
            flex-grow: 1;
            background: #fff;
            border-radius: 15px;
            box-shadow: inset 0 0 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 1rem;
        }
        .sticker-panel {
            width: 280px;
            flex-shrink: 0;
            background: var(--panel-bg);
            border-radius: 15px;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }
        .sticker-panel h3 {
            color: var(--accent-color);
            text-align: center;
            margin-bottom: 1rem;
        }
        .asset-list {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            overflow-y: auto;
            flex-grow: 1;
        }
        .sticker-item {
            width: 80px;
            height: 80px;
            background: rgba(255, 255, 255, 0.9);
        }
        .action-buttons {
            display: flex;
            justify-content: center;
        }
    </style>
</head>
<body>

    <div class="editor-container">
        <div class="sticker-panel">
            <h3>Pilih Stiker</h3>
            <div class="asset-list">
                <?php foreach ($stickers as $sticker): ?>
                    <img src="<?= URLROOT; ?>/public<?= htmlspecialchars($sticker->path); ?>" class="asset-item sticker-item" alt="<?= htmlspecialchars($sticker->name); ?>">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="editor-main">
            <div class="editor-canvas-container">
                <canvas id="editor-canvas"></canvas>
            </div>
            <div class="action-buttons">
                 <button id="save-photo-btn" class="action-button">Simpan & Selesai</button>
            </div>
        </div>
    </div>

    <script>
        const photoUrl = '<?= URLROOT . htmlspecialchars($photo->file_path); ?>';
    </script>
    <script src="<?= URLROOT; ?>/public/js/fabric.js"></script> 
    <script src="<?= URLROOT; ?>/public/js/editor.js"></script>

</body>
</html>