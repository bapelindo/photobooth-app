<div class="page-header">
    <h1>Create New Package</h1>
</div>

<div class="card" style="padding: 2rem;">
    <form action="<?= URLROOT.'/admin/packages/store' ?>" method="POST">
        <div class="form-group">
            <label for="name">Package Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>
        <div class="form-grid">
            <div class="form-group">
                <label for="price">Price (IDR)</label>
                <input type="number" name="price" id="price" class="form-control" required step="1000">
            </div>
            <div class="form-group">
                <label for="photo_limit">Total Photos in Strips</label>
                <input type="number" name="photo_limit" id="photo_limit" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="frame_count">Number of Frames/Strips</label>
                <input type="number" name="frame_count" id="frame_count" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="session_time_limit">Session Time (seconds)</label>
                <input type="number" name="session_time_limit" id="session_time_limit" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="photo_shot_limit">Max Photo Shots</label>
                <input type="number" name="photo_shot_limit" id="photo_shot_limit" class="form-control" required>
            </div>
             <div class="form-group">
                <label for="retake_limit">Retake Limit (optional)</label>
                <input type="number" name="retake_limit" id="retake_limit" class="form-control" value="0" required>
            </div>
        </div>
        <div class="form-actions">
            <a href="<?= URLROOT; ?>/admin/packages" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Package</button>
        </div>
    </form>
</div>
<style>
    .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; }
    .form-actions { margin-top: 2rem; display: flex; justify-content: flex-end; gap: 1rem; }
</style>