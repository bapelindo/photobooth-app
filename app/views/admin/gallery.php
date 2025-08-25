<?php require APPROOT . '/views/admin/layouts/header.php'; ?>
<div class="card">
    <h3>Galeri untuk "<?= htmlspecialchars($data['event']->event_name); ?>"</h3>
    <div class="gallery-grid">
        <?php if(empty($data['photos'])): ?>
            <p>Belum ada foto yang diambil untuk acara ini.</p>
        <?php else: ?>
            <?php foreach($data['photos'] as $photo): ?>
                <div class="gallery-item">
                    <a href="<?= URLROOT . '/uploads/photos/' . $photo->filename; ?>" target="_blank">
                        <img src="<?= URLROOT . '/uploads/photos/' . $photo->filename; ?>" loading="lazy">
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>