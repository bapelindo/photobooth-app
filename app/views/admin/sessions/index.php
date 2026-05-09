<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="camera" style="color: var(--primary);"></i>
            Photo Sessions
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">View and manage user photo sessions.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Session Details</th>
                    <th>Package Info</th>
                    <th>Status</th>
                    <th>Session Stats</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($sessions)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i data-feather="camera-off" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p style="margin: 0; font-size: 1.125rem; font-weight: 500; color: var(--text-main);">No photo sessions found</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem;">Sessions will appear here after users take photos.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($sessions as $session): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--primary); font-size: 1rem; margin-bottom: 0.25rem;">
                                #<?= htmlspecialchars($session->id) ?>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                    <i data-feather="calendar" style="width: 14px; color: var(--text-light);"></i>
                                    <?= date('M j, Y H:i', strtotime($session->created_at)) ?>
                                </div>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                    <i data-feather="shopping-bag" style="width: 14px; color: var(--text-light);"></i>
                                    Order #<?= htmlspecialchars($session->order_id ?? 'N/A') ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">
                                <?= htmlspecialchars($session->package_name ?? 'N/A') ?>
                            </div>
                            <div style="font-size: 0.875rem; color: var(--success); font-weight: 600;">
                                Rp <?= number_format($session->amount ?? $session->price ?? 0, 0, ',', '.') ?>
                            </div>
                        </td>
                        <td>
                            <?php 
                                $status = $session->session_status ?? 'unknown';
                                $badgeClass = 'badge-secondary';
                                if ($status === 'completed') $badgeClass = 'badge-success';
                                elseif ($status === 'active') $badgeClass = 'badge-warning';
                            ?>
                            <span class="badge <?= $badgeClass ?>" style="padding: 0.375rem 0.75rem; font-size: 0.75rem;">
                                <?= ucfirst(htmlspecialchars($status)) ?>
                            </span>
                        </td>
                        <td>
                            <div style="display: flex; gap: 1.5rem; font-size: 0.875rem;">
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <i data-feather="save" style="width: 16px; color: var(--text-muted); margin-bottom: 0.25rem;"></i>
                                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars(max($session->photos_saved ?? 0, $session->photos_count ?? 0)) ?></span>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <i data-feather="camera" style="width: 16px; color: var(--text-muted); margin-bottom: 0.25rem;"></i>
                                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars(max($session->photos_taken ?? 0, $session->total_photos_captured ?? 0)) ?></span>
                                </div>
                                <div style="display: flex; flex-direction: column; align-items: center;">
                                    <i data-feather="clock" style="width: 16px; color: var(--text-muted); margin-bottom: 0.25rem;"></i>
                                    <span style="font-weight: 600; color: var(--text-main);"><?= htmlspecialchars($session->session_duration_seconds ?? 0) ?>s</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="<?= URLROOT; ?>/admin/sessions/view/<?= $session->id ?>" class="btn btn-secondary btn-sm" title="View Session">
                                    <i data-feather="eye"></i>
                                    <span class="hide-mobile">View</span>
                                </a>
                                <form action="<?= URLROOT; ?>/admin/sessions/delete/<?= $session->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this session?');" style="display:inline;">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete Session">
                                        <i data-feather="trash-2"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
    @media (max-width: 768px) {
        .hide-mobile { display: none; }
    }
</style>