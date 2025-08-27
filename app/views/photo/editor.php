<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hias Fotomu! ✨</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= URLROOT; ?>/css/editor.css">
</head>
<body>
    <div class="photobooth-container">
        
        <div class="title-panel">
            <h1>Hias Fotomu!</h1>
        </div>

        <main class="main-stage">
            <div class="canvas-container">
                 <canvas id="editor-canvas"></canvas>
            </div>
            <div id="trash-can">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 6h18m-2 0v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m-6 5v6m4-6v6" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
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
                 <button id="save-photo-btn" class="action-button">🎉 Simpan & Lanjutkan</button>
            </div>
        </aside>

    </div>

    <script>
        // Data yang dibutuhkan sekarang lebih sederhana dan konsisten
        const photostripUrl = '<?= $data['photostrip_url']; ?>';
        const saveUrl = '<?= URLROOT; ?>/photo/ajax_save_final_photostrip';
    </script>
    <script src="<?= URLROOT; ?>/js/fabric.js"></script> 
    <script src="<?= URLROOT; ?>/js/editor.js"></script>

</body>
</html>