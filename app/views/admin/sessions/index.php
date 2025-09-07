<div class="page-header">
    <h1>Photo Sessions</h1>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Session ID</th>
                <th>Package</th>
                <th>Status</th>
                <th>Photos Saved</th>
                <th>Photos Taken</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($sessions)): ?>
            <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted);">No sessions found</td>
            </tr>
            <?php else: ?>
                <?php foreach ($sessions as $session): ?>
                <tr>
                    <td><strong>#<?= htmlspecialchars($session->id) ?></strong></td>
                    <td><?= htmlspecialchars($session->package_name ?? 'N/A') ?></td>
                    <td>
                        <span class="badge badge-<?= $session->session_status === 'completed' ? 'success' : ($session->session_status === 'active' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst(htmlspecialchars($session->session_status)) ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($session->photos_saved ?? 0) ?></td>
                    <td><?= htmlspecialchars($session->photos_taken ?? 0) ?></td>
                    <td><?= date('M j, Y H:i', strtotime($session->created_at)) ?></td>
                    <td class="action-links">
                        <a href="<?= URLROOT; ?>/admin/sessions/view/<?= $session->id ?>" class="btn btn-secondary btn-sm">
                            <i data-feather="eye"></i> View
                        </a>
                        <form action="<?= URLROOT; ?>/admin/sessions/delete/<?= $session->id ?>" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i data-feather="trash-2"></i> Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<style>
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