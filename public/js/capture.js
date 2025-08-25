document.addEventListener('DOMContentLoaded', () => {
    const captureBtn = document.getElementById('take-photo-btn');
    const countdownElement = document.getElementById('countdown');
    const frameSelect = document.getElementById('frame-select');
    
    const livePreviewContainer = document.getElementById('live-preview-container');
    const livePreview = document.getElementById('live-preview');
    const photoResult = document.getElementById('photo-result');
    const resultImage = document.getElementById('result-image');

    const captureControls = document.getElementById('capture-controls');
    const resultControls = document.getElementById('result-controls');

    captureBtn.addEventListener('click', startCaptureSequence);

    async function startCaptureSequence() {
        captureBtn.disabled = true;
        captureBtn.textContent = 'Mengambil gambar...';

        await runCountdown();
        
        const formData = new FormData();
        formData.append('frame', frameSelect.value);
        // In a more complex app, sticker data would be collected and sent here.
        // formData.append('stickers', JSON.stringify(stickerArray));

        try {
            const response = await fetch(`${URLROOT}/photo/take`, {
                method: 'POST',
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showResult(data.photo_url);
            } else {
                alert('Error: ' + data.message);
                resetUI();
            }
        } catch (error) {
            console.error('Capture error:', error);
            alert('Terjadi kesalahan saat mengambil foto.');
            resetUI();
        }
    }

    function runCountdown() {
        return new Promise(resolve => {
            let count = 3;
            countdownElement.classList.add('show');
            
            const interval = setInterval(() => {
                countdownElement.textContent = count;
                if (count === 0) {
                    clearInterval(interval);
                    countdownElement.textContent = '📸';
                    setTimeout(() => {
                        countdownElement.classList.remove('show');
                        countdownElement.textContent = '';
                        resolve();
                    }, 500);
                }
                count--;
            }, 1000);
        });
    }

    function showResult(imageUrl) {
        resultImage.src = imageUrl;
        livePreview.style.display = 'none';
        photoResult.style.display = 'block';
        captureControls.style.display = 'none';
        resultControls.style.display = 'block';
    }

    function resetUI() {
        captureBtn.disabled = false;
        captureBtn.textContent = 'Ambil Foto';
        livePreview.style.display = 'block';
        photoResult.style.display = 'none';
        captureControls.style.display = 'block';
        resultControls.style.display = 'none';
    }

    // Add a global URLROOT variable for JS
    const URLROOT = document.location.origin + '/photobooth-app';
});