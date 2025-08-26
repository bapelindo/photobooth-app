document.addEventListener('DOMContentLoaded', () => {
    // --- Referensi Elemen DOM ---
    const video = document.getElementById('live-preview');
    const canvas = document.getElementById('capture-canvas');
    const countdownEl = document.getElementById('countdown');
    const infoText = document.getElementById('info-text');
    const retakeCountEl = document.getElementById('retake-count');
    const captureBtn = document.getElementById('capture-btn');
    const photoControls = document.getElementById('photo-controls');
    const keepBtn = document.getElementById('keep-btn');
    const retakePhotoBtn = document.getElementById('retake-photo-btn');
    const finishBtn = document.getElementById('finish-btn');
    
    // --- MODIFIED: Target the dropdown now ---
    const filterSelect = document.getElementById('filter-select');

    // Variabel state
    let currentSlot = 0;
    const capturedPhotos = [];
    let stream = null;
    let selectedFilter = 'none'; 

    // --- Inisialisasi Kamera (Tidak Berubah) ---
    async function initCamera() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({
                video: { width: { ideal: 1280 }, height: { ideal: 720 } },
                audio: false
            });
            video.srcObject = stream;
            updateUIForCapture();
        } catch (err) {
            console.error("Error accessing camera:", err);
            infoText.textContent = "Kamera Error!";
            captureBtn.style.display = 'none';
        }
    }

    // --- Manajemen UI (Tidak Berubah) ---
    function updateUIForCapture() {
        infoText.textContent = `Foto ke-${currentSlot + 1} dari ${PHOTO_LIMIT}`;
        photoControls.style.display = 'none';
        captureBtn.style.display = 'flex';
        finishBtn.style.display = 'none';
        document.querySelectorAll('.preview-slot').forEach((slot, index) => {
            slot.classList.toggle('active', index === currentSlot);
        });
    }

    function updateUIForConfirmation() {
        infoText.textContent = "Gimana Hasilnya?";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'flex';
        retakePhotoBtn.disabled = RETAKES_LEFT <= 0;
    }
    
    function updateUIForFinish() {
        infoText.textContent = "Sesi Selesai!";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'none';
        finishBtn.style.display = 'flex';
        document.querySelectorAll('.preview-slot').forEach(slot => {
            slot.classList.remove('active');
        });
        stopCamera();
    }

    // --- Fungsi Hitung Mundur (Tidak Berubah) ---
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
                    countdownEl.style.display = 'none';
                    resolve();
                }
            }, 1000);
        });
    }

    // --- Aksi Tombol (Tidak Berubah, kecuali takePhoto) ---
    async function takePhoto() {
        captureBtn.disabled = true;
        await startCountdown(3);

        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        
        context.filter = selectedFilter; // Menerapkan filter
        context.drawImage(video, 0, 0, canvas.width, canvas.height);
        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        const previewSlot = document.getElementById(`slot-${currentSlot}`);
        previewSlot.innerHTML = `<img src="${dataUrl}" alt="Preview Foto ${currentSlot + 1}">`;
        capturedPhotos[currentSlot] = dataUrl;
        
        updateUIForConfirmation();
        captureBtn.disabled = false;
    }

    function keepPhoto() {
        currentSlot++;
        if (currentSlot < PHOTO_LIMIT) {
            updateUIForCapture();
        } else {
            updateUIForFinish();
        }
    }

    function retakePhoto() {
        if (RETAKES_LEFT > 0) {
            RETAKES_LEFT--;
            retakeCountEl.textContent = RETAKES_LEFT;
            const previewSlot = document.getElementById(`slot-${currentSlot}`);
            previewSlot.innerHTML = `Slot ${currentSlot + 1}`;
            capturedPhotos[currentSlot] = null;
            updateUIForCapture();
        }
    }

    async function processPhotoStrip() {
        infoText.textContent = "Memproses...";
        finishBtn.disabled = true;
        const finalPhotos = capturedPhotos.filter(p => p);

        try {
            const response = await fetch(`${URLROOT}/photo/ajax_process_photostrip`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: TRANSACTION_ID,
                    photos: finalPhotos,
                    frame_path: FRAME_PATH,
                    filter: selectedFilter
                })
            });

            const result = await response.json();
            if (!response.ok) {
                throw new Error(result.message || `HTTP error! Status: ${response.status}`);
            }
            
            document.body.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = result.final_url;
            }, 400);

        } catch (error) {
            console.error('Processing error:', error);
            infoText.textContent = `Error: ${error.message}`;
            finishBtn.disabled = false;
        }
    }
    
    // --- Event Listeners ---
    captureBtn.addEventListener('click', takePhoto);
    keepBtn.addEventListener('click', keepPhoto);
    retakePhotoBtn.addEventListener('click', retakePhoto);
    finishBtn.addEventListener('click', processPhotoStrip);

    // --- MODIFIED: Event Listener for Filter Dropdown ---
    if (filterSelect) {
        filterSelect.addEventListener('change', (event) => {
            selectedFilter = event.target.value;
            video.style.filter = selectedFilter;
        });
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    // --- Mulai ---
    initCamera();
});