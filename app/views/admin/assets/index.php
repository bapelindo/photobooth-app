<?php
// Grouping assets
$frames = array_filter($data['assets'], fn($asset) => $asset->type === 'frame');
$stickers = array_filter($data['assets'], fn($asset) => $asset->type === 'sticker');
$filters = array_filter($data['assets'], fn($asset) => $asset->type === 'filter');
?>

<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="image" style="color: var(--primary);"></i>
            Manage Assets
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Upload and manage frames, stickers, and filters.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <a href="<?= URLROOT; ?>/admin/assets/create" class="btn btn-primary">
            <i data-feather="upload-cloud"></i> Upload Asset
        </a>
    </div>
</div>

<!-- Frames Section -->
<div style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-main); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <i data-feather="layers" style="width: 18px; color: var(--primary);"></i> Frames
    </h2>
    <?php if (empty($frames)): ?>
        <div style="background-color: var(--bg-surface); border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 3rem 1rem; text-align: center; color: var(--text-muted);">
            <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>No frames uploaded yet. Click "Upload Asset" to get started.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php foreach ($frames as $asset): ?>
            <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column; transition: transform 0.2s, box-shadow 0.2s;">
                <div style="height: 180px; background-color: var(--bg-body); display: flex; align-items: center; justify-content: center; position: relative;">
                    <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 1rem;">
                </div>
                <div class="card-body" style="padding: 1rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between; border-top: 1px solid var(--border-color);">
                    <div style="font-weight: 500; font-size: 0.875rem; color: var(--text-main); margin-bottom: 1rem; text-align: center;">
                        <?= htmlspecialchars($asset->name) ?>
                    </div>
                    <div style="display: flex; gap: 0.5rem; justify-content: center;">
                        <a href="<?= URLROOT; ?>/admin/assets/editFrame/<?= $asset->id ?>" class="btn btn-secondary btn-sm" title="Edit Slots" style="flex: 1;">
                            <i data-feather="settings"></i> Slots
                        </a>
                        <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Delete this frame?');" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete" style="height: 100%;">
                                <i data-feather="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Stickers Section -->
<div style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-main); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <i data-feather="smile" style="width: 18px; color: var(--warning);"></i> Stickers
    </h2>
    <?php if (empty($stickers)): ?>
        <div style="background-color: var(--bg-surface); border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 3rem 1rem; text-align: center; color: var(--text-muted);">
            <i data-feather="smile" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>No stickers uploaded yet. Add some fun stickers!</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php foreach ($stickers as $asset): ?>
            <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
                <div style="height: 180px; background-color: var(--bg-body); display: flex; align-items: center; justify-content: center; position: relative;">
                    <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>" style="max-width: 100%; max-height: 100%; object-fit: contain; padding: 1rem;">
                </div>
                <div class="card-body" style="padding: 1rem; border-top: 1px solid var(--border-color);">
                    <div style="font-weight: 500; font-size: 0.875rem; color: var(--text-main); margin-bottom: 1rem; text-align: center;">
                        <?= htmlspecialchars($asset->name) ?>
                    </div>
                    <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Delete this sticker?');" style="display:block;">
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            <i data-feather="trash-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- Filters Section -->
<div style="margin-bottom: 3rem;">
    <h2 style="font-size: 1.25rem; font-weight: 600; color: var(--text-main); margin-bottom: 1rem; border-bottom: 1px solid var(--border-color); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <i data-feather="aperture" style="width: 18px; color: var(--success);"></i> Filters
    </h2>
    <?php if (empty($filters)): ?>
        <div style="background-color: var(--bg-surface); border: 2px dashed var(--border-color); border-radius: var(--radius-md); padding: 3rem 1rem; text-align: center; color: var(--text-muted);">
            <i data-feather="aperture" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
            <p>No filters created yet. Add CSS filters for effects.</p>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
            <?php foreach ($filters as $asset): ?>
            <div class="card" style="margin-bottom: 0; display: flex; flex-direction: column;">
                <div style="height: 180px; background-image: url('https://i.ibb.co/wzD1Zg9/preview-bg.png'); background-size: cover; background-position: center; position: relative;">
                    <div style="position: absolute; inset: 0; filter: <?= htmlspecialchars($asset->path) ?>; background: inherit; background-size: cover; background-position: center;"></div>
                </div>
                <div class="card-body" style="padding: 1rem; border-top: 1px solid var(--border-color);">
                    <div style="font-weight: 500; font-size: 0.875rem; color: var(--text-main); margin-bottom: 1rem; text-align: center;">
                        <?= htmlspecialchars($asset->name) ?>
                    </div>
                    <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Delete this filter?');" style="display:block;">
                        <button type="submit" class="btn btn-danger btn-sm" style="width: 100%;">
                            <i data-feather="trash-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
</style>