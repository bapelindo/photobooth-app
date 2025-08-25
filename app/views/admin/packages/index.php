<h1>Manage Packages</h1>
<p>
    <a href="<?= URLROOT; ?>/admin/packages/create" class="btn btn-primary">Create New Package</a>
</p>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Photo Limit</th>
            <th>Retake Limit</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($packages as $package): ?>
        <tr>
            <td><?= htmlspecialchars($package->name) ?></td>
            <td>Rp <?= number_format($package->price, 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($package->photo_limit) ?></td>
            <td><?= htmlspecialchars($package->retake_limit) ?></td>
            <td class="action-links">
                <a href="<?= URLROOT; ?>/admin/packages/edit/<?= $package->id ?>" class="btn btn-secondary">Edit</a>
                <form action="<?= URLROOT; ?>/admin/packages/delete/<?= $package->id ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this package?');" style="display:inline;">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>