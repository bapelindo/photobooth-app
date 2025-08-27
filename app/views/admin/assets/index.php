<?php require APPROOT . '/views/admin/layouts/header.php'; ?>

<h1>Manage Assets</h1>
<p>
    <a href="<?= URLROOT; ?>/admin/assets/create" class="btn btn-primary">Upload New Asset</a>
</p>

<?php
// Mengelompokkan aset berdasarkan tipe
$frames = array_filter($data['assets'], fn($asset) => $asset->type === 'frame');
$stickers = array_filter($data['assets'], fn($asset) => $asset->type === 'sticker');
$filters = array_filter($data['assets'], fn($asset) => $asset->type === 'filter');
?>

<style>
    .asset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 1.5rem;
    }
    .asset-item {
        border: 1px solid #ddd;
        padding: 1rem;
        text-align: center;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .asset-item img {
        max-width: 100%;
        height: auto;
        max-height: 120px;
        object-fit: contain;
        margin-bottom: 1rem;
    }
    .asset-item .filter-preview {
        width: 100%;
        height: 120px;
        border: 1px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        background-color: #f0f0f0;
        margin-bottom: 1rem;
        background-image: linear-gradient(45deg, #ccc 25%, transparent 25%), linear-gradient(-45deg, #ccc 25%, transparent 25%), linear-gradient(45deg, transparent 75%, #ccc 75%), linear-gradient(-45deg, transparent 75%, #ccc 75%);
        background-size: 20px 20px;
        background-position: 0 0, 0 10px, 10px -10px, -10px 0px;
    }
    .asset-item p {
        font-weight: 600;
        margin-bottom: 1rem;
    }
    .asset-actions {
        margin-top: auto;
        display: flex;
        justify-content: center;
        gap: 5px;
    }
</style>

<div class="card">
    <h2>Frames</h2>
    <?php if (empty($frames)): ?>
        <p>No frames uploaded yet.</p>
    <?php else: ?>
        <div class="asset-grid">
            <?php foreach ($frames as $asset): ?>
            <div class="asset-item">
                <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>">
                <p><?= htmlspecialchars($asset->name) ?></p>
                <div class="asset-actions">
                    <a href="<?= URLROOT; ?>/admin/assets/editFrame/<?= $asset->id ?>" class="btn btn-sm btn-secondary">Edit Slots</a>
                    <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline;">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Stickers</h2>
    <?php if (empty($stickers)): ?>
        <p>No stickers uploaded yet.</p>
    <?php else: ?>
         <div class="asset-grid">
            <?php foreach ($stickers as $asset): ?>
            <div class="asset-item">
                <img src="<?= URLROOT . htmlspecialchars($asset->path) ?>" alt="<?= htmlspecialchars($asset->name) ?>">
                <p><?= htmlspecialchars($asset->name) ?></p>
                <div class="asset-actions">
                    <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline;">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Filters</h2>
    <?php if (empty($filters)): ?>
        <p>No filters added yet.</p>
    <?php else: ?>
        <div class="asset-grid">
            <?php foreach ($filters as $asset): ?>
            <div class="asset-item">
                <div class="filter-preview" style="filter: url('<?= URLROOT . htmlspecialchars($asset->path) ?>');">PREVIEW</div>
                <p><?= htmlspecialchars($asset->name) ?></p>
                 <div class="asset-actions">
                    <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline;">
                        <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                    </form>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>