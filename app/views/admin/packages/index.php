<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
            <i data-feather="box" style="color: var(--primary);"></i>
            Manage Packages
        </h1>
        <p style="color: var(--text-muted); margin: 0; font-size: 0.875rem;">Create and modify photobooth service packages.</p>
    </div>
    <div style="display: flex; gap: 0.75rem; align-items: center;">
        <a href="<?= URLROOT; ?>/admin/packages/create" class="btn btn-primary">
            <i data-feather="plus"></i> Create Package
        </a>
    </div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Package Details</th>
                    <th>Pricing</th>
                    <th>Limits</th>
                    <th>Settings</th>
                    <th style="text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($packages as $package): ?>
                <tr>
                    <td>
                        <div style="font-weight: 600; color: var(--text-main); margin-bottom: 0.25rem;">
                            <?= htmlspecialchars($package->name) ?>
                        </div>
                        <div style="font-size: 0.875rem; color: var(--text-muted); max-width: 250px; line-height: 1.4;">
                            <?= htmlspecialchars($package->description ?? 'No description') ?>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--success); font-size: 1rem;">
                            Rp <?= number_format($package->price, 0, ',', '.') ?>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                <i data-feather="printer" style="width: 14px; color: var(--text-light);"></i>
                                <span><?= htmlspecialchars($package->photo_limit ?? 2) ?> prints</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                <i data-feather="image" style="width: 14px; color: var(--text-light);"></i>
                                <span><?= htmlspecialchars($package->photo_slots ?? 4) ?> photos/strip</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                <i data-feather="layers" style="width: 14px; color: var(--text-light);"></i>
                                <span><?= htmlspecialchars($package->frame_limit ?? 1) ?> frames</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                <i data-feather="clock" style="width: 14px; color: var(--text-light);"></i>
                                <span><?= floor(($package->session_duration ?? 300) / 60) ?> min limit</span>
                            </div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; color: var(--text-muted);">
                                <i data-feather="save" style="width: 14px; color: var(--text-light);"></i>
                                <span><?= htmlspecialchars($package->max_save_photos ?? 20) ?> save limit</span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="<?= URLROOT; ?>/admin/packages/edit/<?= $package->id ?>" class="btn btn-secondary btn-sm" title="Edit Package">
                                <i data-feather="edit-2"></i>
                                <span class="hide-mobile">Edit</span>
                            </a>
                            <form action="<?= URLROOT; ?>/admin/packages/delete/<?= $package->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" style="display:inline;">
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete Package">
                                    <i data-feather="trash-2"></i>
                                    <span class="hide-mobile">Delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($packages)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 3rem 1rem; color: var(--text-muted);">
                        <i data-feather="inbox" style="width: 32px; height: 32px; margin-bottom: 1rem; opacity: 0.5;"></i>
                        <p>No packages found. Create your first package to get started.</p>
                    </td>
                </tr>
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