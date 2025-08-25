document.addEventListener('DOMContentLoaded', () => {
    const canvas = new fabric.Canvas('editor-canvas');
    const container = document.querySelector('.editor-canvas-container');

    // Buat elemen gambar untuk memuat foto dan mendapatkan dimensinya
    const imgElement = new Image();
    imgElement.src = photoUrl;

    // Setelah gambar dimuat, atur kanvas
    imgElement.onload = () => {
        const maxWidth = container.offsetWidth * 0.95; // Gunakan 95% lebar kontainer
        const maxHeight = container.offsetHeight * 0.95; // Gunakan 95% tinggi kontainer
        const imgAspectRatio = imgElement.width / imgElement.height;
        
        let canvasWidth = maxWidth;
        let canvasHeight = maxWidth / imgAspectRatio;

        // Jika tinggi yang dihitung masih terlalu besar, hitung ulang berdasarkan tinggi
        if (canvasHeight > maxHeight) {
            canvasHeight = maxHeight;
            canvasWidth = maxHeight * imgAspectRatio;
        }

        // Atur dimensi kanvas
        canvas.setWidth(canvasWidth);
        canvas.setHeight(canvasHeight);

        // Atur gambar sebagai latar belakang yang diskalakan
        fabric.Image.fromURL(photoUrl, (img) => {
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height
            });
        });
    };

    // Fungsi untuk menambahkan stiker
    const stickerItems = document.querySelectorAll('.sticker-item');
    stickerItems.forEach(item => {
        item.addEventListener('click', () => {
            fabric.Image.fromURL(item.src, (stickerImg) => {
                stickerImg.scale(0.25); // Atur skala awal stiker agar tidak terlalu besar
                canvas.centerObject(stickerImg);
                canvas.add(stickerImg);
            });
        });
    });

    // Fungsi untuk menyimpan foto
    const saveBtn = document.getElementById('save-photo-btn');
    saveBtn.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Menyimpan...';

        const dataUrl = canvas.toDataURL('image/jpeg');
        const pathSegments = window.location.pathname.split('/');
        const photo_id = pathSegments[pathSegments.length - 1]; 

        try {
            const response = await fetch('/photobooth-app/photo/ajax_save_photo', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: dataUrl, photo_id: photo_id })
            });

            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Gagal menyimpan foto.');
            }

            const data = await response.json();
            if (data.success) {
                window.location.href = `/photobooth-app/photo/finalize/${photo_id}`;
            } else {
                throw new Error(data.message);
            }
        } catch (error) {
            console.error('Error saving photo:', error);
            alert('Gagal menyimpan foto: ' + error.message);
            saveBtn.disabled = false;
            saveBtn.textContent = 'Simpan & Selesai';
        }
    });
});