<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalisasi & Cetak - Photobooth</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto+Mono:wght@400;700&family=Orbitron:wght@700;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-color: #2a5298;
            /* Deep Blue */
            --secondary-color: #1e3c72;
            /* Darker Blue */
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(120deg, #c2e9fb 0%, #a1c4fd 50%, #e2d0cb 100%);
            --glass-bg: rgba(255, 255, 255, 0.85);
            --dark-text: #1B365D;
        }

        /* Firefox Scrollbar */
        html {
            scrollbar-width: thin;
            scrollbar-color: rgba(42, 82, 152, 0.5) rgba(255, 255, 255, 0.5);
        }

        /* Exact same animation as select-frame */
        html,
        body {
            height: 100vh;
            margin: 0;
            overflow: auto;
            /* Prevent body scroll, keep app-like */
        }

        body {
            font-family: 'Roboto Condensed', sans-serif;
            background: var(--bg-gradient);
            opacity: 1;
            transition: opacity 0.4s ease-out;
            position: relative;
        }

        /* Animated Clouds */
        .clouds {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
            z-index: 0;
        }

        .cloud {
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
            animation: float linear infinite;
        }

        .cloud::before,
        .cloud::after {
            content: '';
            position: absolute;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 100px;
        }

        .cloud1 {
            width: 120px;
            height: 50px;
            top: 15%;
            left: -150px;
            animation-duration: 45s;
        }

        .cloud1::before {
            width: 60px;
            height: 50px;
            top: -25px;
            left: 20px;
        }

        .cloud1::after {
            width: 70px;
            height: 40px;
            top: -15px;
            right: 20px;
        }

        .cloud2 {
            width: 100px;
            height: 40px;
            top: 65%;
            left: -120px;
            animation-duration: 55s;
            animation-delay: 5s;
        }

        .cloud2::before {
            width: 50px;
            height: 40px;
            top: -20px;
            left: 15px;
        }

        .cloud2::after {
            width: 60px;
            height: 30px;
            top: -10px;
            right: 15px;
        }

        .cloud3 {
            width: 140px;
            height: 60px;
            top: 35%;
            left: -180px;
            animation-duration: 60s;
            animation-delay: 15s;
        }

        .cloud3::before {
            width: 70px;
            height: 60px;
            top: -30px;
            left: 25px;
        }

        .cloud3::after {
            width: 80px;
            height: 50px;
            top: -20px;
            right: 25px;
        }

        @keyframes float {
            from {
                transform: translateX(0);
            }

            to {
                transform: translateX(120vw);
            }
        }

        body.fade-out {
            opacity: 0;
        }



        .finalize-container {
            display: grid;
            grid-template-rows: auto minmax(0, 1fr);
            /* Ensure row allows shrinking */
            grid-template-columns: 1fr 350px;
            gap: 10px;
            /* Match decoration gap */
            width: 100%;
            height: 100%;
            /* Match decoration height */
            /* Responsive width */
            max-width: 1600px;
            /* Match decoration max-width */
            max-height: none;
            /* Remove max-height restriction */
            padding: 20px;
            /* Match decoration padding */
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.5);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
            position: relative;
            z-index: 1;
            margin: 0 auto;
            overflow: visible;
            /* Allow titles to pop, but constraint content */
        }

        .finalize-container.content-fade-out {
            opacity: 0;
        }

        .finalize-container>* {
            opacity: 0;
            animation: innerElementFadeIn 0.5s ease-in 0.7s forwards;
        }

        @keyframes contentFadeIn {
            to {
                opacity: 1;
            }
        }

        @keyframes innerElementFadeIn {
            to {
                opacity: 1;
            }
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px 160px;
            /* Increased padding to clear back button */
            text-align: center;
            backdrop-filter: blur(10px);
            position: relative;
            flex-shrink: 0;
        }

        .btn-back {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.5);
            color: #2a5298; /* Dark blue for visibility */
            border: 2px solid #2a5298; /* Visible border */
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            backdrop-filter: blur(5px);
            z-index: 10;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-back:hover {
            background: #2a5298;
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(42, 82, 152, 0.3);
        }

        .header-panel h1 {
            font-family: 'Orbitron', sans-serif;
            color: #2a5298;
            margin: 0 0 5px 0;
            font-size: 1.8rem;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .header-panel p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }

        .photostrips-panel {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 15px;
            backdrop-filter: blur(10px);
            /* Remove overflow-y: auto from here to allow title overlay */
            overflow: visible !important;
            display: flex;
            flex-direction: column;
            border: 2px solid #2a5298;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            padding-top: 25px;
        }

        .photostrips-panel::before {
            content: 'YOUR PHOTOSTRIPS';
            position: absolute;
            top: -12px;
            left: 20px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 4px 12px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 6px;
            letter-spacing: 1.5px;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .photostrips-panel h3 {
            display: none;
        }

        /* New scroll container */
        .photostrips-scroll-container {
            overflow-y: auto;
            flex: 1;
            width: 100%;
            padding-right: 5px;
            /* Avoid scrollbar overlap */
            scrollbar-width: thin;
            scrollbar-color: rgba(42, 82, 152, 0.5) transparent;
        }

        .photostrips-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: 20px;
        }

        .photostrip-card {
            background: rgba(255, 255, 255, 0.6);
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            transition: all 0.3s ease;
            border: 1px solid rgba(42, 82, 152, 0.2);
        }

        .photostrip-card:hover {
            transform: translateY(-5px);
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 10px 25px rgba(30, 60, 114, 0.15);
            border-color: #2a5298;
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
            font-family: 'Orbitron', sans-serif;
            color: #2a5298;
            font-size: 1rem;
            letter-spacing: 1px;
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
            background: transparent;
            color: var(--success-color);
            border: 2px solid var(--success-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            text-transform: uppercase;
        }

        .btn-print:hover {
            background: var(--success-color);
            color: white;
            transform: translateY(-1px);
        }

        .btn-print:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .actions-panel {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            border-radius: 12px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 15px;
            overflow: visible !important;
            /* Allow label overlay */
            border: 2px solid #2a5298;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            padding-top: 25px;
        }

        /* New scroll container for actions */
        .actions-scroll-container {
            overflow-y: auto;
            flex: 1;
            width: 100%;
            padding-right: 5px;
            display: flex;
            flex-direction: column;
            gap: 15px;
            scrollbar-width: thin;
            scrollbar-color: rgba(42, 82, 152, 0.5) transparent;
        }

        .actions-panel::before {
            content: 'FLIGHT LOG';
            position: absolute;
            top: -12px;
            left: 20px;
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            padding: 4px 12px;
            font-family: 'Roboto Mono', monospace;
            font-size: 0.7rem;
            font-weight: 700;
            border-radius: 6px;
            letter-spacing: 1.5px;
            z-index: 10;
            box-shadow: 0 4px 12px rgba(42, 82, 152, 0.5);
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .actions-panel h3 {
            display: none;
        }

        .session-summary {
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 12px;
            border: 1px solid rgba(42, 82, 152, 0.2);
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
            border: 2px solid #2a5298;
            border-radius: 10px;
            padding: 15px;
            background: rgba(255, 255, 255, 0.4);
        }

        .email-section h4 {
            margin: 0 0 15px 0;
            font-family: 'Orbitron', sans-serif;
            color: #2a5298;
            text-align: center;
            font-size: 0.9rem;
            letter-spacing: 1px;
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
            border-radius: 6px;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2a5298 0%, #1e3c72 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(30, 60, 114, 0.3);
        }

        .btn-success {
            background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);
            color: #1e3c72;
            box-shadow: 0 4px 15px rgba(56, 249, 215, 0.3);
            font-weight: 800;
        }

        .btn-success:hover {
            box-shadow: 0 6px 20px rgba(56, 249, 215, 0.5);
            transform: translateY(-2px);
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
            body {
                overflow: auto;
                /* Allow scroll on mobile */
                height: auto;
            }

            .finalize-container {
                display: flex;
                flex-direction: column;
                height: auto;
                min-height: 100vh;
                padding: 15px;
                gap: 20px;
                width: 100%;
                max-width: 100%;
            }

            .photostrips-panel {
                order: 2;
                max-height: 50vh;
                min-height: 300px;
            }

            .actions-panel {
                order: 3;
                max-height: none;
                overflow: visible !important;
                padding-bottom: 20px;
            }

            .header-panel {
                order: 1;
                padding: 15px;
                /* Reset padding for mobile flow */
            }

            .photostrips-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 15px;
            }

            .btn-back {
                position: relative;
                top: 0;
                left: 0;
                margin-bottom: 10px;
                display: inline-block;
                width: auto;
            }

            .header-panel h1 {
                font-size: 1.4rem;
                margin-bottom: 5px;
            }

            .header-panel p {
                font-size: 0.8rem;
            }
        }
    </style>
