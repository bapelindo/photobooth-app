document.addEventListener('DOMContentLoaded', () => {
    const canvas = new fabric.Canvas('editor-canvas');
    const container = document.querySelector('.main-stage');
    const saveBtn = document.getElementById('save-photo-btn');
    const trashCan = document.getElementById('trash-can');

    // --- Konfigurasi Kontrol Stiker ---
    fabric.Object.prototype.set({
        transparentCorners: false,
        cornerColor: 'rgba(102,153,255,0.8)',
        cornerStrokeColor: 'white',
        borderColor: 'rgba(102,153,255,0.8)',
        cornerSize: 12,
        padding: 10,
        cornerStyle: 'circle',
        borderDashArray: [3, 3],
        _isHovered: false 
    });

    // --- Pengaturan Ukuran Kanvas ---
    const DPI = 150;
    const STRIP_WIDTH_IN = 2;
    const STRIP_HEIGHT_IN = 6;
    const highResWidth = STRIP_WIDTH_IN * DPI;
    const highResHeight = STRIP_HEIGHT_IN * DPI;

    canvas.setWidth(highResWidth);
    canvas.setHeight(highResHeight);

    // --- Memuat Gambar dan Komposisi ---
    const loadImage = (url) => {
        return new Promise((resolve, reject) => {
            fabric.Image.fromURL(url, (img) => {
                if (img) resolve(img);
                else reject(new Error(`Gagal memuat gambar: ${url}`));
            }, { crossOrigin: 'anonymous' });
        });
    };

    /**
     * FUNGSI KUNCI: Sekarang hanya memuat satu gambar (photostrip)
     * sebagai background kanvas. Tidak ada lagi proses penggabungan di sini.
     */
    const initializeEditor = async () => {
        try {
            // Muat gambar photostrip yang sudah jadi dari server
            const photostripImg = await loadImage(photostripUrl);
            
            // Atur sebagai background kanvas, diskalakan agar pas
            canvas.setBackgroundImage(photostripImg, canvas.renderAll.bind(canvas), {
                scaleX: canvas.getWidth() / photostripImg.width,
                scaleY: canvas.getHeight() / photostripImg.height,
            });
            
            canvas.renderAll();

        } catch (error) {
            console.error("Gagal menginisialisasi editor:", error);
            container.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
        }
    };

    // --- Fungsionalitas Stiker ---
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

    // --- Fungsionalitas Drag-to-Delete ---
    function isOverTrash(obj) {
        if (!obj || !trashCan) return false;
        
        const trashRect = trashCan.getBoundingClientRect();
        const objBoundingRect = obj.getBoundingRect();
        const canvasRect = canvas.getElement().getBoundingClientRect();

        const objViewportLeft = canvasRect.left + objBoundingRect.left;
        const objViewportTop = canvasRect.top + objBoundingRect.top;
        const objViewportRight = objViewportLeft + objBoundingRect.width;
        const objViewportBottom = objViewportTop + objBoundingRect.height;

        return objViewportRight > trashRect.left &&
               objViewportLeft < trashRect.right &&
               objViewportBottom > trashRect.top &&
               objViewportTop < trashRect.bottom;
    }

    canvas.on('object:moving', (e) => {
        const obj = e.target;
        if (!obj || obj.selectable === false) return;
        
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

    canvas.on('mouse:over', (e) => {
        if (e.target && e.target.selectable !== false) {
            e.target._isHovered = true;
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
            fabric.animate({
                startValue: obj.get('scaleX'),
                endValue: 0,
                duration: 200,
                easing: fabric.util.ease.easeOutBounce,
                onChange: (value) => {
                    obj.scale(value);
                    canvas.renderAll();
                },
                onComplete: () => {
                    canvas.remove(obj);
                    canvas.discardActiveObject();
                    trashCan.classList.remove('visible', 'hover');
                    canvas.renderAll();
                }
            });
        } else {
             trashCan.classList.remove('visible', 'hover');
             canvas.renderAll();
        }
    });

    // --- Fungsionalitas Simpan ---
    saveBtn.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Memproses...';
        try {
            // Mengekspor seluruh kanvas (background photostrip + stiker) menjadi satu gambar final
            const dataUrl = canvas.toDataURL({
                format: 'png',
                multiplier: 2 // Menghasilkan PNG kualitas tinggi
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