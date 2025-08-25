<?php require APPROOT . '/views/layouts/header.php'; ?>

<style>
    .finalize-container { text-align: center; max-width: 600px; margin: auto; }
    .photo-preview { max-width: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); margin-bottom: 2rem; }
    .action-buttons { display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem; }
    .email-form { display: flex; justify-content: center; gap: 0.5rem; margin-top: 1.5rem; }
    .email-input { padding: 0.8rem; border-radius: 50px; border: 2px solid #ccc; font-size: 1rem; width: 250px; }
    #email-status { margin-top: 1rem; font-weight: bold; }
</style>

<div class="main-content finalize-container">
    <h1>Foto Anda Siap! ✨</h1>
    <p>Satu langkah lagi untuk menyimpan kenangan Anda.</p>

<div id="print-area">
    <img src="<?= URLROOT . htmlspecialchars($photo->file_path); ?>" alt="Final Photobooth Photo" class="photo-preview">
</div>

    <div class="action-buttons">
        <button onclick="window.print()" class="action-button">🖨️ Cetak Foto</button>
        <a href="<?= URLROOT; ?>" class="action-button secondary">📸 Sesi Baru</a>
    </div>
    
    <hr>

    <div>
        <h3>Kirim ke Email</h3>
        <div class="email-form">
            <input type="email" id="email-input" class="email-input" placeholder="Masukkan alamat email...">
            <button id="send-email-btn" class="action-button">Kirim</button>
        </div>
        <p id="email-status"></p>
    </div>
</div>

<script>
    const sendEmailBtn = document.getElementById('send-email-btn');
    const emailInput = document.getElementById('email-input');
    const emailStatus = document.getElementById('email-status');
    const photoId = <?= $photo->id; ?>;

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

        try {
            const response = await fetch('<?= URLROOT; ?>/photo/send_email', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ photo_id: photoId, email: email })
            });

            const result = await response.json();

            if (result.success) {
                emailStatus.textContent = 'Email berhasil dikirim!';
                emailStatus.style.color = 'green';
                emailInput.value = '';
            } else {
                throw new Error(result.message || 'Gagal mengirim email.');
            }
        } catch (error) {
            emailStatus.textContent = 'Error: ' + error.message;
            emailStatus.style.color = 'red';
        } finally {
            sendEmailBtn.disabled = false;
            sendEmailBtn.textContent = 'Kirim';
        }
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>