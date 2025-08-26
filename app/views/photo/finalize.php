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
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --accent-color: #FFD166;
            --card-bg: #FFFFFF;
            --dark-text: #333;
            --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }

        html, body {
            height: 100%;
            margin: 0;
            overflow: hidden;
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        }

        body {
            font-family: var(--font-main);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
            opacity: 0;
            animation: fadeIn 0.5s ease-in forwards;
        }

        .finalize-container {
            display: flex;
            gap: 40px;
            width: 100%;
            max-width: 1000px;
            height: 90vh;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px);
            border-radius: 25px;
            padding: 40px;
            box-sizing: border-box;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
            border: 1px solid rgba(255, 255, 255, 0.2);
            align-items: center;
            opacity: 0;
            transform: scale(0.98);
            animation: fadeIn 0.5s ease-out 0.2s forwards;
        }

        .finalize-container > * {
            opacity: 0;
            animation: fadeInElements 0.5s ease-out 0.7s forwards;
        }
        
        @keyframes fadeIn {
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes fadeInElements {
            to { opacity: 1; }
        }

        .photo-display {
            flex-grow: 1;
            text-align: center;
        }
        
        .photo-display h1 {
            font-family: var(--font-display);
            color: var(--primary-color);
            font-size: clamp(2rem, 5vh, 2.8rem);
            margin-top: 0;
            margin-bottom: 20px;
        }
        
        .photo-preview {
            max-width: 100%;
            max-height: 55vh;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            border: 5px solid white;
        }

        .actions-panel {
            flex-basis: 350px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .action-box, .email-box {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            text-align: center;
            border: 2px solid #eee;
        }
        
        .action-box h3, .email-box h3 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            margin-top: 0;
            margin-bottom: 20px;
            color: var(--dark-text);
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        
        .action-button {
            display: inline-block;
            font-family: var(--font-display);
            font-size: 1.1rem;
            color: var(--dark-text);
            padding: 15px 25px;
            border: 3px solid var(--dark-text);
            border-radius: 50px;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 4px 4px 0 var(--dark-text);
            background-color: var(--accent-color);
        }
        .action-button:hover {
            transform: translate(-2px, -2px);
            box-shadow: 6px 6px 0 var(--dark-text);
        }
        .action-button:active {
            transform: translate(4px, 4px);
            box-shadow: none;
        }
        .action-button.secondary {
            background-color: #f0f0f0;
        }

        .email-form { display: flex; flex-direction: column; gap: 15px; }
        .email-input {
            width: 100%;
            padding: 12px;
            border-radius: 50px;
            border: 2px solid #ccc;
            font-size: 1rem;
            box-sizing: border-box;
            text-align: center;
        }
        .email-input:focus {
            border-color: var(--primary-color);
            outline: none;
        }
        
        /* Perubahan untuk status email */
        #email-status { 
            font-weight: bold; 
            min-height: 22px; /* Jaga tinggi elemen agar tidak collaps */
            transition: opacity 0.4s ease-out; /* Tambahkan transisi fade-out */
            opacity: 1; /* Awalnya terlihat */
        }
        #email-status.fade-out {
            opacity: 0; /* Menjadi transparan saat fade-out */
        }

    </style>
</head>
<body>

    <div class="finalize-container">
        
        <div class="photo-display">
            <h1>Ini Dia Hasil Karyamu!</h1>
            <img src="<?= URLROOT . htmlspecialchars($photo->file_path); ?>" alt="Final Photobooth Photo" class="photo-preview">
        </div>

        <div class="actions-panel">
            <div class="action-box">
                <h3>Mau diapain fotonya?</h3>
                <div class="action-buttons">
                    <button id="print-btn" class="action-button">🖨️ Cetak</button>
                    <a href="<?= URLROOT; ?>" class="action-button secondary">📸 Sesi Baru</a>
                </div>
            </div>
            
            <div class="email-box">
                <h3>Kirim ke Emailmu!</h3>
                <div class="email-form">
                    <input type="email" id="email-input" class="email-input" placeholder="contoh@email.com">
                    <button id="send-email-btn" class="action-button">Kirim Sekarang!</button>
                </div>
                <p id="email-status"></p>
            </div>
        </div>

    </div>

<script>
    const printBtn = document.getElementById('print-btn');
    const printStatus = document.getElementById('email-status');
    const photoId = <?= $photo->id; ?>;
    let printStatusTimeout;

    printBtn.addEventListener('click', async () => {
        printBtn.disabled = true;
        printBtn.textContent = 'Mencetak...';
        printStatus.textContent = '';
        printStatus.classList.remove('fade-out');
        clearTimeout(printStatusTimeout);
        
        try {
            const response = await fetch('<?= URLROOT; ?>/photo/ajax_print_photo', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ photo_id: photoId })
            });

            const result = await response.json();
            
            if (response.ok && result.success) {
                printStatus.textContent = 'Foto berhasil dikirim ke printer!';
                printStatus.style.color = 'green';
                printStatusTimeout = setTimeout(() => {
                    printStatus.classList.add('fade-out');
                }, 3000);
            } else {
                throw new Error(result.message || 'Gagal mengirim perintah cetak.');
            }
        } catch (error) {
            printStatus.textContent = 'Error: ' + error.message;
            printStatus.style.color = 'red';
        } finally {
            printBtn.disabled = false;
            printBtn.textContent = '🖨️ Cetak';
        }
    });

    const sendEmailBtn = document.getElementById('send-email-btn');
    const emailInput = document.getElementById('email-input');
    const emailStatus = document.getElementById('email-status');
    
    let statusTimeout;
    sendEmailBtn.addEventListener('click', async () => {
        const email = emailInput.value;
        if (!email) {
            emailStatus.textContent = 'Harap masukkan alamat email.';
            emailStatus.style.color = 'red';
            return;
        }
        sendEmailBtn.disabled = true;
        sendEmailBtn.textContent = 'Mengirim...';
        emailStatus.textContent = '';
        clearTimeout(statusTimeout);
        try {
            const response = await fetch('<?= URLROOT; ?>/photo/send_email', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ photo_id: photoId, email: email })
            });
            const result = await response.json();
            if (response.ok && result.success) {
                emailStatus.textContent = 'Email berhasil dikirim!';
                emailStatus.style.color = 'green';
                emailStatus.classList.remove('fade-out');
                emailInput.value = '';

                statusTimeout = setTimeout(() => {
                    emailStatus.classList.add('fade-out');
                }, 3000);

            } else {
                throw new Error(result.message || 'Gagal mengirim email.');
            }
        } catch (error) {
            emailStatus.textContent = 'Error: ' + error.message;
            emailStatus.style.color = 'red';
            emailStatus.classList.remove('fade-out');
        } finally {
            sendEmailBtn.disabled = false;
            sendEmailBtn.textContent = 'Kirim Sekarang!';
        }
    });
</script>

</body>
</html>