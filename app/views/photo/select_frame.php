<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Frame Favoritmu!</title>
    <style>
        body { font-family: 'Comic Sans MS', cursive, sans-serif; background-color: #f0f8ff; text-align: center; }
        .header { padding: 40px; }
        .header h1 { font-size: 3em; color: #ff4500; }
        .frames-container { display: flex; justify-content: center; gap: 30px; flex-wrap: wrap; }
        .frame-card {
            background-color: #fff;
            border: 5px dashed #ff69b4;
            border-radius: 20px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            cursor: pointer;
        }
        .frame-card:hover {
            transform: scale(1.05) rotate(3deg);
        }
        .frame-card img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        .frame-card h2 { color: #1e90ff; font-size: 1.2em; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>Pilih Frame Kecerianmu!</h1>
        <p>Klik frame untuk memulai sesi foto!</p>
    </div>

    <div class="frames-container">
        <?php foreach ($frames as $frame): ?>
            <a href="<?= URLROOT; ?>/photo/capture/<?= $transaction_id ?>/<?= $frame->id ?>" class="frame-card">
                <img src="<?= URLROOT; ?>/public<?= htmlspecialchars($frame->file_path); ?>" alt="<?= htmlspecialchars($frame->name); ?>">
                <h2><?= htmlspecialchars($frame->name); ?></h2>
            </a>
        <?php endforeach; ?>
    </div>

</body>
</html>