</head>

<body>
    <div class="clouds">
        <div class="cloud cloud1"></div>
        <div class="cloud cloud2"></div>
        <div class="cloud cloud3"></div>
    </div>

    <div class="finalize-container">
        <div class="header-panel">
            <button class="btn-back" onclick="goToPackages()">
                ← Kembali ke Paket
            </button>
            <h1>PHOTOBOOTH AIRWAYS</h1>
            <p style="letter-spacing: 1px; text-transform: uppercase; font-size: 0.9rem;">DECORATION CLASS / FINALIZE
            </p>
        </div>

        <div class="photostrips-panel">
            <h3>📸 Photostrip Anda (<?= count($data['photostrips']) ?>)</h3>


            <div class="photostrips-scroll-container">
                <div class="photostrips-grid">
                    <?php foreach ($data['photostrips'] as $photostrip): ?>
                        <div class="photostrip-card">
                            <?php if ($photostrip->final_image_path): ?>
                                <img src="<?= URLROOT . $photostrip->final_image_path ?>?v=<?= time() ?>"
                                    alt="<?= $photostrip->frame_name ?>" class="photostrip-preview">
                            <?php else: ?>
                                <div class="photostrip-preview"
                                    style="background: #f0f0f0; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #666; font-size: 0.8rem; padding: 10px;">
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
                                <button class="btn-print" data-photostrip-id="<?= $photostrip->id ?>"
                                    <?= $photostrip->is_printed ? 'disabled' : '' ?>
                                    onclick="printPhotostrip(<?= $photostrip->id ?>)">
                                    <?= $photostrip->is_printed ? '✓ Tercetak' : '🖨️ Cetak' ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="actions-panel">
            <h3>📊 Ringkasan & Aksi</h3>

            <div class="actions-scroll-container">
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

    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;

        document.addEventListener('DOMContentLoaded', () => {
            updatePrintAllButton();
            setInterval(checkPrintStatus, 10000); // Check every 10 seconds
        });

        function printPhotostrip(photostripId, silent = false) {
            const button = document.querySelector(`[data-photostrip-id="${photostripId}"]`);
            if (!button) return;

            const card = button.closest('.photostrip-card');
            const status = card.querySelector('.print-status');

            button.disabled = true;
            button.textContent = '⏳ Mengantri...';

            fetch('<?= URLROOT ?>/photo/print-photostrip', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ photostrip_id: photostripId })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        button.textContent = '✓ Diantrikan';
                        status.textContent = '⏳ Dalam Antrian';
                        if (!silent) {
                            showMessage('Photostrip telah ditambahkan ke antrian cetak.', 'success');
                        }
                    } else {
                        button.disabled = false;
                        button.textContent = '🖨️ Cetak';
                        if (!silent) {
                            showMessage('Gagal menambahkan ke antrian cetak: ' + data.message, 'error');
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    button.disabled = false;
                    button.textContent = '🖨️ Cetak';
                    if (!silent) {
                        showMessage('Terjadi kesalahan saat mencetak.', 'error');
                    }
                });
        }

        function printAllPhotostrips() {
            const unprintedButtons = document.querySelectorAll('.btn-print:not(:disabled)');

            if (unprintedButtons.length === 0) {
                showMessage('Semua photostrip sudah dicetak atau sedang dalam antrian.', 'success');
                return;
            }

            const printAllBtn = document.getElementById('print-all-btn');
            printAllBtn.disabled = true;
            printAllBtn.textContent = '⏳ Menambahkan ke Antrian...';

            let printIndex = 0;
            function printNext() {
                if (printIndex < unprintedButtons.length) {
                    const button = unprintedButtons[printIndex];
                    const photostripId = button.dataset.photostripId;

                    printPhotostrip(photostripId, true); // silent = true

                    printIndex++;
                    setTimeout(printNext, 500); // 0.5 second delay between queueing
                } else {
                    showMessage('Semua photostrip telah ditambahkan ke antrian cetak.', 'success');
                    updatePrintAllButton();
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
                headers: { 'Content-Type': 'application/json' },
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
                            showMessage('Email telah dijadwalkan untuk dikirim! Proses pengiriman sedang berjalan di background.', 'success');
                            document.getElementById('email-form').reset();
                        } else {
                            showMessage('Gagal menambahkan email ke queue: ' + data.message, 'error');
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
            const unprintedButtons = document.querySelectorAll('.btn-print:not(:disabled)');
            const unprintedCount = unprintedButtons.length;

            if (unprintedCount === 0) {
                printAllBtn.textContent = '✓ Semua Sudah Diantrikan';
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
            // Allow navigation when going to packages
            <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
                allowNavigation = true;
            <?php endif; ?>

            // Same fade-out animation as select-frame
            document.body.classList.add('fade-out');

            setTimeout(() => {
                window.location.href = '<?= URLROOT ?>/packages';
            }, 500);
        }

        function checkPrintStatus() {
            fetch('<?= URLROOT ?>/photo/check-print-status/' + sessionId)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.updates) {
                        let hasChanges = false;
                        data.updates.forEach(update => {
                            const button = document.querySelector(`[data-photostrip-id="${update.id}"]`);
                            if (!button) return;

                            const isAlreadyPrinted = button.disabled && button.textContent === '✓ Tercetak';

                            if (update.is_printed && !isAlreadyPrinted) {
                                hasChanges = true;
                                const card = button.closest('.photostrip-card');
                                const status = card.querySelector('.print-status');

                                button.textContent = '✓ Tercetak';
                                button.disabled = true;
                                status.textContent = '✓ Sudah Dicetak';
                                status.className = 'print-status printed';
                            }
                        });

                        if (hasChanges) {
                            updatePrintAllButton();
                        }
                    }
                })
                .catch(error => {
                    console.error('Error checking print status:', error);
                });
        }

        // Simple back/refresh protection with popup
        <?php if (ENABLE_SESSION_REFRESH_BACK): ?>
            let allowNavigation = false;

            // Handle refresh attempts
            window.addEventListener('beforeunload', function (e) {
                if (allowNavigation) {
                    return;
                }

                e.preventDefault();
                e.returnValue = '';
                return '';
            });

            // Handle browser back button
            let currentUrl = window.location.href;
            window.history.pushState({}, '', currentUrl);

            window.addEventListener('popstate', function (e) {
                if (allowNavigation) {
                    return;
                }

                // Show confirmation for back button
                if (confirm('⚠️ PERINGATAN!\n\nAnda mencoba kembali ke halaman sebelumnya. Proses finalisasi akan dibatalkan.\n\nApakah Anda yakin ingin melanjutkan?')) {
                    allowNavigation = true;
                    window.history.go(-1);
                } else {
                    // Stay on current page
                    window.history.pushState({}, '', currentUrl);
                }
            });

            console.log('Simple back/refresh protection loaded for finalize session');
        <?php endif; ?>
    </script>
</body>

</html>