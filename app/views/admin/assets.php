<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<div class="card">
    <h3>Unggah Aset Baru untuk "<?= htmlspecialchars($data['event']->event_name); ?>"</h3>
    <form action="<?= URLROOT; ?>/admin/uploadAsset/<?= $data['event']->id; ?>" method="post" enctype="multipart/form-data" class="upload-form">
        <div class="form-group">
            <label>Pilih File (PNG/JPG):</label>
            <input type="file" name="assetFile" required>
        </div>
        <div class="form-group">
            <label>Tipe Aset:</label>
            <select name="type">
                <option value="sticker">Stiker</option>
                <option value="frame">Bingkai</option>
            </select>
        </div>
        <button type="submit" class="btn">Unggah</button>
    </form>
</div>

<div class="assets-container">
    <div class="card">
        <h4>Bingkai (Frames)</h4>
        <div class="asset-grid">
            <?php foreach($data['frames'] as $frame): ?>
            <div class="asset-item"><img src="<?= URLROOT .'/'. $frame->path; ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="card">
        <h4>Stiker (Stickers)</h4>
        <div class="asset-grid">
            <?php foreach($data['stickers'] as $sticker): ?>
            <div class="asset-item"><img src="<?= URLROOT .'/'. $sticker->path; ?>"></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>