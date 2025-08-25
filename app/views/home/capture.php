<?php require APPROOT . '/views/layouts/header.php'; ?>

<main class="main-content capture-page">
    <div class="photobooth-wrapper" style="align-items: center;">
        <div class="camera-container" id="live-preview-container">
            <div id="live-preview">
                <p>Live Preview dari Kamera DSLR...</p>
                <small>(Ini adalah simulasi)</small>
            </div>
            <div id="photo-result" style="display:none;">
                <img id="result-image" src="" alt="Hasil Foto">
            </div>
            <div id="countdown" class="countdown-overlay"></div>
        </div>
        <div class="controls-container">
            <div id="capture-controls">
                <h2>Kustomisasi Foto Anda</h2>
                <div class="form-group">
                    <label>Pilih Bingkai:</label>
                    <select id="frame-select" class="form-control">
                        <option value="">Tanpa Bingkai</option>
                        <?php foreach($data['frames'] as $frame): ?>
                            <option value="<?= $frame->path; ?>"><?= htmlspecialchars($frame->name); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <p class="capture-info">Kesempatan mengulang: <span id="retakes-left"><?= $data['retakes_left']; ?></span></p>
                <button id="captureBtn" class="btn btn-capture">Ambil Foto</button>
            </div>
            <div id="result-controls" style="display:none;">
                <h2>Bagaimana Hasilnya?</h2>
                <a href="<?= URLROOT; ?>/photo/finalize" class="btn btn-success">Sempurna! Lanjutkan</a>
                <?php if ($data['retakes_left'] > 0): ?>
                    <a href="<?= URLROOT; ?>/photo/retake" class="btn btn-danger">Ulang Ah!</a>
                <?php else: ?>
                    <p class="capture-info">Kesempatan mengulang habis.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<script src="<?= URLROOT; ?>/js/capture.js"></script>
<?php require APPROOT . '/views/layouts/footer.php'; ?>