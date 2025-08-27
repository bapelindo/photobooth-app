document.addEventListener('DOMContentLoaded', () => {
    const canvas = new fabric.Canvas('editor-canvas');
    const container = document.querySelector('.main-stage');
    const saveBtn = document.getElementById('save-photo-btn');
    const trashCan = document.getElementById('trash-can');

    // --- Konfigurasi Kontrol Stiker (Tetap sama) ---
    fabric.Object.prototype.set({
        transparentCorners: false,
        cornerColor: 'rgba(102,153,255,0.8)',
        cornerStrokeColor: 'white',
        borderColor: 'rgba(102,153,255,0.8)',
        cornerSize: 12,
        padding: 10,
        cornerStyle: 'circle',
        borderDashArray: [3, 3],
        // Menambahkan property kustom untuk menyimpan status hover asli
        _isHovered: false 
    });

    // --- Pengaturan Ukuran Kanvas (Tetap sama) ---
    const DPI = 150;
    const STRIP_WIDTH_IN = 2;
    const STRIP_HEIGHT_IN = 6;
    const highResWidth = STRIP_WIDTH_IN * DPI; // 300px
    const highResHeight = STRIP_HEIGHT_IN * DPI; // 900px

    canvas.setWidth(highResWidth);
    canvas.setHeight(highResHeight);

    // --- Memuat Gambar dan Komposisi (Tetap sama) ---
    const loadImage = (url) => {
        return new Promise((resolve, reject) => {
            fabric.Image.fromURL(url, (img) => {
                if (img) resolve(img);
                else reject(new Error(`Gagal memuat gambar: ${url}`));
            }, { crossOrigin: 'anonymous' });
        });
    };

    const initializeEditor = async () => {
        try {
            if (framePath) {
                const frameImg = await loadImage(framePath);
                canvas.setBackgroundImage(frameImg, canvas.renderAll.bind(canvas), {
                    scaleX: canvas.getWidth() / frameImg.width,
                    scaleY: canvas.getHeight() / frameImg.height,
                });
            }

            const photoPromises = capturedPhotos.map(photoUrl => loadImage(photoUrl));
            const photoImages = await Promise.all(photoPromises);

            const numPhotos = photoImages.length;
            const photoHeight = canvas.getHeight() / numPhotos;
            const photoWidth = photoHeight * (4 / 3);
            const xOffset = (canvas.getWidth() - photoWidth) / 2;

            photoImages.forEach((img, index) => {
                const yOffset = index * photoHeight;
                img.set({
                    left: xOffset,
                    top: yOffset,
                    scaleX: photoWidth / img.width,
                    scaleY: photoHeight / img.height,
                    selectable: false,
                    evented: false,
                });
                canvas.add(img);
            });
            
            canvas.renderAll();

        } catch (error) {
            console.error("Gagal menginisialisasi editor:", error);
            container.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
        }
    };

    // --- Fungsionalitas Stiker (Tetap sama) ---
    const stickerItems = document.querySelectorAll('.sticker-item');
    stickerItems.forEach(item => {
        item.addEventListener('click', () => {
            fabric.Image.fromURL(item.src, (stickerImg) => {
                stickerImg.scaleToWidth(canvas.getWidth() / 4); 
                canvas.centerObject(stickerImg);
                canvas.add(stickerImg);
                canvas.setActiveObject(stickerImg);
            }, { crossOrigin: 'anonymous' });
        });
    });

    // --- Fungsionalitas Drag-to-Delete yang Diperbaiki ---
    function isOverTrash(obj) {
        if (!obj || !trashCan) return false;
        
        // Dapatkan posisi dan ukuran trashCan dalam koordinat viewport
        const trashRect = trashCan.getBoundingClientRect();
        
        // Dapatkan posisi objek Fabric.js dalam koordinat kanvas
        const objBoundingRect = obj.getBoundingRect();
        const canvasRect = canvas.getElement().getBoundingClientRect(); // Posisi kanvas di viewport

        // Konversi koordinat objek dari kanvas ke viewport
        const objViewportLeft = canvasRect.left + objBoundingRect.left;
        const objViewportTop = canvasRect.top + objBoundingRect.top;
        const objViewportRight = objViewportLeft + objBoundingRect.width;
        const objViewportBottom = objViewportTop + objBoundingRect.height;

        // Cek overlap
        return objViewportRight > trashRect.left &&
               objViewportLeft < trashRect.right &&
               objViewportBottom > trashRect.top &&
               objViewportTop < trashRect.bottom;
    }

    canvas.on('object:moving', (e) => {
        const obj = e.target;
        if (!obj || obj.selectable === false) return; // Hanya stiker yang bisa dipindahkan
        
        trashCan.classList.add('visible');

        if (isOverTrash(obj)) {
            trashCan.classList.add('hover');
            obj.set('opacity', 0.5);
        } else {
            trashCan.classList.remove('hover');
            obj.set('opacity', 1);
        }
        canvas.renderAll();
    });

    // Deteksi hover pada stiker (di kanvas)
    canvas.on('mouse:over', (e) => {
        if (e.target && e.target.selectable !== false) { // Pastikan itu stiker, bukan foto background
            e.target._isHovered = true; // Tandai stiker sedang dihover
            e.target.set('shadow', new fabric.Shadow({
                color: 'rgba(102,153,255,0.5)',
                blur: 20,
                offsetX: 0,
                offsetY: 0
            }));
            canvas.renderAll();
        }
    });

    canvas.on('mouse:out', (e) => {
        if (e.target && e.target._isHovered) {
            e.target._isHovered = false;
            e.target.set('shadow', null);
            canvas.renderAll();
        }
    });

    // Menyembunyikan tempat sampah saat objek tidak lagi di-drag atau diubah
    canvas.on('mouse:up', (e) => {
        trashCan.classList.remove('visible', 'hover');
        if (e.target && e.target.selectable !== false) {
             e.target.set('opacity', 1);
        }
        canvas.renderAll();
    });
    
    canvas.on('object:modified', (e) => {
        const obj = e.target;
        if (obj && trashCan.classList.contains('hover')) {
            // **Animasi Bounce-Delete**
            fabric.animate({
                startValue: obj.get('scaleX'),
                endValue: 0,
                duration: 200, // Durasi animasi
                easing: fabric.util.ease.easeOutBounce, // Efek bounce
                onChange: (value) => {
                    obj.scale(value);
                    canvas.renderAll();
                },
                onComplete: () => {
                    canvas.remove(obj);
                    canvas.discardActiveObject(); // Hapus objek aktif jika ada
                    trashCan.classList.remove('visible', 'hover'); // Sembunyikan trash can
                    canvas.renderAll();
                }
            });
        } else {
             trashCan.classList.remove('visible', 'hover');
             canvas.renderAll();
        }
    });

    // --- Fungsionalitas Simpan (Tetap sama) ---
    saveBtn.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Memproses...';
        try {
            const dataUrl = canvas.toDataURL({
                format: 'jpeg',
                quality: 0.95,
                multiplier: 1
            });
            const response = await fetch(saveUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ image: dataUrl })
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                throw new Error(result.message || 'Gagal menyimpan photostrip.');
            }
            window.location.href = result.finalize_url;
        } catch (error) {
            console.error('Gagal menyimpan foto:', error);
            alert('Error: ' + error.message);
            saveBtn.disabled = false;
            saveBtn.textContent = '🎉 Simpan & Lanjutkan';
        }
    });

    initializeEditor();
});