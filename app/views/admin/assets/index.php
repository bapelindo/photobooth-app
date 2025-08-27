<?php
// Mengelompokkan aset berdasarkan tipe
$frames = array_filter($data['assets'], fn($asset) => $asset->type === 'frame');
$stickers = array_filter($data['assets'], fn($asset) => $asset->type === 'sticker');
$filters = array_filter($data['assets'], fn($asset) => $asset->type === 'filter');
?>

<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Manage Assets</h1>
    <a href="<?= URLROOT; ?>/admin/assets/create" class="btn btn-primary">
        <i data-feather="upload-cloud"></i> Upload New Asset
    </a>
</div>

<div class="asset-section">
    <h2 class="section-title">Frames</h2>
    <?php if (empty($frames)): ?>
        <p class="empty-state">No frames have been uploaded yet. You can upload one now!</p>
    <?php else: ?>
        <div class="asset-grid">
            <?php foreach ($frames as $asset): ?>
            <div class="asset-item">
                <div class="asset-preview frame-preview">
                    <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>">
                </div>
                <div class="asset-info">
                    <p class="asset-name"><?= htmlspecialchars($asset->name) ?></p>
                    <div class="asset-actions">
                        <a href="<?= URLROOT; ?>/admin/assets/editFrame/<?= $asset->id ?>" class="btn btn-secondary btn-sm"><i data-feather="settings"></i> Edit Slots</a>
                        <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this frame?');">
                            <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash-2"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="asset-section">
    <h2 class="section-title">Stickers</h2>
    <?php if (empty($stickers)): ?>
        <p class="empty-state">No stickers uploaded yet. Add some fun stickers!</p>
    <?php else: ?>
         <div class="asset-grid">
            <?php foreach ($stickers as $asset): ?>
            <div class="asset-item">
                <div class="asset-preview sticker-preview">
                    <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>">
                </div>
                <div class="asset-info">
                    <p class="asset-name"><?= htmlspecialchars($asset->name) ?></p>
                    <div class="asset-actions">
                        <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this sticker?');">
                            <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash-2"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="asset-section">
    <h2 class="section-title">Filters</h2>
    <?php if (empty($filters)): ?>
        <p class="empty-state">No filters added yet. Create some cool effects!</p>
    <?php else: ?>
        <div class="asset-grid">
            <?php foreach ($filters as $asset): ?>
            <div class="asset-item">
                <div class="asset-preview filter-preview" style="background-image: url('https://i.ibb.co/wzD1Zg9/preview-bg.png');">
                    <div class="filter-overlay" style="filter: <?= htmlspecialchars($asset->path) ?>;"></div>
                </div>
                 <div class="asset-info">
                    <p class="asset-name"><?= htmlspecialchars($asset->name) ?></p>
                     <div class="asset-actions">
                        <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this filter?');">
                            <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash-2"></i> Delete</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .asset-section { margin-bottom: 3.5rem; }
    .section-title { font-size: 1.75rem; font-weight: 600; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem; }
    .asset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.5rem;
    }
    .asset-item {
        background: var(--card-bg);
        border-radius: var(--border-radius);
        border: 1px solid var(--border-color);
        text-align: center;
        transition: all 0.2s ease;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }
    .asset-item:hover { transform: translateY(-5px); box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1); }
    .asset-preview {
        height: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f9fafb;
        position: relative;
    }
    .frame-preview img, .sticker-preview img { max-width: 100%; max-height: 100%; object-fit: contain; padding: 1rem; }
    .filter-preview { background-size: cover; background-position: center; }
    .filter-overlay { width: 100%; height: 100%; background: inherit; background-size: cover; background-position: center; }
    .asset-info { padding: 1rem; border-top: 1px solid var(--border-color); flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; }
    .asset-name { font-weight: 600; margin: 0 0 1rem 0; }
    .asset-actions { display: flex; justify-content: center; gap: 0.5rem; }
    .empty-state {
        background-color: #f9fafb;
        padding: 2rem;
        text-align: center;
        border-radius: var(--border-radius);
        border: 2px dashed var(--border-color);
        color: var(--text-muted);
    }
</style>