<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Susun Fotomu!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF; --secondary-color: #FF6584; --accent-color: #FFD166;
            --card-bg: #FFFFFF; --dark-text: #333; --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }
        html, body { 
            overflow: hidden; 
            margin: 0; 
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); 
        }
        body { 
            font-family: var(--font-main); 
            display: flex; 
            height: 100vh; 
            padding: 20px;
            box-sizing: border-box;
        }

        .sidebar { 
            width: 280px; 
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            padding: 20px; 
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1); 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0;
            margin-right: 20px;
        }
        .sidebar h2 { 
            font-family: var(--font-display); 
            text-align: center; 
            margin-top: 0; 
            color: var(--primary-color); 
            font-size: 1.8rem;
        }
        #raw-photos-gallery { 
            display: grid; 
            grid-template-columns: repeat(2, 1fr); 
            gap: 15px; 
            overflow-y: auto; 
            flex-grow: 1;
            padding-right: 5px; /* space for scrollbar */
        }
        .raw-photo-item { 
            width: 100%; 
            aspect-ratio: 1 / 1; 
            object-fit: cover; 
            cursor: grab; 
            border-radius: 12px; 
            border: 3px solid transparent; 
            transition: all 0.2s ease; 
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .raw-photo-item:hover { 
            border-color: var(--primary-color); 
            transform: scale(1.05); 
        }
        .raw-photo-item.used { 
            opacity: 0.3; 
            cursor: not-allowed; 
            filter: grayscale(80%);
        }

        .main-workspace { 
            flex-grow: 1; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            gap: 30px; 
            overflow-x: auto; 
            padding: 20px; 
            background: transparent;
        }
        
        /* REVISI UTAMA: Membuat photostrip dinamis dan berukuran benar */
        .photostrip-canvas-container {
            height: 75vh; /* Tinggi utama berdasarkan tinggi layar */
            aspect-ratio: 2 / 6; /* Memaksa rasio 2x6 inch */
            background: white; 
            border-radius: 15px; 
            box-shadow: 0 8px 30px rgba(0,0,0,0.15);
            position: relative;
            flex-shrink: 0; /* Mencegah container menyusut */
        }
        .photostrip-canvas-container canvas {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .footer-controls { 
            position: fixed; 
            bottom: 20px; 
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }
        .action-button { 
            font-family: var(--font-display); 
            font-size: 1.2rem; 
            padding: 15px 40px; 
            border: none; 
            border-radius: 50px; 
            cursor: pointer; 
            color: white; 
            background-color: var(--secondary-color); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            transition: all 0.2s ease;
        }
        .action-button:hover:not(:disabled) {
             transform: translateY(-3px);
             box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }
        .action-button:disabled { 
            background-color: #ccc; 
            cursor: not-allowed; 
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <h2>Pilih Fotomu</h2>
        <div id="raw-photos-gallery">
            <?php foreach($data['raw_photos'] as $photo): ?>
                <img src="<?= URLROOT . $photo->file_path ?>" class="raw-photo-item" draggable="true" data-photo-id="<?= $photo->id ?>">
            <?php endforeach; ?>
        </div>
    </aside>

    <main class="main-workspace">
        <?php foreach($data['selected_frames_with_slots'] as $index => $frame): ?>
            <div class="photostrip-canvas-container">
                <canvas id="canvas-<?= $index ?>"></canvas>
            </div>
        <?php endforeach; ?>
    </main>
    
    <div class="footer-controls">
        <button id="next-step-btn" class="action-button" disabled>Lanjut Hias Stiker</button>
    </div>

    <script src="<?= URLROOT ?>/js/fabric.js"></script>
    <script>
        const FRAMES_DATA = <?= json_encode($data['selected_frames_with_slots']); ?>;
        const TRANSACTION_ID = '<?= $data['transaction_id']; ?>';
        const URLROOT = '<?= URLROOT; ?>';
    </script>
    <script src="<?= URLROOT ?>/js/layout_editor.js"></script>
</body>
</html>