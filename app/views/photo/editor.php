    <!DOCTYPE html>
    <html lang="id">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Hias Fotomu! ✨</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
        
        <link rel="stylesheet" href="<?= URLROOT; ?>/css/editor.css?v=<?= time(); ?>">
    </head>
    <body>
        <div class="photobooth-container">
            <div class="title-panel">
                <h1>Hias Foto</h1>
            </div>
    
            <main class="main-stage">
                 <?php foreach($data['photostrip_urls'] as $index => $url): ?>
                    <div class="photostrip-canvas-container" data-canvas-index="<?= $index ?>">
                        <canvas id="canvas-<?= $index ?>"></canvas>
                    </div>
                <?php endforeach; ?>
            </main>
    
            <aside class="sidebar">
                <div class="sticker-panel">
                    <h3>Pilih Stiker</h3>
                    <div class="sticker-list">
                        <?php foreach ($data['stickers'] as $sticker): ?>
                            <img src="<?= URLROOT . htmlspecialchars($sticker->path); ?>" class="sticker-item" alt="<?= htmlspecialchars($sticker->name); ?>">
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="action-panel">
                    <button id="save-photo-btn">🎉 Simpan & Selesai</button>
                </div>
            </aside>
        </div>
    
        <script src="<?= URLROOT; ?>/js/fabric.js"></script>
        <script>
            const PHOTOSTRIP_URLS = <?= json_encode($data['photostrip_urls']); ?>;
            const SAVE_URL = '<?= URLROOT; ?>/photo/ajax_save_final_photostrip';
            const TRANSACTION_ID = '<?= $data['transaction_id']; ?>';
            const URLROOT = '<?= URLROOT; ?>';
        </script>
        <script src="<?= URLROOT; ?>/js/editor.js"></script>
    </body>
    </html>