document.addEventListener('DOMContentLoaded', () => {
    // --- Referensi Elemen DOM ---
    const video = document.getElementById('live-preview');
    const canvas = document.getElementById('capture-canvas');
    const countdownEl = document.getElementById('countdown');
    const safeZone = document.getElementById('safe-zone');
    const mainStage = document.querySelector('.main-stage');
    
    // UI Panels
    const controlsPanel = document.querySelector('.controls-panel');
    const reviewPanel = document.querySelector('.photo-review-panel');
    const reviewPhotoImg = document.getElementById('review-photo');

    // Buttons
    const captureBtn = document.getElementById('capture-btn');
    const keepBtn = document.getElementById('keep-btn');
    const discardBtn = document.getElementById('discard-btn');
    const finishSessionBtn = document.getElementById('finish-session-btn');

    // Info Displays
    const timerDisplay = document.getElementById('timer');
    const shotCounterDisplay = document.getElementById('shot-counter');

    // --- State & Configuration ---
    let stream = null;
    let sessionTimer;
    let timeLeft = PACKAGE_DATA.session_time_limit;
    let shotsLeft = PACKAGE_DATA.photo_shot_limit;
    const savedRawPhotos = [];
    let lastCapturedPhoto = null;

    // --- Inisialisasi ---
    async function init() {
        await initCamera();
        // Event listener di window.resize tetap ada untuk penyesuaian
        window.addEventListener('resize', calculateSafeZone);
    }

    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 1920 }, height: { ideal: 1080 }, facingMode: 'user' },
                audio: false
            });
            video.srcObject = stream;
            
            // REVISI: Tunggu hingga metadata video dimuat sebelum melanjutkan
            video.onloadedmetadata = () => {
                video.play(); // Pastikan video diputar
                calculateSafeZone(); // Hitung zona aman setelah video siap
                startSessionTimer();
                updateUI();
            };

        } catch (err) {
            console.error("Error accessing camera:", err);
            mainStage.innerHTML = `<p style="color: white; text-align: center;">Kamera tidak dapat diakses. Pastikan Anda memberikan izin.</p>`;
        }
    }

    // --- Logika Inti Sesi (tetap sama) ---

    function startSessionTimer() {
        if (timeLeft < 0) { // Handle unlimited time
            timerDisplay.textContent = "∞";
            return;
        }
        sessionTimer = setInterval(() => {
            timeLeft--;
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerDisplay.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
            if (timeLeft <= 0) {
                endSession();
            }
        }, 1000);
    }

    async function takePhoto() {
        if (shotsLeft <= 0) return;

        captureBtn.disabled = true;
        safeZone.style.display = 'none';

        await startCountdown(3);

        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        // Balikkan kanvas secara horizontal untuk mencocokkan pratinjau cermin
        context.translate(video.videoWidth, 0);
        context.scale(-1, 1);
        
        context.drawImage(video, 0, 0, video.videoWidth, video.videoHeight);
        
        lastCapturedPhoto = canvas.toDataURL('image/jpeg', 0.9);
        reviewPhotoImg.src = lastCapturedPhoto;
        
        shotsLeft--;
        updateUI('review');
    }

    function keepPhoto() {
        savedRawPhotos.push({
            imageData: lastCapturedPhoto
        });
        lastCapturedPhoto = null;
        if (shotsLeft <= 0) {
            endSession();
        } else {
            updateUI('capture');
        }
    }

    function discardPhoto() {
        lastCapturedPhoto = null;
        if (shotsLeft <= 0) {
            endSession();
        } else {
            updateUI('capture');
        }
    }

    function endSession() {
        clearInterval(sessionTimer);
        if (timeLeft >= 0) {
            timerDisplay.textContent = "00:00";
        }
        captureBtn.disabled = true;
        reviewPanel.style.display = 'none';
        controlsPanel.style.display = 'none';
        finishSessionBtn.style.display = 'block';
        video.style.display = 'none';
        safeZone.style.display = 'none';

        mainStage.innerHTML = `<h1 id="countdown" style="display: flex; align-items: center; justify-content: center; font-family: var(--font-display); font-size: 80px; color: var(--accent-color); text-shadow: 5px 5px 10px rgba(0,0,0,0.5);">Sesi Selesai!</h1>`;

        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    async function proceedToLayoutEditor() {
        finishSessionBtn.disabled = true;
        finishSessionBtn.textContent = 'Memproses...';

        try {
            const response = await fetch(`${URLROOT}/photo/ajax_save_raw_photos`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: TRANSACTION_ID,
                    photos: savedRawPhotos
                })
            });

            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal menyimpan foto mentah.');
            }
            
            window.location.href = result.editor_url;

        } catch (error) {
            console.error('Error proceeding to editor:', error);
            finishSessionBtn.textContent = `Error: ${error.message}`;
        }
    }
    
    // --- Fungsi Helper & UI ---

    function updateUI(mode = 'capture') {
        shotCounterDisplay.textContent = shotsLeft;
        captureBtn.disabled = (shotsLeft <= 0);
        const canFinish = savedRawPhotos.length > 0 || (shotsLeft <= 0 && lastCapturedPhoto === null);
        finishSessionBtn.style.display = canFinish ? 'block' : 'none';


        if (mode === 'capture') {
            controlsPanel.style.display = 'block';
            reviewPanel.style.display = 'none';
            safeZone.style.display = 'block';
        } else if (mode === 'review') {
            controlsPanel.style.display = 'none';
            reviewPanel.style.display = 'block';
            finishSessionBtn.style.display = 'none';
        }
    }
    
    function calculateSafeZone() {
        // REVISI: Pastikan video sudah memiliki dimensi
        if (!video.videoWidth || !ALL_SLOTS_DATA || ALL_SLOTS_DATA.length === 0) {
            return;
        }

        let minX = 100, minY = 100, maxX = 0, maxY = 0;

        ALL_SLOTS_DATA.forEach(slot => {
            minX = Math.min(minX, slot.left);
            minY = Math.min(minY, slot.top);
            maxX = Math.max(maxX, slot.left + slot.width);
            maxY = Math.max(maxY, slot.top + slot.height);
        });

        // Kalkulasi berdasarkan rasio video aktual yang ditampilkan
        const videoAspectRatio = video.videoWidth / video.videoHeight;
        const stageAspectRatio = mainStage.clientWidth / mainStage.clientHeight;
        
        let displayedVideoWidth, displayedVideoHeight;

        if (videoAspectRatio > stageAspectRatio) {
            displayedVideoWidth = mainStage.clientWidth;
            displayedVideoHeight = displayedVideoWidth / videoAspectRatio;
        } else {
            displayedVideoHeight = mainStage.clientHeight;
            displayedVideoWidth = displayedVideoHeight * videoAspectRatio;
        }
        
        const offsetX = (mainStage.clientWidth - displayedVideoWidth) / 2;
        const offsetY = (mainStage.clientHeight - displayedVideoHeight) / 2;
        
        // Menggunakan persentase dari dimensi video yang ditampilkan
        safeZone.style.left = `${(minX / 100) * displayedVideoWidth + offsetX}px`;
        safeZone.style.top = `${(minY / 100) * displayedVideoHeight + offsetY}px`;
        safeZone.style.width = `${((maxX - minX) / 100) * displayedVideoWidth}px`;
        safeZone.style.height = `${((maxY - minY) / 100) * displayedVideoHeight}px`;
    }

    function startCountdown(seconds) {
        return new Promise(resolve => {
            let count = seconds;
            countdownEl.style.display = 'flex';
            countdownEl.textContent = count;
            const timer = setInterval(() => {
                count--;
                if (count > 0) {
                    countdownEl.textContent = count;
                } else {
                    clearInterval(timer);
                    countdownEl.textContent = '📸';
                    setTimeout(() => {
                       countdownEl.style.display = 'none';
                       resolve();
                    }, 500);
                }
            }, 1000);
        });
    }

    // --- Event Listeners ---
    captureBtn.addEventListener('click', takePhoto);
    keepBtn.addEventListener('click', keepPhoto);
    discardBtn.addEventListener('click', discardPhoto);
    finishSessionBtn.addEventListener('click', proceedToLayoutEditor);
    
    init();
});