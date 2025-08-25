<h1>Edit Package: <?= htmlspecialchars($package->name) ?></h1>

<form action="<?= URLROOT; ?>/admin/packages/update/<?= $package->id ?>" method="POST">
    <div class="form-group">
        <label for="name">Package Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($package->name) ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea name="description" id="description" class="form-control"><?= htmlspecialchars($package->description) ?></textarea>
    </div>
    <div class="form-group">
        <label for="price">Price (IDR)</label>
        <input type="number" name="price" id="price" class="form-control" value="<?= htmlspecialchars($package->price) ?>" required step="1000">
    </div>
    <div class="form-group">
        <label for="photo_limit">Photo Limit</label>
        <input type="number" name="photo_limit" id="photo_limit" class="form-control" value="<?= htmlspecialchars($package->photo_limit) ?>" required>
    </div>
    <div class="form-group">
        <label for="retake_limit">Retake Limit</label>
        <input type="number" name="retake_limit" id="retake_limit" class="form-control" value="<?= htmlspecialchars($package->retake_limit) ?>" required>
    </div>
    <button type="submit" class="btn btn-primary">Update Package</button>
    <a href="<?= URLROOT; ?>/admin/packages" class="btn btn-secondary">Cancel</a>
</form>