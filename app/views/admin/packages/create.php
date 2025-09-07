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
                <label for="photo_limit">Photo Prints</label>
                <input type="number" name="photo_limit" id="photo_limit" class="form-control" value="<?= htmlspecialchars($package->photo_limit ?? 2) ?>" required min="1" max="10">
                <small class="form-text">Number of photostrips to print</small>
            </div>
            <div class="form-group">
                <label for="photo_slots">Photos per Strip</label>
                <input type="number" name="photo_slots" id="photo_slots" class="form-control" value="<?= htmlspecialchars($package->photo_slots ?? 4) ?>" required min="1" max="8">
                <small class="form-text">Number of photos in each photostrip</small>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="frame_limit">Frame Selection Limit</label>
                <input type="number" name="frame_limit" id="frame_limit" class="form-control" value="<?= htmlspecialchars($package->frame_limit ?? DEFAULT_FRAME_LIMIT) ?>" required min="1" max="5">
                <small class="form-text">Number of frames user can select</small>
            </div>
            <div class="form-group">
                <label for="session_duration">Session Duration (seconds)</label>
                <input type="number" name="session_duration" id="session_duration" class="form-control" value="<?= htmlspecialchars($package->session_duration ?? DEFAULT_SESSION_DURATION) ?>" required min="60" max="1800" step="30">
                <small class="form-text">Photo session time limit</small>
            </div>
            <div class="form-group">
                <label for="max_save_photos">Max Save Photos</label>
                <input type="number" name="max_save_photos" id="max_save_photos" class="form-control" value="<?= htmlspecialchars($package->max_save_photos ?? DEFAULT_MAX_SAVE_PHOTOS) ?>" required min="5" max="100">
                <small class="form-text">Maximum photos user can save</small>
            </div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label for="retake_limit">Retake Limit (Legacy)</label>
                <input type="number" name="retake_limit" id="retake_limit" class="form-control" value="<?= htmlspecialchars($package->retake_limit ?? 0) ?>" min="0">
                <small class="form-text">Legacy field - not used in new workflow</small>
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
        margin-bottom: 1.5rem;
    }
    .form-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    .form-text {
        color: #6c757d;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
    .form-group label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
    }
</style>