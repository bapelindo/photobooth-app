<?php require APPROOT . '/views/layouts/header.php'; ?>

<style>
    .packages-container { display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; }
    .package-card { background: #fff; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.08); padding: 2rem; text-align: center; width: 300px; transition: all 0.3s ease; }
    .package-card:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
    .package-card h3 { font-size: 1.8rem; color: var(--primary-color); margin-bottom: 0.5rem; }
    .package-card .price { font-size: 2.5rem; font-weight: 700; color: var(--secondary-color); margin-bottom: 1rem; }
    .package-card .price span { font-size: 1rem; font-weight: 400; color: #777; }
    .package-card ul { list-style: none; margin: 1.5rem 0; padding: 0; text-align: left; }
    .package-card li { margin-bottom: 0.7rem; }
</style>

<main class="main-content landing-page">
    <div class="content-box" style="max-width: 900px;">
        <h1>Pilih Paket Photobooth Anda</h1>
        <p>Setiap paket dirancang untuk memberikan Anda pengalaman yang tak terlupakan. Pilih yang paling cocok untuk Anda!</p>
        
        <div class="packages-container">
            <?php if (empty($data['packages'])): ?>
                <p>Saat ini tidak ada paket yang tersedia. Silakan kembali lagi nanti.</p>
            <?php else: ?>
                <?php foreach($data['packages'] as $package): ?>
                    <div class="package-card">
                        <h3><?= htmlspecialchars($package->name); ?></h3>
                        <div class="price">Rp<?= number_format($package->price, 0, ',', '.'); ?></div>
                        <ul>
                            <li><?= htmlspecialchars($package->description); ?></li>
                            <li><strong><?= $package->retake_limit; ?>x</strong> kesempatan mengulang</li>
                        </ul>
                        <a href="<?= URLROOT; ?>/packages/select/<?= $package->id; ?>" class="btn btn-primary">Pilih Paket Ini</a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require APPROOT . '/views/layouts/footer.php'; ?>