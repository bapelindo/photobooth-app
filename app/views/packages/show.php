<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Paket Photobooth!</title>
    <style>
        body { font-family: 'Comic Sans MS', cursive, sans-serif; background-color: #f0f8ff; text-align: center; }
        .header { padding: 40px; }
        .header h1 { font-size: 3em; color: #ff4500; }
        .packages-container { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .package-card {
            background-color: #fff;
            border: 5px dashed #ff69b4;
            border-radius: 20px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .package-card:hover {
            transform: scale(1.05) rotate(3deg);
        }
        .package-card h2 { color: #1e90ff; }
        .package-card .price { font-size: 2em; font-weight: bold; color: #32cd32; margin: 10px 0; }
        .package-card ul { list-style: none; padding: 0; text-align: left; }
        .package-card ul li { margin-bottom: 10px; }
        .package-card ul li::before { content: '⭐'; margin-right: 10px; }
        .select-button {
            background-color: #ff6347;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 50px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .select-button:hover { background-color: #ff4500; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Pilih Paket Kecerianmu!</h1>
        <p>Setiap foto adalah ledakan tawa yang tak terlupakan!</p>
    </div>

    <div class="packages-container">
        <?php foreach ($packages as $package): ?>
            <div class="package-card">
                <h2><?= htmlspecialchars($package->name); ?></h2>
                <div class="price">Rp <?= number_format($package->price, 0, ',', '.'); ?></div>
                <p><?= htmlspecialchars($package->description); ?></p>
                <ul>
                    <li><b><?= $package->photo_limit; ?>x</b> Ambil Foto</li>
                    <li><b><?= $package->retake_limit; ?>x</b> Ulang Ah!</li>
                </ul>
                <button class="select-button" onclick="location.href='<?= URLROOT; ?>/payment/process/<?= $package->id ?>'">Pilih Paket Ini!</button>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>