<div class="page-header">
    <h1><?= isset($package) ? 'Edit Package' : 'Create New Package' ?></h1>
</div>

<div class="card" style="padding: 2rem;">
    <form action="<?= isset($package) ? URLROOT.'/admin/packages/update/'.$package->id : URLROOT.'/admin/packages/store' ?>" method="POST">
        <div class="form-group">
            <label for="name">Package Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($package->name ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"><?= htmlspecialchars($package->description ?? '') ?></textarea>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="price">Price (IDR)</label>
                <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($package->price ?? '') ?>" required step="1000">
            </div>
            <div class="form-group">
                <label for="photo_limit">Photo Limit</label>
                <input type="number" name="photo_limit" id="photo_limit" class="form-control" value="<?= htmlspecialchars($package->photo_limit ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="retake_limit">Retake Limit</label>
                <input type="number" name="retake_limit" id="retake_limit" class="form-control" value="<?= htmlspecialchars($package->retake_limit ?? '') ?>" required>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?= URLROOT; ?>/admin/packages" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary"><?= isset($package) ? 'Update' : 'Save' ?> Package</button>
        </div>
    </form>
</div>
<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    .form-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
</style>