<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="camera" style="color: var(--primary);"></i>
            Session Details
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Viewing details for session <strong style="color: var(--primary);">#<?= htmlspecialchars($session->id) ?></strong></p>
    </div>
    <div>
        <a href="<?= URLROOT; ?>/admin/sessions" class="btn btn-secondary">
            <i data-feather="arrow-left"></i> Back to Sessions
        </a>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem; align-items: start;">
    
    <!-- Session Info Card -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Information</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Session ID</span>
                    <span style="font-weight: 600; color: var(--text-main);">#<?= htmlspecialchars($session->id) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Package</span>
                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($session->package_name ?? 'N/A') ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Status</span>
                    <?php 
                        $status = $session->session_status ?? 'unknown';
                        $badgeClass = 'badge-secondary';
                        if ($status === 'completed') $badgeClass = 'badge-success';
                        elseif ($status === 'active') $badgeClass = 'badge-warning';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= ucfirst(htmlspecialchars($status)) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Captured Photos</span>
                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars(max($session->photos_taken ?? 0, $session->total_photos_captured ?? 0)) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Saved Photos</span>
                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars(max($session->photos_saved ?? 0, $session->photos_count ?? 0)) ?></span>
                </div>
                <div style="display: flex; justify-content: space-between; border-bottom: 1px solid var(--border-color); padding-bottom: 0.75rem;">
                    <span style="color: var(--text-muted); font-weight: 500;">Duration Used</span>
                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($session->session_duration_seconds ?? 0) ?> sec</span>
                </div>
                <div style="display: flex; justify-content: space-between;">
                    <span style="color: var(--text-muted); font-weight: 500;">Created At</span>
                    <span style="font-weight: 600; color: var(--text-main);"><?= date('M j, Y H:i:s', strtotime($session->created_at)) ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Photos Grid Card -->
    <div class="card" style="grid-column: span 1 / -1;">
        <div class="card-header">
            <h3 class="card-title">Session Photos</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($photos)): ?>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem;">
                    <?php foreach ($photos as $photo): ?>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; background: var(--bg-body); padding: 0.5rem; border-radius: var(--radius-md); border: 1px solid var(--border-color);">
                            <img src="<?= URLROOT; ?>/<?= htmlspecialchars($photo->file_path) ?>" alt="Session Photo" style="width: 100%; aspect-ratio: 3/2; object-fit: cover; border-radius: var(--radius-sm);">
                            <div style="font-size: 0.75rem; color: var(--text-muted); text-align: center; display: flex; justify-content: center; align-items: center; gap: 0.25rem;">
                                <i data-feather="clock" style="width: 12px;"></i> <?= date('H:i:s', strtotime($photo->taken_at)) ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 2rem; color: var(--text-muted); border: 2px dashed var(--border-color); border-radius: var(--radius-md);">
                    <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                    <p style="margin: 0;">No photos captured in this session</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>