<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="image" style="color: var(--primary);"></i>
            Photo Gallery
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Manage and view completed photostrip sessions.</p>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
    <?php if(empty($photos)): ?>
        <div style="grid-column: 1 / -1; background-color: var(--bg-surface); border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 4rem 2rem; text-align: center; color: var(--text-muted);">
            <i data-feather="image" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem; color: var(--text-main);">No photos have been taken yet</h3>
            <p style="margin: 0; font-size: 0.875rem;">Once a session is completed, the final photo will appear here.</p>
        </div>
    <?php else: ?>
        <?php foreach ($photos as $photo): ?>
            <?php if ($photo->final_image_path): ?>
                <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s;">
                    <a href="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" target="_blank" style="display: block; position: relative; height: 300px; background-color: var(--bg-body); overflow: hidden; group">
                        <img src="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" loading="lazy" alt="Photostrip" style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease;">
                        <div style="position: absolute; inset: 0; background: rgba(0,0,0,0.5); display: flex; flex-direction: column; align-items: center; justify-content: center; color: white; opacity: 0; transition: opacity 0.2s;" onmouseenter="this.style.opacity='1'; this.previousElementSibling.style.transform='scale(1.05)';" onmouseleave="this.style.opacity='0'; this.previousElementSibling.style.transform='scale(1)';">
                            <i data-feather="eye" style="margin-bottom: 0.5rem;"></i>
                            <span style="font-weight: 500; font-size: 0.875rem;">View Full Size</span>
                        </div>
                    </a>
                    
                    <div class="card-body" style="padding: 1.25rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 1rem; border-top: 1px solid var(--border-color);">
                        <div>
                            <h4 style="margin: 0 0 0.5rem 0; font-size: 1rem; color: var(--text-main); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="<?= htmlspecialchars($photo->frame_name) ?>">
                                <?= htmlspecialchars($photo->frame_name) ?>
                            </h4>
                            <p style="margin: 0 0 0.5rem 0; font-size: 0.75rem; color: var(--text-muted);">
                                Package: <span style="font-weight: 500; color: var(--text-main);"><?= htmlspecialchars($photo->package_name) ?></span><br>
                                Order: <span style="font-weight: 500; color: var(--text-main);">#<?= htmlspecialchars($photo->order_id) ?></span>
                            </p>
                            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--text-light);">
                                <i data-feather="calendar" style="width: 12px; height: 12px;"></i> 
                                <?= date('d M Y, H:i', strtotime($photo->created_at)) ?>
                            </div>
                        </div>
                        
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= htmlspecialchars(URLROOT . $photo->final_image_path) ?>" class="btn btn-secondary btn-sm" download title="Download" style="flex: 1;">
                                <i data-feather="download"></i> Download
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
    .card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
</style>