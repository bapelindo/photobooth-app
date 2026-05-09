<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="layers" style="color: var(--primary);"></i>
            Photostrips
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">View all generated photostrips and their status.</p>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Photostrip Info</th>
                    <th>Frame Details</th>
                    <th>Status Overview</th>
                    <th>Date Created</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($photostrips)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i data-feather="image" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p style="margin: 0; font-size: 1.125rem; font-weight: 500; color: var(--text-main);">No photostrips found</p>
                        <p style="margin: 0.25rem 0 0 0; font-size: 0.875rem;">Photostrips will appear here after photo sessions are completed.</p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($photostrips as $photostrip): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: var(--primary); font-size: 1rem; margin-bottom: 0.25rem;">
                                #<?= htmlspecialchars($photostrip->id) ?>
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 0.25rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                    <i data-feather="camera" style="width: 14px; color: var(--text-light);"></i>
                                    Session #<?= htmlspecialchars($photostrip->session_id) ?>
                                </div>
                                <?php if (!empty($photostrip->order_id)): ?>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                    <i data-feather="shopping-bag" style="width: 14px; color: var(--text-light);"></i>
                                    Order #<?= htmlspecialchars($photostrip->order_id) ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">
                                <?= htmlspecialchars($photostrip->frame_name ?? 'N/A') ?>
                            </div>
                            <?php if (!empty($photostrip->package_name)): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                                <i data-feather="package" style="width: 14px; color: var(--text-light);"></i>
                                <?= htmlspecialchars($photostrip->package_name) ?>
                            </div>
                            <?php endif; ?>
                            <?php if (!empty($photostrip->final_image_path)): ?>
                            <div style="display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: var(--success); font-weight: 500;">
                                <i data-feather="check-circle" style="width: 12px;"></i> Image Ready
                            </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                    <span style="color: var(--text-muted); width: 40px;">Print:</span>
                                    <?php $p_status = ($photostrip->is_printed ?? false) ? 'success' : 'warning'; ?>
                                    <?php $p_text = ($photostrip->is_printed ?? false) ? 'Printed' : 'Pending'; ?>
                                    <span class="badge badge-<?= $p_status ?>" style="display: inline-flex; align-items: center; gap: 0.25rem;"><i data-feather="<?= ($photostrip->is_printed ?? false) ? 'check-circle' : 'clock' ?>" style="width: 10px;"></i> <?= $p_text ?></span>
                                </div>
                                <?php if (isset($photostrip->email_sent)): ?>
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem;">
                                    <span style="color: var(--text-muted); width: 40px;">Email:</span>
                                    <?php $e_status = $photostrip->email_sent ? 'success' : 'secondary'; ?>
                                    <?php $e_text = $photostrip->email_sent ? 'Sent' : 'Not sent'; ?>
                                    <span class="badge badge-<?= $e_status ?>" style="display: inline-flex; align-items: center; gap: 0.25rem;"><i data-feather="<?= $photostrip->email_sent ? 'mail' : 'mail-x' ?>" style="width: 10px;"></i> <?= $e_text ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div style="font-weight: 500; color: var(--text-main); margin-bottom: 0.25rem;">
                                <?= date('M j, Y', strtotime($photostrip->created_at)) ?>
                            </div>
                            <div style="font-size: 0.875rem; color: var(--text-muted);">
                                <?= date('H:i', strtotime($photostrip->created_at)) ?>
                            </div>
                        </td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <?php if (!empty($photostrip->final_image_path)): ?>
                                <a href="<?= URLROOT . $photostrip->final_image_path ?>" class="btn btn-secondary btn-sm" target="_blank" title="View Image">
                                    <i data-feather="external-link"></i> <span class="hide-mobile">View</span>
                                </a>
                                <?php endif; ?>
                                <?php if (isset($photostrip->id)): ?>
                                <a href="<?= URLROOT; ?>/admin/photostrips/view/<?= $photostrip->id ?>" class="btn btn-secondary btn-sm" title="View Details">
                                    <i data-feather="eye"></i> <span class="hide-mobile">Details</span>
                                </a>
                                <?php endif; ?>
                                <form action="<?= URLROOT; ?>/admin/gallery/delete/<?= $photostrip->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this photostrip?');" style="display:inline;">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Delete">
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