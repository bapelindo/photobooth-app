<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisasi & Cetak - Photobooth</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(135deg, #fcb69f 0%, #fa709a 100%);
        }

        body {
            height: 100vh;
            margin: 0;
            padding: 20px;
            overflow: auto;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            display: flex;
            justify-content: center; align-items: center;box-sizing: border-box;
        }
        
        .finalize-container {
            display: grid;
            grid-template-rows: auto 1fr;
            grid-template-columns: 1fr 300px;
            gap: 10px;
            width: 100%;
            height: 95vh;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);
            border-radius: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px 20px;
            text-align: center;
            backdrop-filter: blur(10px);
            position: relative;
            flex-shrink: 0;
        }

        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-back:hover {
            background: #554dff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .header-panel h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin: 0 0 5px 0;
            font-size: 1.8rem;
        }

        .header-panel p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }

        .photostrips-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px;
            backdrop-filter: blur(10px);
            overflow-y: auto;
            max-height: 100%;
        }

        .photostrips-panel h3 {
            margin: 0 0 10px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--secondary-color);
            font-size: 1.5rem;
            text-align: center;
        }

        .photostrips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .photostrip-card {
            background: white;
            border-radius: 15px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            border: 3px solid transparent;
        }

        .photostrip-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
            border-color: var(--primary-color);
        }

        .photostrip-preview {
            width: 100%;
            max-width: 120px;
            /* 2:6 aspect ratio = 1:3 */
            aspect-ratio: 1 / 3;
            object-fit: cover;
            border-radius: 10px;
            margin: 0 auto 15px auto;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            display: block;
        }

        .photostrip-info h4 {
            margin: 0 0 10px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            font-size: 1.1rem;
        }

        .print-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .print-status.printed {
            background: var(--success-color);
            color: white;
        }

        .print-status.pending {
            background: var(--warning-color);
            color: white;
        }

        .btn-print {
            background: var(--success-color);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-print:hover {
            background: #45a049;
            transform: translateY(-1px);
        }

        .btn-print:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .actions-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 10px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 10px;
            max-height: 100%;
            overflow-y: auto;
        }

        .actions-panel h3 {
            margin: 0;
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            font-size: 1.3rem;
            text-align: center;
        }

        .session-summary {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 10px;
            padding: 15px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .summary-item:last-child {
            margin-bottom: 0;
            font-weight: 700;
            color: var(--primary-color);
            border-top: 1px solid rgba(108, 99, 255, 0.3);
            padding-top: 8px;
        }

        .email-section {
            border: 2px solid var(--primary-color);
            border-radius: 15px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.5);
        }

        .email-section h4 {
            margin: 0 0 15px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--secondary-color);
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
            box-sizing: border-box;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary-color);
        }

        .btn {
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 25px;
            font-family: 'Fredoka One', cursive;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-success {
            background: var(--success-color);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .controls-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            backdrop-filter: blur(10px);
        }


        .progress-indicator {
            text-align: center;
            color: var(--primary-color);
            font-weight: 600;
        }

        .progress-bar {
            width: 300px;
            height: 8px;
            background: rgba(108, 99, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
            margin: 10px auto;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--success-color));
            border-radius: 4px;
            width: 0%;
            transition: width 0.5s ease;
        }

        .status-message {
            padding: 10px 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
        }

        .status-success {
            background: rgba(76, 175, 80, 0.2);
            color: var(--success-color);
            border: 2px solid var(--success-color);
        }

        .status-error {
            background: rgba(255, 101, 132, 0.2);
            color: var(--secondary-color);
            border: 2px solid var(--secondary-color);
        }

        @media (max-width: 768px) {
            .finalize-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto 0.4fr 0.6fr;
                padding: 8px;
                gap: 8px;
                height: 100vh;
            }

            .photostrips-panel {
                order: 3;
                max-height: 100%;
                overflow-y: auto;
            }

            .actions-panel {
                order: 2;
                max-height: 100%;
                overflow-y: auto;
                padding: 12px;
            }

            .photostrips-grid {
                grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
                gap: 10px;
            }

            .btn-back {
                position: static;
                margin-bottom: 10px;
                align-self: flex-start;
                padding: 8px 15px;
                font-size: 0.8rem;
            }

            .header-panel {
                padding: 12px 15px;
            }

            .header-panel h1 {
                font-size: 1.4rem;
                margin-bottom: 3px;
            }

            .header-panel p {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>
    <div class="finalize-container">
        <div class="header-panel">
            <button class="btn-back" onclick="goToPackages()">
                ← Kembali ke Paket
            </button>
            <h1>🎉 Sesi Foto Selesai!</h1>
            <p>Photostrip Anda sudah siap untuk dicetak dan dikirim via email</p>
        </div>

        <div class="photostrips-panel">
            <h3>📸 Photostrip Anda (<?= count($data['photostrips']) ?>)</h3>
            
            <?php if (isset($data['debug_info'])): ?>
                <div style="background: #f0f0f0; padding: 10px; margin: 10px 0; border-radius: 5px; font-size: 0.8rem;">
                    <strong>Debug Info:</strong><br>
                    Frame Limit: <?= $data['debug_info']['frame_limit'] ?? 'N/A' ?><br>
                    Total Photostrips Found: <?= $data['debug_info']['total_photostrips_found'] ?? 'N/A' ?><br>
                    Final Photostrips Shown: <?= $data['debug_info']['final_photostrips_shown'] ?? 'N/A' ?><br>
                    Session Photos Count: <?= $data['debug_info']['session_photos_count'] ?? 'N/A' ?><br>
                    <?php if (isset($data['debug_info']['regeneration_log'])): ?>
                        <strong>Regeneration Log:</strong><br>
                        <?php foreach ($data['debug_info']['regeneration_log'] as $log): ?>
                            - <?= htmlspecialchars($log) ?><br>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="photostrips-grid">
                <?php foreach ($data['photostrips'] as $photostrip): ?>
                    <div class="photostrip-card">
                        <?php if ($photostrip->final_image_path): ?>
                            <img src="<?= URLROOT . $photostrip->final_image_path ?>" 
                                 alt="<?= $photostrip->frame_name ?>" 
                                 class="photostrip-preview">
                        <?php else: ?>
                            <div class="photostrip-preview" style="background: #f0f0f0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #666; font-size: 0.8rem; padding: 10px;">
                                <span>Preview tidak tersedia</span>
                                <small style="margin-top: 5px;">
                                    ID: <?= $photostrip->id ?><br>
                                    Final Path: <?= $photostrip->final_image_path ?? 'NULL' ?><br>
                                    Layout Data: <?= !empty($photostrip->layout_data) ? 'Ada' : 'Kosong' ?>
                                </small>
                            </div>
                        <?php endif; ?>
                        
                        <div class="photostrip-info">
                            <h4><?= $photostrip->frame_name ?></h4>
                            <div class="print-status <?= $photostrip->is_printed ? 'printed' : 'pending' ?>">
                                <?= $photostrip->is_printed ? '✓ Sudah Dicetak' : '⏳ Belum Dicetak' ?>
                            </div>
                            <br>
                            <button class="btn-print" 
                                    data-photostrip-id="<?= $photostrip->id ?>"
                                    <?= $photostrip->is_printed ? 'disabled' : '' ?>
                                    onclick="printPhotostrip(<?= $photostrip->id ?>)">
                                <?= $photostrip->is_printed ? '✓ Tercetak' : '🖨️ Cetak' ?>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="actions-panel">
            <h3>📊 Ringkasan & Aksi</h3>
            
            <div class="session-summary">
                <div class="summary-item">
                    <span>Paket:</span>
                    <span><?= $data['package']->name ?></span>
                </div>
                <div class="summary-item">
                    <span>Frame Dipilih:</span>
                    <span><?= count($data['photostrips']) ?></span>
                </div>
                <div class="summary-item">
                    <span>Foto Tersimpan:</span>
                    <span><?= count($data['session_photos']) ?></span>
                </div>
                <div class="summary-item">
                    <span>Total Photostrip:</span>
                    <span><?= count($data['photostrips']) ?></span>
                </div>
            </div>

            <div class="email-section">
                <h4>📧 Kirim via Email</h4>
                <div id="status-messages"></div>
                
                <form id="email-form" onsubmit="sendEmail(event)">
                    <div class="form-group">
                        <label for="email">Alamat Email:</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="contoh@email.com" required>
                    </div>
                    
                    <button type="submit" class="btn btn-primary" id="send-email-btn">
                        📧 Kirim Email
                    </button>
                </form>

                <div class="progress-indicator" id="email-progress" style="display: none;">
                    <div>Mengirim email...</div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-fill"></div>
                    </div>
                </div>
            </div>

            <button class="btn btn-success" onclick="printAllPhotostrips()" id="print-all-btn">
                🖨️ Cetak Semua
            </button>
        </div>

    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const photostrips = <?= json_encode($data['photostrips']) ?>;
        let printedCount = <?= array_sum(array_map(function($p) { return $p->is_printed ? 1 : 0; }, $data['photostrips'])) ?>;
        
        document.addEventListener('DOMContentLoaded', () => {
            updatePrintAllButton();
        });

        function printPhotostrip(photostripId) {
            const button = document.querySelector(`[data-photostrip-id="${photostripId}"]`);
            const card = button.closest('.photostrip-card');
            const status = card.querySelector('.print-status');
            
            button.disabled = true;
            button.textContent = '⏳ Mencetak...';
            
            fetch('<?= URLROOT ?>/photo/print-photostrip', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    photostrip_id: photostripId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.textContent = '✓ Tercetak';
                    status.textContent = '✓ Sudah Dicetak';
                    status.className = 'print-status printed';
                    printedCount++;
                    updatePrintAllButton();
                    showMessage('Photostrip berhasil dicetak!', 'success');
                } else {
                    button.disabled = false;
                    button.textContent = '🖨️ Cetak';
                    showMessage('Gagal mencetak photostrip: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                button.disabled = false;
                button.textContent = '🖨️ Cetak';
                showMessage('Terjadi kesalahan saat mencetak', 'error');
            });
        }

        function printAllPhotostrips() {
            const unprintedButtons = document.querySelectorAll('.btn-print:not(:disabled)');
            
            if (unprintedButtons.length === 0) {
                showMessage('Semua photostrip sudah dicetak!', 'success');
                return;
            }

            const printAllBtn = document.getElementById('print-all-btn');
            printAllBtn.disabled = true;
            printAllBtn.textContent = '⏳ Mencetak Semua...';

            // Print each photostrip sequentially
            let printIndex = 0;
            function printNext() {
                if (printIndex < unprintedButtons.length) {
                    const button = unprintedButtons[printIndex];
                    const photostripId = button.dataset.photostripId;
                    
                    printPhotostrip(photostripId);
                    printIndex++;
                    setTimeout(printNext, 2000); // Wait 2 seconds between prints
                } else {
                    printAllBtn.disabled = false;
                    printAllBtn.textContent = '🖨️ Cetak Semua';
                    showMessage('Semua photostrip telah dicetak!', 'success');
                }
            }

            printNext();
        }

        function sendEmail(event) {
            event.preventDefault();
            
            const email = document.getElementById('email').value;
            const sendButton = document.getElementById('send-email-btn');
            const progress = document.getElementById('email-progress');
            const progressFill = document.getElementById('progress-fill');
            
            sendButton.disabled = true;
            sendButton.textContent = '⏳ Mengirim...';
            progress.style.display = 'block';
            
            // Animate progress bar
            let progressValue = 0;
            const progressInterval = setInterval(() => {
                progressValue += 10;
                progressFill.style.width = progressValue + '%';
                if (progressValue >= 90) {
                    clearInterval(progressInterval);
                }
            }, 200);

            fetch('<?= URLROOT ?>/photo/send-session-email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    email: email
                })
            })
            .then(response => response.json())
            .then(data => {
                clearInterval(progressInterval);
                progressFill.style.width = '100%';
                
                setTimeout(() => {
                    progress.style.display = 'none';
                    progressFill.style.width = '0%';
                    sendButton.disabled = false;
                    sendButton.textContent = '📧 Kirim Email';
                    
                    if (data.success) {
                        showMessage('Email berhasil dikirim ke ' + email + '!', 'success');
                        document.getElementById('email-form').reset();
                    } else {
                        showMessage('Gagal mengirim email: ' + data.message, 'error');
                    }
                }, 1000);
            })
            .catch(error => {
                console.error('Error:', error);
                clearInterval(progressInterval);
                progress.style.display = 'none';
                progressFill.style.width = '0%';
                sendButton.disabled = false;
                sendButton.textContent = '📧 Kirim Email';
                showMessage('Terjadi kesalahan saat mengirim email', 'error');
            });
        }

        function updatePrintAllButton() {
            const printAllBtn = document.getElementById('print-all-btn');
            const unprintedCount = photostrips.length - printedCount;
            
            if (unprintedCount === 0) {
                printAllBtn.textContent = '✓ Semua Sudah Dicetak';
                printAllBtn.disabled = true;
            } else {
                printAllBtn.textContent = `🖨️ Cetak Semua (${unprintedCount})`;
                printAllBtn.disabled = false;
            }
        }

        function showMessage(message, type) {
            const statusMessages = document.getElementById('status-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `status-message status-${type}`;
            messageDiv.textContent = message;
            
            statusMessages.innerHTML = '';
            statusMessages.appendChild(messageDiv);
            
            setTimeout(() => {
                messageDiv.remove();
            }, 5000);
        }

        function goToPackages() {
            window.location.href = '<?= URLROOT ?>/packages';
        }

        // Auto-refresh print status every 30 seconds
        setInterval(() => {
            fetch('<?= URLROOT ?>/photo/check-print-status/' + sessionId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.updates) {
                        data.updates.forEach(update => {
                            const button = document.querySelector(`[data-photostrip-id="${update.id}"]`);
                            const card = button.closest('.photostrip-card');
                            const status = card.querySelector('.print-status');
                            
                            if (update.is_printed && !button.disabled) {
                                button.textContent = '✓ Tercetak';
                                button.disabled = true;
                                status.textContent = '✓ Sudah Dicetak';
                                status.className = 'print-status printed';
                                printedCount++;
                            }
                        });
                        updatePrintAllButton();
                    }
                })
                .catch(error => {
                    console.error('Error checking print status:', error);
                });
        }, 30000);
    </script>
</body>
</html>