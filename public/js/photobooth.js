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
    
    // --- MODIFIED: New Filter Elements ---
    const filterBtn = document.getElementById('filter-btn');
    const filterOptionsContainer = document.querySelector('.filter-options');
    const filterOptions = document.querySelectorAll('.filter-option');

    // Variabel state
    let currentSlot = 0;
    const capturedPhotos = [];
    let stream = null;
    let selectedFilter = 'none'; 

    // --- Inisialisasi Kamera ---
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

    // --- Manajemen UI ---
    function updateUIForCapture() {
        if (currentSlot === 0) {
            infoText.textContent = "Ayo, Senyum!"; // Encouraging message for the first photo
        } else {
            infoText.textContent = `Foto ke-${currentSlot + 1} dari ${PHOTO_LIMIT}`;
        }
        photoControls.style.display = 'none';
        captureBtn.style.display = 'flex';
        finishBtn.style.display = 'none';
        document.querySelectorAll('.preview-slot').forEach((slot, index) => {
            slot.classList.toggle('active', index === currentSlot);
        });
        // Ensure filter options are hidden when returning to capture state
        if (filterOptionsContainer) {
            filterOptionsContainer.style.display = 'none';
        }
        if (filterBtn) { // Ensure filter button is visible
            filterBtn.style.display = 'flex';
        }
    }

    function updateUIForConfirmation() {
        infoText.textContent = "Gimana Hasilnya?";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'flex';
        retakePhotoBtn.disabled = RETAKES_LEFT <= 0;
        // Hide filter container when confirmation appears
        if (filterOptionsContainer) {
            filterOptionsContainer.style.display = 'none';
        }
        if (filterBtn) { // Also hide the filter button
            filterBtn.style.display = 'none';
        }
    }
    
    function updateUIForFinish() {
        infoText.textContent = "Sesi Selesai!";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'none';
        finishBtn.style.display = 'flex';
        document.querySelectorAll('.preview-slot').forEach(slot => {
            slot.classList.remove('active');
        });
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
        
        // --- START: CROP LOGIC ---
        // Calculate the 'visible' video frame dimensions based on object-fit: contain
        const videoRatio = video.videoWidth / video.videoHeight;
        const stage = document.querySelector('.main-stage');
        const stageRatio = stage.clientWidth / stage.clientHeight;

        let sWidth = video.videoWidth;
        let sHeight = video.videoHeight;
        let sx = 0;
        let sy = 0;

        // If stage is wider than video (letterbox on sides)
        if (stageRatio > videoRatio) {
            sWidth = video.videoHeight * stageRatio;
            sx = (video.videoWidth - sWidth) / 2;
        } else { // If stage is taller than video (letterbox on top/bottom)
            sHeight = video.videoWidth / stageRatio;
            sy = (video.videoHeight - sHeight) / 2;
        }
        
        // The canvas should have the aspect ratio of the stage, not the raw video
        canvas.width = sWidth;
        canvas.height = sHeight;
        
        // Apply filter and draw the cropped image
        context.filter = video.style.filter; // Use the same filter as the live preview
        context.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, canvas.width, canvas.height);
        // --- END: CROP LOGIC ---

        const dataUrl = canvas.toDataURL('image/jpeg', 0.9);
        
        const previewSlot = document.getElementById(`slot-${currentSlot}`);
        // The preview image should fill its container, object-fit will handle the rest
        previewSlot.innerHTML = `<img src="${dataUrl}" alt="Preview Foto ${currentSlot + 1}" style="width: 100%; height: 100%; object-fit: cover;">`;
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

    async function proceedToEditor() {
        infoText.textContent = "Menyiapkan editor...";
        finishBtn.disabled = true;
        const finalPhotos = capturedPhotos.filter(p => p);

        try {
            const response = await fetch(`${URLROOT}/photo/ajax_save_captured_photos`, {
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
            if (!response.ok || !result.success) {
                throw new Error(result.message || `HTTP error! Status: ${response.status}`);
            }
            
            stopCamera();
            document.body.classList.add('fade-out');
            setTimeout(() => {
                window.location.href = result.editor_url;
            }, 400);

        } catch (error) {
            console.error('Error proceeding to editor:', error);
            infoText.textContent = `Error: ${error.message}`;
            finishBtn.disabled = false;
        }
    }

    // --- Event Listeners ---
    captureBtn.addEventListener('click', takePhoto);
    keepBtn.addEventListener('click', keepPhoto);
    retakePhotoBtn.addEventListener('click', retakePhoto);
    finishBtn.addEventListener('click', proceedToEditor);

    // --- MODIFIED: Event Listeners for Filter Selection ---
    if (filterBtn) {
        filterBtn.addEventListener('click', () => {
            filterOptionsContainer.style.display = filterOptionsContainer.style.display === 'block' ? 'none' : 'block';
        });

        filterOptions.forEach(option => {
            option.addEventListener('click', () => {
                selectedFilter = option.dataset.filter;
                video.style.filter = selectedFilter;
                filterOptionsContainer.style.display = 'none';

                // Update active class
                filterOptions.forEach(opt => opt.classList.remove('active'));
                option.classList.add('active');

                // Update filter button text
                filterBtn.textContent = `Filter: ${option.textContent}`;
            });
        });

        // Initialize filter button text
        const initialFilterOption = document.querySelector('.filter-option.active');
        if (initialFilterOption) {
            filterBtn.textContent = `Filter: ${initialFilterOption.textContent}`;
        }
    }

    function stopCamera() {
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
    }

    // --- Mulai ---
    initCamera();
});