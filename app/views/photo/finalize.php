<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fotomu Sudah Jadi! 🎉</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF; --secondary-color: #FF6584; --accent-color: #FFD166;
            --card-bg: #FFFFFF; --dark-text: #333; --font-display: 'Fredoka One', cursive;
        }
        html, body { height: 100%; margin: 0; overflow: auto; background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%); }
        body { font-family: 'Poppins', sans-serif; display: flex; justify-content: center; align-items: flex-start; padding: 40px; box-sizing: border-box; }
        .finalize-container { display: flex; flex-direction: column; gap: 40px; width: 100%; max-width: 1000px; }
        .photo-display { text-align: center; }
        .photo-display h1 { font-family: var(--font-display); color: var(--card-bg); text-shadow: 2px 2px 4px rgba(0,0,0,0.3); font-size: clamp(2rem, 5vh, 2.8rem); margin-top: 0; margin-bottom: 30px; }
        .photostrip-gallery { display: flex; gap: 30px; justify-content: center; flex-wrap: wrap; }
        .photo-preview { height: 60vh; max-height: 500px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); border: 5px solid white; transition: transform 0.3s ease; }
        .photo-preview:hover { transform: scale(1.05); }
        .actions-panel { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px); border-radius: 25px; padding: 30px; display: flex; flex-direction: column; gap: 25px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37); }
        .action-box { text-align: center; }
        .action-box h3 { font-family: var(--font-display); font-size: 1.5rem; margin: 0 0 20px 0; color: var(--dark-text); }
        .main-actions { display: flex; justify-content: center; gap: 20px; margin-bottom: 20px; }
        .action-button { display: inline-block; font-family: var(--font-display); font-size: 1.1rem; color: var(--dark-text); padding: 15px 25px; border: 3px solid var(--dark-text); border-radius: 50px; text-decoration: none; cursor: pointer; transition: all 0.2s ease; box-shadow: 4px 4px 0 var(--dark-text); }
        .action-button:hover { transform: translate(-2px, -2px); box-shadow: 6px 6px 0 var(--dark-text); }
        .action-button:active { transform: translate(4px, 4px); box-shadow: none; }
        #print-btn { background-color: var(--accent-color); }
        .new-session-btn { background-color: #f0f0f0; }
        .email-form { display: flex; flex-direction: column; gap: 15px; align-items: center; }
        .email-input { width: 100%; max-width: 400px; padding: 12px; border-radius: 50px; border: 2px solid #ccc; font-size: 1rem; box-sizing: border-box; text-align: center; }
        #email-status { font-weight: bold; min-height: 22px; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="finalize-container">
        <div class="photo-display">
            <h1>Ini Dia Hasil Karyamu!</h1>
            <div class="photostrip-gallery">
                <?php foreach($data['final_photos'] as $photo): ?>
                    <img src="<?= URLROOT . htmlspecialchars($photo->file_path); ?>" alt="Final Photobooth Photo" class="photo-preview">
                <?php endforeach; ?>
            </div>
        </div>

        <div class="actions-panel">
            <div class="action-box">
                <h3>Mau diapain fotonya?</h3>
                <div class="main-actions">
                    <button id="print-btn" class="action-button">🖨️ Cetak Semua</button>
                    <a href="<?= URLROOT; ?>/packages" class="action-button new-session-btn">📸 Sesi Baru</a>
                </div>
            </div>
            
            <div class="action-box">
                <h3>Kirim Semua Foto ke Emailmu!</h3>
                <p style="font-size: 0.9rem; color: #555;">(Kamu akan mendapatkan semua hasil photostrip DAN semua foto mentah yang kamu simpan)</p>
                <div class="email-form">
                    <input type="email" id="email-input" class="email-input" placeholder="contoh@email.com">
                    <button id="send-email-btn" class="action-button">Kirim Sekarang!</button>
                </div>
                <p id="email-status"></p>
            </div>
        </div>
    </div>

<script>
    const transactionId = '<?= $data['transaction_id'] ?>';

    // Print Logic
    const printBtn = document.getElementById('print-btn');
    const statusEl = document.getElementById('email-status'); // Shared status element
    printBtn.addEventListener('click', async () => {
        printBtn.disabled = true;
        printBtn.textContent = 'Mencetak...';
        statusEl.textContent = '';
        
        try {
            const response = await fetch('<?= URLROOT; ?>/photo/ajax_print_photo', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ transaction_id: transactionId })
            });
            const result = await response.json();
            if (!result.success) throw new Error(result.message);
            statusEl.textContent = 'Semua foto berhasil dikirim ke printer!';
            statusEl.style.color = 'green';
        } catch (error) {
            statusEl.textContent = 'Error: ' + error.message;
            statusEl.style.color = 'red';
        } finally {
            printBtn.disabled = false;
            printBtn.textContent = '🖨️ Cetak Semua';
        }
    });

    // Email Logic
    const sendEmailBtn = document.getElementById('send-email-btn');
    const emailInput = document.getElementById('email-input');
    sendEmailBtn.addEventListener('click', async () => {
        const email = emailInput.value;
        if (!email) {
            statusEl.textContent = 'Harap masukkan alamat email.';
            statusEl.style.color = 'red';
            return;
        }
        sendEmailBtn.disabled = true;
        sendEmailBtn.textContent = 'Mengirim...';
        statusEl.textContent = '';
        
        try {
            const response = await fetch('<?= URLROOT; ?>/photo/send_email', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ transaction_id: transactionId, email: email })
            });
            const result = await response.json();
            if (!result.success) throw new Error(result.message);
            statusEl.textContent = 'Email berhasil dikirim! Cek inbox atau spam.';
            statusEl.style.color = 'green';
            emailInput.value = '';
        } catch (error) {
            statusEl.textContent = 'Error: ' + error.message;
            statusEl.style.color = 'red';
        } finally {
            sendEmailBtn.disabled = false;
            sendEmailBtn.textContent = 'Kirim Sekarang!';
        }
    });
</script>

</body>
</html>