<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saatnya Beraksi!</title>
    <link rel="stylesheet" href="<?= URLROOT; ?>/public/css/photobooth.css">
</head>
<body>

    <div class="photobooth-container">
        <!-- Area Utama -->
        <div class="main-stage">
            <div id="pose-prompt">Siap-siap Berpose!</div>
            <div class="camera-view">
                <video id="live-preview" autoplay playsinline></video>
                <!-- Frame yang dipilih akan muncul di sini -->
                <?php if (isset($selected_frame) && $selected_frame): ?>
    <img id="selected-frame-preview" src="<?= URLROOT; ?>/public<?= htmlspecialchars($selected_frame->path); ?>" alt="Selected Frame">
<?php endif; ?>
                <!-- Stiker yang di-drag akan ada di sini -->
                <div id="sticker-area"></div>
                <!-- Hitung mundur akan muncul di sini -->
                <div id="countdown"></div>
            </div>

            <!-- Area untuk menampilkan hasil foto -->
            <div class="result-view" style="display: none;">
                <img id="result-photo" src="" alt="Hasil Foto Kamu!">
                <p class="fun-message">Keren Banget Kan!</p>
            </div>

            <!-- Kanvas tersembunyi untuk mengambil gambar dari video -->
            <canvas id="capture-canvas" style="display:none;"></canvas>
        </div>

        <!-- Panel Kontrol -->
        <div class="controls-panel">
            <div id="webcam-status-container"></div> <!-- Placeholder for webcam status -->
            <div id="initial-controls">
                <button id="take-photo-btn" class="action-button">Ambil Foto!</button>
            </div>
            <div id="retake-controls" style="display: none;">
                <button id="retake-btn" class="action-button secondary">Ulang Ah!</button>
                <button id="finalize-btn" class="action-button">Suka! Lanjut</button>
            </div>
            <div id="finalize-panel" style="display: none;">
                <h3>Selesai! Apa selanjutnya?</h3>
                <div class="final-actions">
                    <button id="print-btn" class="action-button">Cetak Foto!</button>
                    <div class="email-form">
                        <input type="email" id="email-input" placeholder="Atau kirim ke email...">
                        <button id="send-email-btn" class="action-button secondary">Kirim</button>
                    </div>
                </div>
                <p id="email-status"></p>
            </div>
        </div>

        <!-- Panel Aset (Stiker) -->
        <div class="assets-panel">
            <div class="asset-section">
                <h3>Pilih Filter</h3>
                <div class="asset-list">
                    <div class="asset-item filter-item" data-filter="none">None</div>
                    <div class="asset-item filter-item" data-filter="grayscale">Grayscale</div>
                    <div class="asset-item filter-item" data-filter="sepia">Sepia</div>
                    <div class="asset-item filter-item" data-filter="invert">Invert</div>
                    <div class="asset-item filter-item" data-filter="hue-rotate">Hue</div>
                    <div class="asset-item filter-item" data-filter="blur">Blur</div>
                    <div class="asset-item filter-item" data-filter="contrast">Contrast</div>
                </div>
            </div>
            <div class="asset-section">
                <h3>Tambah Stiker (Drag & Drop)</h3>
                <div class="asset-list">
                    <img src="/photobooth-app/public/assets/stickers/sticker1.png" class="asset-item sticker-item" data-path="/photobooth-app/public/assets/stickers/sticker1.png" alt="Sticker 1" draggable="true">
                    <img src="/photobooth-app/public/assets/stickers/sticker2.png" class="asset-item sticker-item" data-path="/photobooth-app/public/assets/stickers/sticker2.png" alt="Sticker 2" draggable="true">
                    <?php foreach ($stickers as $sticker): ?>
                        <img src="<?= URLROOT; ?>/public<?= htmlspecialchars($sticker->file_path); ?>" class="asset-item sticker-item" data-path="<?= htmlspecialchars($sticker->file_path); ?>" alt="<?= htmlspecialchars($sticker->name); ?>" draggable="true">
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= URLROOT; ?>/public/js/photobooth.js"></script>
</body>
</html>