<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Foto Photobooth Anda!</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eeeeee; }
        .header h1 { color: #333333; }
        .content { padding: 20px 0; }
        .content p { color: #555555; line-height: 1.6; }
        .footer { text-align: center; padding-top: 20px; border-top: 1px solid #eeeeee; font-size: 12px; color: #999999; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Foto Photobooth Anda!</h1>
        </div>
        <div class="content">
            <p>Halo,</p>
            <p>Terima kasih telah mengabadikan momen spesial Anda di acara <strong><?= htmlspecialchars($data['eventName']); ?></strong>. Kami harap Anda bersenang-senang!</p>
            <p>Foto Anda terlampir dalam email ini. Jangan ragu untuk membagikannya di media sosial Anda!</p>
            <p>Sampai jumpa di acara berikutnya!</p>
        </div>
        <div class="footer">
            <p>Email ini dibuat secara otomatis. Mohon untuk tidak membalas.</p>
        </div>
    </div>
</body>
</html>