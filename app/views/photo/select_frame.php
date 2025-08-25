<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Bingkai Favoritmu!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --card-bg: #FFFFFF;
            --dark-text: #333;
            --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        }

        body {
            font-family: var(--font-main);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }

        .main-container {
            display: flex;
            flex-direction: column;
            width: 100%;
            max-width: 1200px;
            height: 95vh;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 5px;
            box-sizing: border-box;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-panel {
            text-align: center;
            padding: 10px;
            background-color: var(--card-bg);
            border-radius: 20px;
            flex-shrink: 0;
            margin-bottom: 15px;
        }
        .info-panel h1 {
            font-family: var(--font-display);
            color: var(--primary-color);
            margin: 0;
            font-size: clamp(1.8rem, 4vh, 2.2rem);
        }
        .info-panel p {
            margin: 5px 0 0;
            color: #555;
            font-size: clamp(0.8rem, 2vh, 1rem);
        }

        .frames-grid-container {
            overflow-y: auto;
            flex-grow: 1;
            min-height: 0;
        }
        .frames-grid-container::-webkit-scrollbar { display: none; }
        .frames-grid-container { -ms-overflow-style: none; scrollbar-width: none; }

        .frames-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 25px;
            padding-bottom: 10px;
            /* --- PERBAIKAN UTAMA: Membuat baris lebih tinggi --- */
            grid-auto-rows: minmax(240px, auto);
        }

        .frame-card {
            background-color: var(--card-bg);
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            color: inherit;
            border: 2px solid transparent;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .frame-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border-color: var(--secondary-color);
        }
        
        .frame-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            flex-grow: 1;
        }

        .frame-card h2 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            margin: 0;
            padding: 2px 10px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            flex-shrink: 0;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="info-panel">
            <h1>Pilih Bingkai Ajaibmu!</h1>
            <p>Klik bingkai favoritmu untuk memulai sesi foto yang tak terlupakan.</p>
        </div>

        <div class="frames-grid-container">
            <div class="frames-grid">
                <?php foreach ($frames as $frame): ?>
                    <a href="<?= URLROOT; ?>/photo/capture/<?= $transaction_id ?>/<?= $frame->id ?>" class="frame-card">
                        <img src="<?= URLROOT . htmlspecialchars($frame->path); ?>" alt="<?= htmlspecialchars($frame->name); ?>">
                        <h2><?= htmlspecialchars($frame->name); ?></h2>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

</body>
</html>