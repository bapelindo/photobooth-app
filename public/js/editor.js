document.addEventListener('DOMContentLoaded', () => {
    const canvasContainers = document.querySelectorAll('.photostrip-canvas-container');
    const stickerItems = document.querySelectorAll('.sticker-item');
    const saveBtn = document.getElementById('save-photo-btn');
    const canvases = [];
    let activeCanvas = null; // Penentu kanvas yang aktif
    let activeCanvasContainer = null;

    // --- Konfigurasi Stiker ---
    fabric.Object.prototype.set({
        transparentCorners: false, cornerColor: 'rgba(102,153,255,0.8)', cornerStrokeColor: 'white',
        borderColor: 'rgba(102,153,255,0.8)', cornerSize: 12, padding: 10, cornerStyle: 'circle',
    });

    // --- Inisialisasi Setiap Kanvas ---
    PHOTOSTRIP_URLS.forEach((url, index) => {
        const container = canvasContainers[index];
        const canvasEl = document.getElementById(`canvas-${index}`);
        
        const canvas = new fabric.Canvas(canvasEl, {
            width: container.clientWidth,
            height: container.clientHeight
        });
        canvases.push(canvas);

        // Muat gambar photostrip sebagai latar belakang
        fabric.Image.fromURL(url, (img) => {
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height,
            });
        }, { crossOrigin: 'anonymous' });
        
        // REVISI UTAMA: Set kanvas aktif saat diklik
        container.addEventListener('click', () => {
            setActiveCanvas(canvas, container);
        });
    });

    // Otomatis aktifkan kanvas pertama
    if (canvases.length > 0) {
        setActiveCanvas(canvases[0], canvasContainers[0]);
    }

    function setActiveCanvas(canvas, container) {
        activeCanvas = canvas;
        activeCanvasContainer = container;
        
        // Update visual
        canvasContainers.forEach(c => c.classList.remove('active'));
        container.classList.add('active');
    }

    // --- Fungsionalitas Stiker ---
    stickerItems.forEach(item => {
        item.addEventListener('click', () => {
            if (!activeCanvas) {
                alert("Klik pada salah satu photostrip terlebih dahulu untuk menambahkan stiker.");
                return;
            }
            fabric.Image.fromURL(item.src, (stickerImg) => {
                stickerImg.scaleToWidth(activeCanvas.getWidth() / 3); // Ukuran stiker
                activeCanvas.centerObject(stickerImg);
                activeCanvas.add(stickerImg);
                activeCanvas.setActiveObject(stickerImg);
            }, { crossOrigin: 'anonymous' });
        });
    });

    // --- Tombol Simpan ---
    saveBtn.addEventListener('click', async () => {
        saveBtn.disabled = true;
        saveBtn.textContent = 'Memproses...';
        
        const finalImages = canvases.map(canvas => {
            return canvas.toDataURL({ format: 'png', multiplier: 2 });
        });

        try {
            const response = await fetch(SAVE_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    transaction_id: TRANSACTION_ID, // Tambahkan transaction_id
                    images: finalImages 
                })
            });
            const result = await response.json();
            if (!result.success) throw new Error(result.message);
            
            window.location.href = result.finalize_url;
        } catch (error) {
            alert('Error: ' + error.message);
            saveBtn.disabled = false;
            saveBtn.textContent = '🎉 Simpan & Selesai';
        }
    });
});