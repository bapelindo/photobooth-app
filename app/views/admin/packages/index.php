<div class="page-header" style="display: flex; justify-content: space-between; align-items: center;">
    <h1>Manage Packages</h1>
    <a href="<?= URLROOT; ?>/admin/packages/create" class="btn btn-primary">
        <i data-feather="plus"></i> Create New Package
    </a>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Prints</th>
                <th>Photos/Strip</th>
                <th>Frame Limit</th>
                <th>Duration</th>
                <th>Max Save</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($packages as $package): ?>
            <tr>
                <td><strong><?= htmlspecialchars($package->name) ?></strong></td>
                <td>Rp <?= number_format($package->price, 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($package->photo_limit ?? 2) ?></td>
                <td><?= htmlspecialchars($package->photo_slots ?? 4) ?></td>
                <td><?= htmlspecialchars($package->frame_limit ?? 1) ?></td>
                <td><?= htmlspecialchars($package->session_duration ?? 300) ?>s</td>
                <td><?= htmlspecialchars($package->max_save_photos ?? 20) ?></td>
                <td class="action-links">
                    <a href="<?= URLROOT; ?>/admin/packages/edit/<?= $package->id ?>" class="btn btn-secondary btn-sm"><i data-feather="edit-2"></i> Edit</a>
                    <form action="<?= URLROOT; ?>/admin/packages/delete/<?= $package->id ?>" method="POST" onsubmit="return confirm('Are you sure?');" style="display:inline;">
                        <button type="submit" class="btn btn-danger btn-sm"><i data-feather="trash-2"></i> Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>