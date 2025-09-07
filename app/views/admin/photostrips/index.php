<div class="page-header">
    <h1>Photostrips</h1>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Photostrip ID</th>
                <th>Session</th>
                <th>Frame</th>
                <th>Print Status</th>
                <th>Email Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($photostrips)): ?>
            <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted);">No photostrips found</td>
            </tr>
            <?php else: ?>
                <?php foreach ($photostrips as $photostrip): ?>
                <tr>
                    <td><strong>#<?= htmlspecialchars($photostrip->id) ?></strong></td>
                    <td>#<?= htmlspecialchars($photostrip->session_id) ?></td>
                    <td><?= htmlspecialchars($photostrip->frame_name ?? 'N/A') ?></td>
                    <td>
                        <span class="badge badge-<?= $photostrip->print_status === 'printed' ? 'success' : ($photostrip->print_status === 'queued' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst(htmlspecialchars($photostrip->print_status ?? 'none')) ?>
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-<?= $photostrip->email_status === 'sent' ? 'success' : ($photostrip->email_status === 'queued' ? 'warning' : 'secondary') ?>">
                            <?= ucfirst(htmlspecialchars($photostrip->email_status ?? 'none')) ?>
                        </span>
                    </td>
                    <td><?= date('M j, Y H:i', strtotime($photostrip->created_at)) ?></td>
                    <td class="action-links">
                        <a href="<?= URLROOT; ?>/admin/photostrips/view/<?= $photostrip->id ?>" class="btn btn-secondary btn-sm">
                            <i data-feather="eye"></i> View
                        </a>
                        <form action="<?= URLROOT; ?>/admin/photostrips/regenerate/<?= $photostrip->id ?>" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i data-feather="refresh-cw"></i> Regenerate
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