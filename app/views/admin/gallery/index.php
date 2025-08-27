<style>
    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 15px;
    }
    .gallery-item {
        border: 1px solid #ddd;
        border-radius: 8px;
        overflow: hidden;
        text-align: center;
        padding: 10px;
    }
    .gallery-item img {
        max-width: 100%;
        height: auto;
        display: block;
    }
    .gallery-item p {
        font-size: 0.8rem;
        color: #666;
        margin-top: 5px;
    }
</style>

<h1>Photo Gallery</h1>

<div class="gallery-grid">
    <?php foreach ($photos as $photo): ?>
        <div class="gallery-item">
            <a href="<?= htmlspecialchars(URLROOT . $photo->file_path) ?>" target="_blank">
                <img src="<?= htmlspecialchars(URLROOT . $photo->file_path) ?>" alt="Photo from transaction <?= htmlspecialchars($photo->transaction_code ?? '') ?>">
            </a>
            <p>Taken: <?= date('d M Y H:i', strtotime($photo->created_at)) ?></p>
        </div>
    <?php endforeach; ?>
</div>