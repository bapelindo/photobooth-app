<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Fotomu!</title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/public/css/photobooth.css">
    <style>
        .editor-container {
            display: flex;
            gap: 2rem;
            padding: 2rem;
        }
        .editor-main {
            flex-grow: 1;
        }
        .editor-canvas-container {
            position: relative;
            width: 100%;
            aspect-ratio: 4 / 3;
            background: #eee;
        }
        #editor-canvas {
            width: 100%;
            height: 100%;
        }
        .sticker-panel {
            width: 300px;
        }
    </style>
</head>
<body>

    <div class="editor-container">
        <div class="editor-main">
            <h1>Edit Fotomu</h1>
            <div class="editor-canvas-container">
                <canvas id="editor-canvas"></canvas>
            </div>
            <button id="save-photo-btn" class="action-button">Simpan Foto</button>
        </div>
        <div class="sticker-panel">
            <h3>Pilih Stiker</h3>
            <div class="asset-list">
                <?php foreach ($stickers as $sticker): ?>
                    <img src="<?= URLROOT; ?>/public<?= htmlspecialchars($sticker->file_path); ?>" class="asset-item sticker-item" data-path="<?= htmlspecialchars($sticker->file_path); ?>" alt="<?= htmlspecialchars($sticker->name); ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const photoUrl = '<?= URLROOT; ?>/public<?= htmlspecialchars($photo->file_path); ?>';
    </script>
    <script src="<?= URLROOT; ?>/public/js/editor.js"></script>
</body>
</html>