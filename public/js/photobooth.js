document.addEventListener('DOMContentLoaded', () => {
    const video = document.getElementById('live-preview');
    const takePhotoBtn = document.getElementById('take-photo-btn');
    const resultView = document.querySelector('.result-view');
    const resultPhoto = document.getElementById('result-photo');
    const retakeBtn = document.getElementById('retake-btn');
    const finalizeBtn = document.getElementById('finalize-btn');
    const initialControls = document.getElementById('initial-controls');
    const retakeControls = document.getElementById('retake-controls');

    // Get access to the camera
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia({ video: true })
            .then(stream => {
                video.srcObject = stream;
                video.play();
            })
            .catch(err => {
                console.error("Error accessing the camera: ", err);
            });
    }

    // Take photo
    takePhotoBtn.addEventListener('click', () => {
        const canvas = document.getElementById('capture-canvas');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/jpeg');
        resultPhoto.src = dataUrl;
        resultView.style.display = 'block';
        video.style.display = 'none';

        initialControls.style.display = 'none';
        retakeControls.style.display = 'block';

        // Send the photo to the server
        const transaction_id = window.location.pathname.split('/')[3];
        const frame_id = window.location.pathname.split('/')[4];

        fetch('/photobooth-app/photo/ajax_take_photo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                image: dataUrl,
                transaction_id: transaction_id,
                frame_id: frame_id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = `/photobooth-app/photo/editor/${data.photo_id}`;
            } else {
                console.error('Error saving photo:', data.message);
            }
        });
    });

    // Retake photo
    retakeBtn.addEventListener('click', () => {
        resultView.style.display = 'none';
        video.style.display = 'block';
        initialControls.style.display = 'block';
        retakeControls.style.display = 'none';
    });

    // Filter selection
    const filterItems = document.querySelectorAll('.filter-item');
    const livePreview = document.getElementById('live-preview');

    filterItems.forEach(item => {
        item.addEventListener('click', () => {
            // Remove active class from all filters
            filterItems.forEach(i => i.classList.remove('active'));

            // Add active class to clicked filter
            item.classList.add('active');

            // Get filter name
            const filter = item.dataset.filter;

            // Remove all filter classes from video
            livePreview.className = '';

            // Add new filter class
            if (filter !== 'none') {
                livePreview.classList.add(filter);
            }
        });
    });
});