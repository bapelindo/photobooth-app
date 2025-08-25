<h1>Manage Assets</h1>
<p>
    <a href="<?= URLROOT; ?>/admin/assets/create" class="btn btn-primary">Upload New Asset</a>
</p>

<?php
$frames = array_filter($assets, fn($asset) => $asset->type === 'frame');
$stickers = array_filter($assets, fn($asset) => $asset->type === 'sticker');
?>

<h2>Frames</h2>
<?php if (empty($frames)): ?>
    <p>No frames uploaded yet.</p>
<?php else: ?>
<table>
    <thead>
        <tr>
            <th>Preview</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($frames as $asset): ?>
        <tr>
            <td><img src="<?= htmlspecialchars($asset->file_path) ?>" alt="<?= htmlspecialchars($asset->name) ?>" class="asset-preview"></td>
            <td><?= htmlspecialchars($asset->name) ?></td>
            <td>
                <form action="<?= URLROOT; ?>/admin/assets/delete/<?= $asset->id ?>" method="POST" onsubmit="return confirm('Are you sure?');">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<?php endif; ?>

<h2>Stickers</h2>
<?php if (empty($stickers)): ?>
    <p>No stickers uploaded yet.</p>
<?php else: ?>
<table>
    <!-- Sama seperti tabel Frames, tapi untuk stiker -->
    <!-- (Kode ini bisa direfaktor menjadi satu partial view jika diinginkan) -->
</table>
<?php endif; ?>