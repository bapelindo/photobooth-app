<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saatnya Sesi Foto!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        html, body { overflow: hidden; }
        :root {
            --primary-color: #6C63FF; --secondary-color: #FF6584; --accent-color: #FFD166;
            --card-bg: #FFFFFF; --dark-text: #333; --font-display: 'Fredoka One', cursive; --font-main: 'Poppins', sans-serif;
        }
        body { font-family: var(--font-main); background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); margin: 0; padding: 20px; display: flex; justify-content: center; align-items: center; min-height: 100vh; box-sizing: border-box; }
        .photobooth-container { display: grid; grid-template-columns: 1fr 300px; grid-template-rows: auto 1fr; gap: 20px; width: 100%; max-width: 1200px; height: 90vh; background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px); border-radius: 20px; padding: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2); }
        .top-panel { grid-column: 1 / 3; display: flex; justify-content: space-around; align-items: center; background-color: var(--card-bg); border-radius: 20px; padding: 10px; margin-bottom: 10px; }
        .info-box { text-align: center; }
        .info-box h2 { font-family: var(--font-display); color: var(--primary-color); margin: 0; font-size: 1.2rem; }
        .info-box p { font-size: 1.5rem; font-weight: 700; margin: 5px 0 0; }
        /* REVISI: Pastikan main-stage bisa menampung elemen di dalamnya */
        .main-stage { grid-column: 1 / 2; grid-row: 2 / 3; position: relative; display: flex; justify-content: center; align-items: center; background: #000; border-radius: 20px; overflow: hidden; }
        .side-panel { grid-column: 2 / 3; grid-row: 2 / 3; display: flex; flex-direction: column; gap: 15px; }
        .controls-panel, .photo-review-panel { background: var(--card-bg); border-radius: 20px; padding: 20px; text-align: center; }
        .photo-review-panel { flex-grow: 1; display: flex; flex-direction: column; }
        .photo-review-panel h3 { margin-top: 0; font-family: var(--font-display); }
        .review-area { width: 100%; height: 200px; background: #eee; border-radius: 10px; margin: 15px 0; position: relative; display: flex; justify-content: center; align-items: center; }
        #review-photo { max-width: 100%; max-height: 100%; object-fit: contain; }
        .review-buttons { display: flex; gap: 10px; justify-content: center; }
        /* REVISI: Gunakan object-fit contain agar rasio video terjaga */
        #live-preview { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
        #safe-zone { position: absolute; border: 3px dashed rgba(255, 255, 255, 0.8); box-shadow: 0 0 20px rgba(0,0,0,0.6); border-radius: 5px; z-index: 2; pointer-events: none; transition: all 0.2s ease-in-out; }
        #countdown { position: absolute; font-family: var(--font-display); font-size: 200px; color: var(--accent-color); text-shadow: 5px 5px 10px rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 10; }
        .action-button { font-family: var(--font-display); font-size: 1.2rem; padding: 15px 30px; border: none; border-radius: 50px; cursor: pointer; color: var(--dark-text); transition: all 0.3s ease; }
        #capture-btn { background-color: var(--accent-color); width: 100%; }
        #finish-session-btn { background-color: var(--primary-color); color: white; margin-top: auto; display: none; }
        .review-buttons .action-button { font-size: 1rem; padding: 10px 20px; }
        #keep-btn { background-color: #28a745; color: white; }
        #discard-btn { background-color: #ffc107; }
    </style>
</head>
<body>
    <div class="photobooth-container">
        <div class="top-panel">
            <div class="info-box">
                <h2>Sisa Waktu</h2>
                <p id="timer"><?= $package->session_time_limit < 0 ? '∞' : gmdate("i:s", $package->session_time_limit) ?></p>
            </div>
            <div class="info-box">
                <h2>Sisa Foto</h2>
                <p id="shot-counter"><?= $package->photo_shot_limit ?></p>
            </div>
        </div>

        <div class="main-stage">
            <video id="live-preview" autoplay playsinline muted></video>
            <div id="safe-zone"></div>
            <div id="countdown"></div>
            <canvas id="capture-canvas" style="display: none;"></canvas>
        </div>

        <div class="side-panel">
            <div class="controls-panel">
                <button id="capture-btn" class="action-button">📸 Ambil Foto</button>
            </div>
            <div class="photo-review-panel" style="display: none;">
                <h3>Tinjau Foto</h3>
                <div class="review-area">
                    <img id="review-photo" src="" alt="Captured Photo Preview">
                </div>
                <div class="review-buttons">
                    <button id="discard-btn" class="action-button">Hapus</button>
                    <button id="keep-btn" class="action-button">Simpan</button>
                </div>
            </div>
            <button id="finish-session-btn" class="action-button">Selesai & Lanjut Edit</button>
        </div>
    </div>

    <script>
        const PACKAGE_DATA = <?= json_encode($package); ?>;
        const TRANSACTION_ID = '<?= $data['transaction_id']; ?>';
        const ALL_SLOTS_DATA = <?= json_encode($data['all_slots_data']); ?>;
        const URLROOT = '<?= URLROOT; ?>';
    </script>
    <script src="<?= URLROOT; ?>/js/photobooth.js"></script>
</body>
</html>
