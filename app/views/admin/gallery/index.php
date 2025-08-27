<div class="page-header">
    <h1>Photo Gallery</h1>
</div>

<div class="gallery-grid">
    <?php if(empty($photos)): ?>
        <div class="empty-state-container">
            <div class="empty-state">
                <i data-feather="image"></i>
                <p>No photos have been taken yet.</p>
                <span>Once a session is completed, the final photo will appear here.</span>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($photos as $photo): ?>
            <div class="gallery-item">
                <a href="<?= htmlspecialchars(URLROOT . $photo->file_path) ?>" target="_blank" class="gallery-image-link">
                    <img src="<?= htmlspecialchars(URLROOT . $photo->file_path) ?>" loading="lazy" alt="Photobooth final image">
                    <div class="overlay">
                        <i data-feather="eye"></i>
                        <p>View Full Size</p>
                    </div>
                </a>
                <div class="gallery-info">
                    <span class="photo-date">
                        <i data-feather="calendar" class="icon-sm"></i> 
                        <?= date('d M Y, H:i', strtotime($photo->created_at)) ?>
                    </span>
                    <div class="gallery-actions">
                        <a href="<?= htmlspecialchars(URLROOT . $photo->file_path) ?>" class="btn btn-secondary btn-sm" download>
                            <i data-feather="download"></i>
                        </a>
                        
                        <form action="<?= URLROOT; ?>/admin/gallery/delete/<?= $photo->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this photo?');" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i data-feather="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    .gallery-grid {
        display: grid;
        /* Mengurangi ukuran minimum kartu foto */
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    .empty-state-container { grid-column: 1 / -1; }
    .empty-state {
        background-color: #f9fafb; padding: 3rem; text-align: center;
        border-radius: var(--border-radius); border: 2px dashed var(--border-color);
        color: var(--text-muted);
    }
    .empty-state .feather { width: 48px; height: 48px; margin-bottom: 1rem; }
    .empty-state p { font-size: 1.2rem; font-weight: 500; margin: 0; }
    .empty-state span { font-size: 0.9rem; }

    .gallery-item {
        background: var(--card-bg); border-radius: var(--border-radius);
        overflow: hidden; box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        display: flex; flex-direction: column;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }
    .gallery-image-link {
        display: block; position: relative;
        /* Menyesuaikan rasio aspek agar kartu tidak terlalu tinggi */
        aspect-ratio: 4 / 5;
    }
    .gallery-image-link img {
        width: 100%; height: 100%; object-fit: cover;
        transition: transform 0.3s ease;
    }
    .gallery-item:hover .gallery-image-link img { transform: scale(1.05); }
    .overlay {
        position: absolute; top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 50%);
        color: white; display: flex; flex-direction: column;
        align-items: center; justify-content: flex-end;
        padding: 1rem; opacity: 0;
        transition: opacity 0.3s ease; text-align: center;
    }
    .gallery-item:hover .overlay { opacity: 1; }
    .overlay .feather { width: 28px; height: 28px; margin-bottom: 0.25rem; }
    .overlay p { font-size: 1rem; font-weight: 600; margin: 0; }
    
    .gallery-info {
        padding: 0.75rem 1rem;
        border-top: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .photo-date {
        font-size: 0.8rem;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
    }
    .icon-sm { width: 14px; height: 14px; }
    .gallery-actions {
        display: flex;
        gap: 0.5rem;
    }
    /* Membuat tombol lebih kecil dan berbentuk ikon */
    .gallery-actions .btn-sm {
        padding: 0.5rem;
        line-height: 1;
    }
    .gallery-actions .btn-sm .feather {
        width: 16px;
        height: 16px;
    }
</style>