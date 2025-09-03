document.addEventListener('DOMContentLoaded', () => {
    const rawPhotosGallery = document.getElementById('raw-photos-gallery');
    const nextStepBtn = document.getElementById('next-step-btn');
    const canvases = [];
    const frameState = [];
    let draggedItemData = null;
    let totalSlots = 0;
    
    FRAMES_DATA.forEach(frameData => {
        totalSlots += frameData.slot_coordinates.length;
    });

    function initializeCanvas(frameData, index) {
        const container = document.querySelectorAll('.photostrip-canvas-container')[index];
        const canvasEl = document.getElementById(`canvas-${index}`);
        
        const canvas = new fabric.Canvas(canvasEl, {
            width: container.clientWidth,
            height: container.clientHeight,
            selection: false
        });
        canvases.push(canvas);
        frameState[index] = { slots: {}, images: {} };

        fabric.Image.fromURL(URLROOT + frameData.path, (img, isError) => {
            if (isError) {
                console.error(`Gagal memuat frame: ${URLROOT + frameData.path}`);
                return;
            }
            canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
                scaleX: canvas.width / img.width,
                scaleY: canvas.height / img.height,
            });
        }, { crossOrigin: 'anonymous' });

        frameData.slot_coordinates.forEach((slotCoords, slotIndex) => {
            const slotRect = new fabric.Rect({
                left: canvas.width * (slotCoords.left / 100),
                top: canvas.height * (slotCoords.top / 100),
                width: canvas.width * (slotCoords.width / 100),
                height: canvas.height * (slotCoords.height / 100),
                fill: 'rgba(0,0,0,0.3)',
                stroke: '#fff',
                strokeDashArray: [5, 5],
                selectable: false,
                hoverCursor: 'pointer',
                isSlot: true,
                canvasIndex: index,
                slotIndex: slotIndex
            });
            canvas.add(slotRect);
            frameState[index].slots[slotIndex] = slotRect;
        });

        canvas.on('drop:before', (opt) => {
            const dropTarget = canvas.findTarget(opt.e, false);
            if (dropTarget && dropTarget.isSlot) {
                handleDrop(canvas, dropTarget);
            }
        });
    }

    // --- REVISI UTAMA DI FUNGSI INI ---
    function handleDrop(canvas, slot) {
        if (!draggedItemData) return;

        const { photoSrc, photoId: newPhotoId } = draggedItemData;
        const { canvasIndex, slotIndex } = slot;

        // Langkah 1: Hapus gambar lama dari slot tujuan (jika ada)
        const existingImageInTargetSlot = frameState[canvasIndex].images[slotIndex];
        if (existingImageInTargetSlot) {
            const oldPhotoId = existingImageInTargetSlot.photoId;
            document.querySelector(`.raw-photo-item[data-photo-id="${oldPhotoId}"]`)?.classList.remove('used');
            canvas.remove(existingImageInTargetSlot.fabricImage);
        }

        // Langkah 2: Cari & hapus gambar yang sama dari slot LAIN (jika pengguna memindahkan foto)
        frameState.forEach((state, cIdx) => {
            Object.entries(state.images).forEach(([sIdx, imgData]) => {
                if (imgData && imgData.photoId === newPhotoId) {
                    canvases[cIdx].remove(imgData.fabricImage);
                    delete frameState[cIdx].images[sIdx];
                }
            });
        });

        // Hapus status 'used' dari semua foto, lalu terapkan lagi berdasarkan state
        document.querySelectorAll('.raw-photo-item.used').forEach(el => el.classList.remove('used'));

        // Langkah 3: Tambahkan gambar baru
        fabric.Image.fromURL(photoSrc, (photoImg) => {
            const slotWidth = slot.width;
            const slotHeight = slot.height;
            const scale = Math.max(slotWidth / photoImg.width, slotHeight / photoImg.height);
            
            photoImg.set({
                originX: 'left', originY: 'top', left: slot.left, top: slot.top,
                scaleX: scale, scaleY: scale,
                clipPath: new fabric.Rect({
                    originX: 'left', originY: 'top', left: slot.left, top: slot.top,
                    width: slotWidth, height: slotHeight, absolutePositioned: true
                }),
                selectable: true, hasControls: false, hoverCursor: 'move',
            });

            photoImg.left -= (photoImg.getScaledWidth() - slotWidth) / 2;
            photoImg.top -= (photoImg.getScaledHeight() - slotHeight) / 2;

            canvas.add(photoImg);
            
            // Perbarui state setelah gambar baru ditambahkan
            frameState[canvasIndex].images[slotIndex] = { fabricImage: photoImg, photoId: newPhotoId };
            
            // Perbarui status 'used' di galeri berdasarkan state terbaru
            frameState.forEach(state => {
                Object.values(state.images).forEach(imgData => {
                    if (imgData) {
                        document.querySelector(`.raw-photo-item[data-photo-id="${imgData.photoId}"]`)?.classList.add('used');
                    }
                });
            });

            checkIfDone();

            photoImg.on('moving', function() {
                const rightBound = slot.left;
                const leftBound = slot.left - (this.getScaledWidth() - slotWidth);
                const bottomBound = slot.top;
                const topBound = slot.top - (this.getScaledHeight() - slotHeight);

                if (this.left > rightBound) this.left = rightBound;
                if (this.left < leftBound) this.left = leftBound;
                if (this.top > bottomBound) this.top = bottomBound;
                if (this.top < topBound) this.top = topBound;
            });

        }, { crossOrigin: 'anonymous' });
    }
    
    rawPhotosGallery.addEventListener('dragstart', (e) => {
        const target = e.target;
        if (target.classList.contains('raw-photo-item')) {
            draggedItemData = {
                photoSrc: target.src,
                photoId: target.dataset.photoId
            };
        } else {
            e.preventDefault();
            draggedItemData = null;
        }
    });

    function checkIfDone() {
        let filledSlots = 0;
        frameState.forEach(state => {
            filledSlots += Object.keys(state.images).length;
        });
        nextStepBtn.disabled = filledSlots !== totalSlots;
    }

    nextStepBtn.addEventListener('click', async () => {
        nextStepBtn.disabled = true;
        nextStepBtn.textContent = 'Menyimpan...';
        
        canvases.forEach((canvas, canvasIndex) => {
            Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: false }));
            canvas.renderAll();
        });

        const finalImages = canvases.map(canvas => {
            return canvas.toDataURL({ format: 'png', quality: 0.9, multiplier: 2 });
        });

        canvases.forEach((canvas, canvasIndex) => {
             Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: true }));
             canvas.renderAll();
        });

        try {
            const response = await fetch(`${URLROOT}/photo/ajax_process_layout`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    transaction_id: TRANSACTION_ID,
                    final_images: finalImages
                })
            });
            const result = await response.json();
            if (result.success) {
                window.location.href = result.sticker_editor_url;
            } else { throw new Error(result.message); }
        } catch (error) {
            alert('Error: ' + error.message);
            nextStepBtn.disabled = false;
            nextStepBtn.textContent = 'Lanjut Hias Stiker';
        }
    });

    FRAMES_DATA.forEach(initializeCanvas);
});