<?php require APPROOT . '/views/admin/layouts/header.php'; ?>
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>Nama Acara</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['events'] as $event): ?>
            <tr>
                <td><?= htmlspecialchars($event->event_name); ?></td>
                <td><?= date('d M Y', strtotime($event->event_date)); ?></td>
                <td><span class="status-<?= $event->status; ?>"><?= ucfirst($event->status); ?></span></td>
                <td class="actions">
                    <a href="<?= URLROOT; ?>/admin/assets/<?= $event->id; ?>" class="btn-sm btn-secondary">Aset</a>
                    <a href="<?= URLROOT; ?>/admin/gallery/<?= $event->id; ?>" class="btn-sm btn-primary">Galeri</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require APPROOT . '/views/admin/layouts/footer.php'; ?>