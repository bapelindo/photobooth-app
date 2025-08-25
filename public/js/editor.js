document.addEventListener('DOMContentLoaded', () => {
    const canvas = new fabric.Canvas('editor-canvas');

    fabric.Image.fromURL(photoUrl, (img) => {
        canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
            scaleX: canvas.width / img.width,
            scaleY: canvas.height / img.height
        });
    });

    const stickerItems = document.querySelectorAll('.sticker-item');
    stickerItems.forEach(item => {
        item.addEventListener('click', () => {
            fabric.Image.fromURL(item.src, (stickerImg) => {
                stickerImg.scale(0.5);
                canvas.add(stickerImg);
            });
        });
    });

    const saveBtn = document.getElementById('save-photo-btn');
    saveBtn.addEventListener('click', () => {
        const dataUrl = canvas.toDataURL('image/jpeg');
        
        // Get photo_id from the URL
        const photo_id = window.location.pathname.split('/')[3];

        // Send the data URL to the server to be saved
        fetch('/photobooth-app/photo/ajax_save_photo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                image: dataUrl,
                photo_id: photo_id
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect to a thank you page or show a success message
                window.location.href = '/photobooth-app/home/thankyou';
            } else {
                console.error('Error saving photo:', data.message);
            }
        });
    });
});