<div class="page-header">
    <h1>Session Details #<?= htmlspecialchars($session->id) ?></h1>
    <a href="<?= URLROOT; ?>/admin/sessions" class="btn btn-secondary">← Back to Sessions</a>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Session Information</h2>
        <div class="info-grid">
            <div class="info-item">
                <label>Session ID:</label>
                <span>#<?= htmlspecialchars($session->id) ?></span>
            </div>
            <div class="info-item">
                <label>Package:</label>
                <span><?= htmlspecialchars($session->package_name ?? 'N/A') ?></span>
            </div>
            <div class="info-item">
                <label>Status:</label>
                <span class="badge badge-<?= $session->session_status === 'completed' ? 'success' : ($session->session_status === 'active' ? 'warning' : 'secondary') ?>">
                    <?= ucfirst(htmlspecialchars($session->session_status)) ?>
                </span>
            </div>
            <div class="info-item">
                <label>Photos Captured:</label>
                <span><?= htmlspecialchars($session->photos_captured ?? 0) ?></span>
            </div>
            <div class="info-item">
                <label>Duration Used:</label>
                <span><?= htmlspecialchars($session->duration_used ?? 0) ?> seconds</span>
            </div>
            <div class="info-item">
                <label>Created At:</label>
                <span><?= date('M j, Y H:i:s', strtotime($session->created_at)) ?></span>
            </div>
        </div>
    </div>

    <div class="card" style="padding: 2rem;">
        <h2 style="margin-top: 0;">Session Photos</h2>
        <div class="photos-grid">
            <?php if (!empty($photos)): ?>
                <?php foreach ($photos as $photo): ?>
                    <div class="photo-item">
                        <img src="<?= URLROOT; ?>/<?= htmlspecialchars($photo->file_path) ?>" alt="Session Photo" style="width: 100%; height: 100px; object-fit: cover; border-radius: 0.5rem;">
                        <small><?= date('H:i:s', strtotime($photo->taken_at)) ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: var(--text-muted); text-align: center;">No photos captured in this session</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
    .info-grid {
        display: grid;
        gap: 1rem;
    }
    .info-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .info-item label {
        font-weight: 600;
        color: var(--text-muted);
    }
    .photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 1rem;
    }
    .photo-item {
        text-align: center;
    }
    .badge {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 0.375rem;
        text-transform: uppercase;
    }
    .badge-success { background-color: #dcfce7; color: #15803d; }
    .badge-warning { background-color: #fef3c7; color: #d97706; }
    .badge-secondary { background-color: #f3f4f6; color: #6b7280; }
</style>