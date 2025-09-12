<div class="page-header">
    <div class="page-header-content">
        <div class="page-title">
            <i data-feather="image" class="page-icon"></i>
            <h1>Photo Gallery</h1>
        </div>
        <div class="page-subtitle">
            <span>Manage and view completed photostrip sessions</span>
        </div>
    </div>
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
            <?php if ($photo->final_image_path): ?>
                <div class="gallery-item">
                    <a href="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" target="_blank" class="gallery-image-link">
                        <img src="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" loading="lazy" alt="Photostrip: <?= htmlspecialchars($photo->frame_name) ?>">
                        <div class="overlay">
                            <i data-feather="eye"></i>
                            <p>View Full Size</p>
                        </div>
                    </a>
                    <div class="gallery-info">
                        <div class="photo-details">
                            <h4><?= htmlspecialchars($photo->frame_name) ?></h4>
                            <p class="photo-meta">
                                Package: <?= htmlspecialchars($photo->package_name) ?> | 
                                Order: #<?= htmlspecialchars($photo->order_id) ?>
                            </p>
                            <span class="photo-date">
                                <i data-feather="calendar" class="icon-sm"></i> 
                                <?= date('d M Y, H:i', strtotime($photo->created_at)) ?>
                            </span>
                        </div>
                        <div class="gallery-actions">
                            <a href="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" class="btn btn-secondary btn-sm" download title="Download">
                                <i data-feather="download"></i>
                            </a>
                            
                            <form action="<?= URLROOT; ?>/admin/gallery/delete/<?= $photo->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this photostrip?');" style="display:inline;">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i data-feather="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
    /* Page Header Styling */
    .page-header {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: var(--border-radius);
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        transition: var(--transition);
    }
    
    .page-header:hover {
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }
    
    .page-header-content {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .page-title {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .page-icon {
        width: 32px;
        height: 32px;
        color: var(--primary-light);
        filter: drop-shadow(0 2px 4px rgba(59, 130, 246, 0.3));
    }
    
    .page-header h1 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-color);
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .page-subtitle span {
        font-size: 1rem;
        color: var(--text-muted);
        font-weight: 500;
        letter-spacing: 0.025em;
    }
    
    /* Responsive Page Header */
    @media (max-width: 768px) {
        .page-header {
            padding: 1.5rem;
        }
        
        .page-header h1 {
            font-size: 1.5rem;
        }
        
        .page-icon {
            width: 24px;
            height: 24px;
        }
        
        .page-subtitle span {
            font-size: 0.9rem;
        }
    }

    .gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 1.5rem;
        padding: 1rem 0;
    }
    
    .empty-state-container { 
        grid-column: 1 / -1; 
    }
    
    .empty-state {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        padding: 3rem; 
        text-align: center;
        border-radius: var(--border-radius); 
        border: 2px dashed var(--border-color);
        color: var(--text-muted);
        box-shadow: var(--shadow);
        transition: var(--transition);
    }
    
    .empty-state:hover {
        border-color: var(--border-light);
        box-shadow: var(--shadow-lg);
    }
    
    .empty-state .feather { 
        width: 48px; 
        height: 48px; 
        margin-bottom: 1rem;
        color: var(--text-dim);
    }
    
    .empty-state p { 
        font-size: 1.2rem; 
        font-weight: 600; 
        margin: 0 0 0.5rem 0;
        color: var(--text-secondary);
    }
    
    .empty-state span { 
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .gallery-item {
        background: var(--card-bg);
        background-image: var(--gradient-card);
        border-radius: var(--border-radius);
        overflow: hidden; 
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
        display: flex; 
        flex-direction: column;
        transition: var(--transition);
        position: relative;
    }
    
    .gallery-item:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-lg);
        border-color: var(--border-light);
    }
    
    .gallery-image-link {
        display: block; 
        position: relative;
        aspect-ratio: 4 / 5;
        overflow: hidden;
    }
    
    .gallery-image-link img {
        width: 100%; 
        height: 100%; 
        object-fit: cover;
        transition: var(--transition);
        border-radius: var(--border-radius) var(--border-radius) 0 0;
    }
    
    .gallery-item:hover .gallery-image-link img { 
        transform: scale(1.1); 
    }
    
    .overlay {
        position: absolute; 
        top: 0; 
        left: 0; 
        right: 0; 
        bottom: 0;
        background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.4) 50%, rgba(0,0,0,0) 70%);
        color: white; 
        display: flex; 
        flex-direction: column;
        align-items: center; 
        justify-content: flex-end;
        padding: 1.5rem; 
        opacity: 0;
        transition: var(--transition); 
        text-align: center;
    }
    
    .gallery-item:hover .overlay { 
        opacity: 1; 
    }
    
    .overlay .feather { 
        width: 32px; 
        height: 32px; 
        margin-bottom: 0.5rem;
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
    }
    
    .overlay p { 
        font-size: 1rem; 
        font-weight: 600; 
        margin: 0;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    .gallery-info {
        padding: 1rem;
        border-top: 1px solid var(--border-color);
        background: var(--card-secondary);
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .photo-details {
        flex: 1;
    }
    
    .photo-details h4 {
        margin: 0 0 0.5rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--primary-light);
        text-shadow: 0 1px 2px rgba(0,0,0,0.1);
    }
    
    .photo-meta {
        margin: 0 0 0.75rem 0;
        font-size: 0.8rem;
        color: var(--text-muted);
        line-height: 1.4;
    }
    
    .photo-date {
        font-size: 0.8rem;
        color: var(--text-dim);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 500;
    }
    
    .icon-sm { 
        width: 14px; 
        height: 14px; 
    }
    
    .gallery-actions {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
    }
    
    .gallery-actions .btn-sm {
        padding: 0.6rem;
        line-height: 1;
        border-radius: 0.5rem;
        transition: var(--transition);
        background: var(--bg-tertiary);
        border: 1px solid var(--border-color);
        color: var(--text-secondary);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .gallery-actions .btn-secondary:hover {
        background: var(--secondary-color);
        border-color: var(--secondary-hover);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }
    
    .gallery-actions .btn-danger:hover {
        background: var(--error-color);
        border-color: var(--error-light);
        color: white;
        transform: translateY(-2px);
        box-shadow: var(--shadow);
    }
    
    .gallery-actions .btn-sm .feather {
        width: 16px;
        height: 16px;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .gallery-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 1rem;
        }
        
        .gallery-info {
            flex-direction: column;
            gap: 0.75rem;
        }
        
        .gallery-actions {
            align-self: flex-end;
        }
    }
    
    @media (max-width: 480px) {
        .gallery-grid {
            grid-template-columns: 1fr;
        }
        
        .gallery-item {
            max-width: 100%;
        }
    }
</style>