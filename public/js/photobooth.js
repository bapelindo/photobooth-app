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
    const overlayGuide = document.querySelector('.overlay-guide');
    const mainStage = document.querySelector('.main-stage');

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
                video: { width: { ideal: 1920 }, height: { ideal: 1080 } },
                audio: false
            });
            video.srcObject = stream;
            
            video.onloadedmetadata = () => {
                updateUIForCapture();
            };

        } catch (err) {
            console.error("Error accessing camera:", err);
            infoText.textContent = "Kamera Error!";
            captureBtn.style.display = 'none';
        }
    }

    // --- NEW: Update Overlay Guide ---
    function updateOverlayGuide(slotIndex) {
        if (!overlayGuide || typeof SLOTS_DATA === 'undefined' || !SLOTS_DATA[slotIndex] || typeof FRAME_DIMENSIONS === 'undefined') {
            if(overlayGuide) overlayGuide.style.display = 'none';
            return;
        }
        
        overlayGuide.style.display = 'block';

        const slot = SLOTS_DATA[slotIndex];
        const frameAspectRatio = FRAME_DIMENSIONS.width / FRAME_DIMENSIONS.height;
        const slotAspectRatio = frameAspectRatio * (slot.width / slot.height);

        const stageWidth = mainStage.clientWidth;
        const stageHeight = mainStage.clientHeight;
        const stageAspectRatio = stageWidth / stageHeight;

        let overlayWidth, overlayHeight;

        if (slotAspectRatio > stageAspectRatio) {
            overlayWidth = stageWidth;
            overlayHeight = overlayWidth / slotAspectRatio;
        } else {
            overlayHeight = stageHeight;
            overlayWidth = overlayHeight * slotAspectRatio;
        }

        overlayGuide.style.width = `${overlayWidth}px`;
        overlayGuide.style.height = `${overlayHeight}px`;
        overlayGuide.style.top = `${(stageHeight - overlayHeight) / 2}px`;
        overlayGuide.style.left = `${(stageWidth - overlayWidth) / 2}px`;
    }


    // --- Manajemen UI ---
    function updateUIForCapture() {
        if (currentSlot === 0) {
            infoText.textContent = "Ayo, Senyum!";
        } else {
            infoText.textContent = `Foto ke-${currentSlot + 1} dari ${PHOTO_LIMIT}`;
        }
        photoControls.style.display = 'none';
        captureBtn.style.display = 'flex';
        finishBtn.style.display = 'none';
        document.querySelectorAll('.preview-slot').forEach((slot, index) => {
            slot.classList.toggle('active', index === currentSlot);
        });
        
        updateOverlayGuide(currentSlot);

        if (filterOptionsContainer) {
            filterOptionsContainer.style.display = 'none';
        }
        if (filterBtn) {
            filterBtn.style.display = 'flex';
        }
    }

    function updateUIForConfirmation() {
        infoText.textContent = "Gimana Hasilnya?";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'flex';
        retakePhotoBtn.disabled = RETAKES_LEFT <= 0;
        if(overlayGuide) overlayGuide.style.display = 'none';

        // Hide filter button on confirmation
        if (filterBtn) {
            filterBtn.style.display = 'none';
        }
        if (filterOptionsContainer) {
            filterOptionsContainer.style.display = 'none';
        }
    }
    
    function updateUIForFinish() {
        infoText.textContent = "Sesi Selesai!";
        captureBtn.style.display = 'none';
        photoControls.style.display = 'none';
        finishBtn.style.display = 'flex';
        if(overlayGuide) overlayGuide.style.display = 'none';
        document.querySelectorAll('.preview-slot').forEach(slot => {
            slot.classList.remove('active');
        });
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
                    countdownEl.style.display = 'none';
                    resolve();
                }
            }, 1000);
        });
    }

    async function takePhoto() {
        captureBtn.disabled = true;
        if(overlayGuide) overlayGuide.style.display = 'none';
        await startCountdown(3);

        const context = canvas.getContext('2d');
        
        const videoRatio = video.videoWidth / video.videoHeight;
        const slot = SLOTS_DATA[currentSlot];
        if (!slot) {
            console.error("Current slot data not found!");
            captureBtn.disabled = false;
            return;
        }
        
        const frameAspectRatio = FRAME_DIMENSIONS.width / FRAME_DIMENSIONS.height;
        const slotAspectRatio = frameAspectRatio * (slot.width / slot.height);

        let sWidth, sHeight, sx, sy;

        if (slotAspectRatio > videoRatio) {
            sHeight = video.videoWidth / slotAspectRatio;
            sWidth = video.videoWidth;
            sx = 0;
            sy = (video.videoHeight - sHeight) / 2;
        } else {
            sWidth = video.videoHeight * slotAspectRatio;
            sHeight = video.videoHeight;
            sx = (video.videoWidth - sWidth) / 2;
            sy = 0;
        }

        canvas.width = sWidth;
        canvas.height = sHeight;
        
        context.filter = video.style.filter;
        context.drawImage(video, sx, sy, sWidth, sHeight, 0, 0, sWidth, sHeight);

        const dataUrl = canvas.toDataURL('image/png');
        
        const previewSlot = document.getElementById(`slot-${currentSlot}`);
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
    window.addEventListener('resize', () => updateOverlayGuide(currentSlot)); // Recalculate on resize

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