<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Layout - Susun Photostrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.0/fabric.min.js"></script>
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        html, body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
        }

        .layout-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            grid-template-rows: auto 1fr auto;
            gap: 15px;
            min-height: 100vh;
            height: calc(100vh - 30px);
            padding: 15px;
            box-sizing: border-box;
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .header-panel h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin: 0 0 10px 0;
            font-size: 2rem;
        }

        .header-panel p {
            margin: 0;
            color: #666;
        }

        .photos-panel {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
        }

        .photos-panel h3 {
            margin: 0 0 15px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--secondary-color);
            font-size: 1.2rem;
        }

        .photo-source {
            flex-grow: 1;
            overflow-y: auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px;
            padding: 10px;
            max-height: calc(100vh - 250px);
        }
        
        .photo-source::-webkit-scrollbar {
            width: 8px;
        }
        
        .photo-source::-webkit-scrollbar-track {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 10px;
        }
        
        .photo-source::-webkit-scrollbar-thumb {
            background: rgba(108, 99, 255, 0.4);
            border-radius: 10px;
        }
        
        .photo-source::-webkit-scrollbar-thumb:hover {
            background: rgba(108, 99, 255, 0.6);
        }

        .draggable-photo {
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: grab;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .draggable-photo:hover {
            border-color: var(--primary-color);
            transform: scale(1.08);
            box-shadow: 0 8px 30px rgba(108, 99, 255, 0.4);
            z-index: 10;
        }

        .draggable-photo.dragging {
            cursor: grabbing;
            opacity: 0.5;
            transform: scale(0.9);
            z-index: 1000;
            position: relative;
            pointer-events: none;
            filter: blur(1px);
        }

        .draggable-photo.used {
            opacity: 0.6;
            border: 2px solid var(--success-color);
            position: relative;
        }

        .draggable-photo.used::after {
            content: '✓';
            position: absolute;
            top: 5px;
            right: 5px;
            background: var(--success-color);
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .draggable-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .slot-photo {
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: move;
            transition: transform 0.1s ease;
            z-index: 2;
        }

        .slot-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            pointer-events: none;
        }

        .slot-photo.panning {
            cursor: grabbing;
        }

        .photo-controls {
            position: absolute;
            top: 2px;
            right: 2px;
            display: flex;
            gap: 2px;
            opacity: 0;
            transition: opacity 0.2s ease;
            z-index: 3;
        }

        .photo-slot:hover .photo-controls {
            opacity: 1;
        }

        .control-btn {
            width: 18px;
            height: 18px;
            border: none;
            border-radius: 50%;
            background: rgba(0,0,0,0.8);
            color: white;
            cursor: pointer;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .workspace {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .frame-tabs {
            display: flex;
            gap: 12px;
            margin-bottom: 25px;
            flex-wrap: wrap;
            justify-content: center;
            padding: 0 20px;
        }

        .frame-tab {
            background: rgba(108, 99, 255, 0.1);
            border: 2px solid var(--primary-color);
            padding: 10px 20px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 120px;
            justify-content: center;
            white-space: nowrap;
        }

        .frame-tab:hover {
            background: rgba(108, 99, 255, 0.3);
        }

        .frame-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .frame-thumb {
            width: 24px;
            height: 36px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
        }

        .photostrip-canvas {
            background: white;
            border: 3px dashed #ddd;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            height: 600px;
            overflow: visible;
        }
        
        /* Ensure editor area can scroll if needed */
        #photostrip-container {
            height: calc(100vh - 400px); /* Give it a more defined height */
            overflow-y: auto;
            padding: 20px;
            display: flex; /* Use flexbox for centering */
            align-items: center; /* Center vertically */
            justify-content: center; /* Center horizontally */
        }

        .photostrip-frame {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
            pointer-events: none;
            object-fit: fill;
            opacity: 0.85;
        }

        .photo-slot {
            position: absolute;
            border: 3px dashed var(--primary-color);
            border-radius: 12px;
            background: rgba(108, 99, 255, 0.25);
            backdrop-filter: blur(2px);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary-color);
            font-weight: 700;
            font-size: 1rem;
            text-align: center;
            transition: all 0.3s ease;
            z-index: 15;
            overflow: hidden;
            box-shadow: inset 0 0 10px rgba(108, 99, 255, 0.3), 0 2px 8px rgba(0,0,0,0.2);
            cursor: pointer;
        }

        .photo-slot.drag-over {
            background: rgba(255, 101, 132, 0.2);
            border-color: var(--secondary-color);
            border-style: solid;
            transform: scale(1.05);
            box-shadow: 0 0 20px rgba(255, 101, 132, 0.4);
        }

        .photo-slot.filled {
            border: none;
            background: none;
        }

        .slot-photo {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 6px;
            cursor: move;
            transition: transform 0.1s ease;
            z-index: 4;
            transform-origin: center center;
        }
        
        .slot-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            user-select: none;
            pointer-events: none;
        }
        
        .slot-photo.panning {
            cursor: grabbing;
        }

        .slot-photo:hover {
            opacity: 0.8;
        }

        .controls-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            font-family: 'Fredoka One', cursive;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-preview {
            background: var(--primary-color);
            color: white;
        }

        .btn-continue {
            background: linear-gradient(135deg, var(--success-color), var(--primary-color));
            color: white;
            font-size: 1.1rem;
            padding: 15px 35px;
        }

        .btn-clear {
            background: var(--warning-color);
            color: white;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .progress-indicator {
            display: flex;
            align-items: center;
            gap: 15px;
            font-weight: 600;
            color: var(--primary-color);
        }

        .progress-bar {
            width: 200px;
            height: 8px;
            background: rgba(108, 99, 255, 0.2);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            border-radius: 4px;
            width: 0%;
            transition: width 0.5s ease;
        }

        .empty-state {
            text-align: center;
            color: #999;
            padding: 40px;
        }

        .empty-state h3 {
            font-family: 'Fredoka One', cursive;
            margin: 0 0 10px 0;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(76, 175, 80, 0.95);
            color: white;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 0.9rem;
            z-index: 10000;
            transform: translateX(400px);
            transition: transform 0.3s ease;
            backdrop-filter: blur(10px);
        }
        
        .notification.show {
            transform: translateX(0);
        }
        
        .notification.error {
            background: rgba(244, 67, 54, 0.95);
        }

        @media (max-width: 768px) {
            .layout-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
            }

            .photos-panel {
                order: 2;
            }

            .workspace {
                order: 3;
            }

            .controls-panel {
                order: 4;
            }
            
            .notification {
                right: 10px;
                left: 10px;
                transform: translateY(-100px);
            }
            
            .notification.show {
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <div class="header-panel">
            <h1>✨ Editor Layout Photostrip</h1>
            <p>Seret dan letakkan foto ke dalam slot yang tersedia untuk membuat photostrip yang menakjubkan!</p>
        </div>

        <div class="photos-panel">
            <h3>📸 Galeri Foto Sesi (<?= count($data['photos']) ?>)</h3>
            <div class="photo-source" id="photo-source">
                <?php foreach ($data['photos'] as $photo): ?>
                    <div class="draggable-photo" draggable="true" data-photo-id="<?= $photo->id ?>" data-photo-path="<?= $photo->file_path ?>">
                        <img src="<?= URLROOT . $photo->file_path ?>" alt="Foto Sesi">
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="workspace">
            <div class="frame-tabs" id="frame-tabs">
                <?php foreach ($data['frames'] as $index => $frame): ?>
                    <div class="frame-tab <?= $index === 0 ? 'active' : '' ?>" data-frame-id="<?= $frame->id ?>">
                        <img src="<?= URLROOT . $frame->path ?>" alt="<?= $frame->name ?>" class="frame-thumb">
                        <span><?= $frame->name ?></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="photostrip-container">
                <?php if (empty($data['frames'])): ?>
                    <div class="empty-state">
                        <h3>Tidak Ada Frame</h3>
                        <p>Tidak ada frame yang dipilih untuk sesi ini.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($data['frames'] as $index => $frame): ?>
                        <div class="photostrip-canvas-container" id="container-<?= $frame->id ?>" 
                             style="display: <?= $index === 0 ? 'block' : 'none' ?>; position: relative; width: 300px; height: 600px;">
                            <canvas id="canvas-<?= $frame->id ?>" width="300" height="600" 
                                    style="border: 3px dashed #ddd; border-radius: 15px; background: white;"></canvas>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="controls-panel">
            <div class="progress-indicator">
                <span>Progress:</span>
                <div class="progress-bar">
                    <div class="progress-fill" id="progress-fill"></div>
                </div>
                <span id="progress-text">0%</span>
            </div>
            
            <button class="btn btn-clear" onclick="clearAllSlots()">🗑️ Bersihkan</button>
            <button class="btn btn-preview" onclick="previewPhotostrips()">👁️ Preview</button>
            <button class="btn btn-continue" id="continue-btn" onclick="saveLayouts()" disabled>
                🎨 Lanjut ke Dekorasi
            </button>
        </div>
    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const FRAMES_DATA = <?= json_encode($data['frames']) ?> || [];
        const URLROOT = '<?= URLROOT ?>';
        let currentFrameIndex = 0;
        let draggedItemData = null;
        let totalSlots = 0;
        
        const canvases = [];
        const frameState = [];

        // Calculate total slots
        FRAMES_DATA.forEach(frameData => {
            const slotCoords = frameData.slot_coordinates ? JSON.parse(frameData.slot_coordinates) : [];
            totalSlots += slotCoords.length || 4; // Default 4 slots if no coordinates
        });

        document.addEventListener('DOMContentLoaded', () => {
            console.log('DOM loaded, initializing fabric.js layout editor...');
            console.log('Available frames:', FRAMES_DATA);
            console.log('Total slots:', totalSlots);
            
            if (!FRAMES_DATA || FRAMES_DATA.length === 0) {
                alert('Error: No frames available for layout editing!');
                return;
            }
            
            try {
                FRAMES_DATA.forEach(initializeCanvas);
                initializeFrameTabs();
                initializeDragAndDrop();
                checkIfDone();
                
                console.log('Fabric.js layout editor initialized successfully');
            } catch (error) {
                console.error('Error initializing layout editor:', error);
                alert('Error initializing layout editor: ' + error.message);
            }
        });

        function initializeCanvas(frameData, index) {
            const container = document.getElementById(`container-${frameData.id}`);
            const canvasEl = document.getElementById(`canvas-${frameData.id}`);
            
            if (!container || !canvasEl) {
                console.error(`Canvas elements not found for frame ${frameData.id}`);
                return;
            }
            
            const canvas = new fabric.Canvas(canvasEl, {
                width: 300,
                height: 600,
                selection: false
            });
            
            canvases[index] = canvas;
            frameState[index] = { slots: {}, images: {} };

            // Load frame background
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

            // Parse slot coordinates
            let slotCoords = [];
            try {
                slotCoords = frameData.slot_coordinates ? JSON.parse(frameData.slot_coordinates) : [];
            } catch (e) {
                console.warn('Invalid slot coordinates for frame', frameData.id, e);
                slotCoords = [];
            }

            // Default slots if none provided
            if (!slotCoords || slotCoords.length === 0) {
                slotCoords = [
                    { left: 8.33, top: 6.67, width: 83.33, height: 20 },
                    { left: 8.33, top: 30, width: 83.33, height: 20 },
                    { left: 8.33, top: 53.33, width: 83.33, height: 20 },
                    { left: 8.33, top: 76.67, width: 83.33, height: 20 }
                ];
            }

            // Create slot rectangles
            slotCoords.forEach((slotCoordData, slotIndex) => {
                const slotRect = new fabric.Rect({
                    left: canvas.width * (slotCoordData.left / 100),
                    top: canvas.height * (slotCoordData.top / 100),
                    width: canvas.width * (slotCoordData.width / 100),
                    height: canvas.height * (slotCoordData.height / 100),
                    fill: 'rgba(108, 99, 255, 0.25)',
                    stroke: '#6C63FF',
                    strokeDashArray: [5, 5],
                    strokeWidth: 3,
                    selectable: false,
                    hoverCursor: 'pointer',
                    isSlot: true,
                    canvasIndex: index,
                    slotIndex: slotIndex
                });
                canvas.add(slotRect);
                frameState[index].slots[slotIndex] = slotRect;
            });

            // Handle drop events
            canvas.on('drop:before', (opt) => {
                const dropTarget = canvas.findTarget(opt.e, false);
                if (dropTarget && dropTarget.isSlot) {
                    handleDrop(canvas, dropTarget);
                }
            });

            console.log(`Canvas ${index} initialized for frame ${frameData.id}`);
        }

        function initializeDragAndDrop() {
            console.log('Initializing drag and drop...');
            
            const photoSource = document.getElementById('photo-source');
            if (!photoSource) {
                console.error('Photo source element not found');
                return;
            }

            photoSource.addEventListener('dragstart', (e) => {
                const target = e.target.closest('.draggable-photo');
                if (target && target.classList.contains('draggable-photo')) {
                    draggedItemData = {
                        photoSrc: URLROOT + target.dataset.photoPath,
                        photoId: target.dataset.photoId
                    };
                    console.log('Drag started:', draggedItemData);
                    target.classList.add('dragging');
                } else {
                    e.preventDefault();
                    draggedItemData = null;
                }
            });

            photoSource.addEventListener('dragend', (e) => {
                const target = e.target.closest('.draggable-photo');
                if (target) {
                    target.classList.remove('dragging');
                }
            });

            console.log('Drag and drop initialized');
        }
        
        // Handle drop with fabric.js (based on the provided example)
        function handleDrop(canvas, slot) {
            if (!draggedItemData) return;

            const { photoSrc, photoId: newPhotoId } = draggedItemData;
            const { canvasIndex, slotIndex } = slot;

            console.log('Drop detected:', { photoSrc, newPhotoId, canvasIndex, slotIndex });

            // Step 1: Remove old image from target slot (if any)
            const existingImageInTargetSlot = frameState[canvasIndex].images[slotIndex];
            if (existingImageInTargetSlot) {
                const oldPhotoId = existingImageInTargetSlot.photoId;
                document.querySelector(`.draggable-photo[data-photo-id="${oldPhotoId}"]`)?.classList.remove('used');
                canvas.remove(existingImageInTargetSlot.fabricImage);
            }

            // Step 2: Find & remove same image from OTHER slots (if user moves photo)
            frameState.forEach((state, cIdx) => {
                Object.entries(state.images).forEach(([sIdx, imgData]) => {
                    if (imgData && imgData.photoId === newPhotoId) {
                        canvases[cIdx].remove(imgData.fabricImage);
                        delete frameState[cIdx].images[sIdx];
                    }
                });
            });

            // Remove 'used' status from all photos, then reapply based on state
            document.querySelectorAll('.draggable-photo.used').forEach(el => el.classList.remove('used'));

            // Step 3: Add new image
            fabric.Image.fromURL(photoSrc, (photoImg) => {
                const slotWidth = slot.width;
                const slotHeight = slot.height;
                const scale = Math.max(slotWidth / photoImg.width, slotHeight / photoImg.height);
                
                photoImg.set({
                    originX: 'left', originY: 'top', 
                    left: slot.left, top: slot.top,
                    scaleX: scale, scaleY: scale,
                    clipPath: new fabric.Rect({
                        originX: 'left', originY: 'top', 
                        left: slot.left, top: slot.top,
                        width: slotWidth, height: slotHeight, 
                        absolutePositioned: true
                    }),
                    selectable: true, hasControls: false, 
                    hoverCursor: 'move',
                });

                // Center the image in the slot
                photoImg.left -= (photoImg.getScaledWidth() - slotWidth) / 2;
                photoImg.top -= (photoImg.getScaledHeight() - slotHeight) / 2;

                canvas.add(photoImg);
                
                // Update state after new image is added
                frameState[canvasIndex].images[slotIndex] = { fabricImage: photoImg, photoId: newPhotoId };
                
                // Update 'used' status in gallery based on latest state
                frameState.forEach(state => {
                    Object.values(state.images).forEach(imgData => {
                        if (imgData) {
                            document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`)?.classList.add('used');
                        }
                    });
                });

                checkIfDone();

                // Add panning constraints
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

                console.log('Image added successfully to slot', slotIndex);
                showNotification('✅ Foto berhasil ditempatkan!', 'success');

            }, { crossOrigin: 'anonymous' });
        }

        function initializeFrameTabs() {
            console.log('Initializing frame tabs...');
            const frameTabs = document.querySelectorAll('.frame-tab');
            console.log('Found frame tabs:', frameTabs.length);
            
            if (frameTabs.length === 0) {
                console.error('No frame tabs found!');
                return;
            }
            
            frameTabs.forEach((tab, index) => {
                console.log(`Setting up tab ${index}:`, tab.dataset.frameId);
                
                tab.addEventListener('click', () => {
                    console.log('Frame tab clicked:', tab.dataset.frameId, 'index:', index);
                    
                    try {
                        // Remove active class from all tabs
                        frameTabs.forEach(t => t.classList.remove('active'));
                        // Add active class to clicked tab
                        tab.classList.add('active');
                        
                        // Hide all canvas containers
                        const containers = document.querySelectorAll('.photostrip-canvas-container');
                        containers.forEach(container => container.style.display = 'none');
                        
                        // Show selected container
                        const selectedContainer = document.getElementById(`container-${tab.dataset.frameId}`);
                        if (selectedContainer) {
                            selectedContainer.style.display = 'block';
                            currentFrameIndex = index;
                            console.log('Frame switched to index:', currentFrameIndex);
                        } else {
                            console.error('Container not found for frame:', tab.dataset.frameId);
                        }
                        
                        checkIfDone();
                    } catch (error) {
                        console.error('Error switching frame:', error);
                    }
                });
            });
            
            console.log('Frame tabs initialized');
        }

        function checkIfDone() {
            let filledSlots = 0;
            frameState.forEach(state => {
                filledSlots += Object.keys(state.images).length;
            });
            
            const continueBtn = document.getElementById('continue-btn');
            const progressFill = document.getElementById('progress-fill');
            const progressText = document.getElementById('progress-text');
            
            const progress = totalSlots > 0 ? Math.round((filledSlots / totalSlots) * 100) : 0;
            
            if (progressFill) progressFill.style.width = `${progress}%`;
            if (progressText) progressText.textContent = `${progress}%`;
            if (continueBtn) continueBtn.disabled = filledSlots !== totalSlots;
            
            console.log(`Progress: ${filledSlots}/${totalSlots} slots filled (${progress}%)`);
        }

        function clearAllSlots() {
            if (!confirm('Hapus semua foto dari layout saat ini?')) return;
            
            const currentCanvas = canvases[currentFrameIndex];
            const currentState = frameState[currentFrameIndex];
            
            if (!currentCanvas || !currentState) return;
            
            // Remove all images from current frame
            Object.values(currentState.images).forEach(imgData => {
                if (imgData) {
                    currentCanvas.remove(imgData.fabricImage);
                    document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`)?.classList.remove('used');
                }
            });
            
            // Clear state
            currentState.images = {};
            checkIfDone();
            
            showNotification('🗑️ Layout dibersihkan!', 'success');
        }

        // Legacy functions removed - using fabric.js implementation above

        // All legacy functions removed - using fabric.js implementation above

        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = `notification ${type === 'error' ? 'error' : ''}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
        
        function initializeFrameTabs() {
            console.log('Initializing frame tabs...');
            const frameTabs = document.querySelectorAll('.frame-tab');
            console.log('Found frame tabs:', frameTabs.length);
            
            if (frameTabs.length === 0) {
                console.error('No frame tabs found!');
                return;
            }
            
            frameTabs.forEach((tab, index) => {
                console.log(`Setting up tab ${index}:`, tab.dataset.frameId);
                
                tab.addEventListener('click', () => {
                    console.log('Frame tab clicked:', tab.dataset.frameId);
                    
                    try {
                        // Remove active class from all tabs
                        frameTabs.forEach(t => t.classList.remove('active'));
                        // Add active class to clicked tab
                        tab.classList.add('active');
                        
                        // Hide all canvases
                        const canvases = document.querySelectorAll('.photostrip-canvas');
                        console.log('Found canvases:', canvases.length);
                        canvases.forEach(canvas => canvas.style.display = 'none');
                        
                        // Show selected canvas
                        currentFrameId = tab.dataset.frameId;
                        const selectedCanvas = document.getElementById(`canvas-${currentFrameId}`);
                        console.log('Selected canvas:', selectedCanvas);
                        
                        if (selectedCanvas) {
                            selectedCanvas.style.display = 'block';
                            // Update slot event listeners for the new frame
                            updateSlotEventListeners();
                            console.log('Frame switched to:', currentFrameId);
                        } else {
                            console.error('Canvas not found for frame:', currentFrameId);
                        }
                        
                        updateProgress();
                    } catch (error) {
                        console.error('Error switching frame:', error);
                    }
                });
            });
            
            console.log('Frame tabs initialized');
        }

        function updateProgress() {
            let totalSlots = 0;
            let filledSlots = 0;
            
            frames.forEach(frame => {
                const canvas = document.getElementById(`canvas-${frame.id}`);
                const slots = canvas.querySelectorAll('.photo-slot');
                totalSlots += slots.length;
                
                const frameFilledSlots = Object.keys(layouts[frame.id]).length;
                filledSlots += frameFilledSlots;
            });
            
            const progress = totalSlots > 0 ? Math.round((filledSlots / totalSlots) * 100) : 0;
            
            document.getElementById('progress-fill').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = `${progress}%`;
            
            // Enable continue button if at least one frame is completely filled
            const continueBtn = document.getElementById('continue-btn');
            let hasCompleteFrame = false;
            
            frames.forEach(frame => {
                const canvas = document.getElementById(`canvas-${frame.id}`);
                const slots = canvas.querySelectorAll('.photo-slot');
                const frameFilledSlots = Object.keys(layouts[frame.id]).length;
                
                if (frameFilledSlots === slots.length && slots.length > 0) {
                    hasCompleteFrame = true;
                }
            });
            
            continueBtn.disabled = !hasCompleteFrame;
        }

        function clearAllSlots() {
            if (confirm('Hapus semua foto dari layout saat ini?')) {
                const currentCanvas = document.getElementById(`canvas-${currentFrameId}`);
                const slots = currentCanvas.querySelectorAll('.photo-slot');
                
                slots.forEach(slot => {
                    clearSlot(slot);
                });
                
                layouts[currentFrameId] = {};
                updateProgress();
            }
        }

        function previewPhotostrips() {
            // Validate layouts first
            let hasAnyPhotos = false;
            for (const frameId in layouts) {
                if (Object.keys(layouts[frameId]).length > 0) {
                    hasAnyPhotos = true;
                    break;
                }
            }
            
            if (!hasAnyPhotos) {
                showNotification('⚠️ Tidak ada foto untuk di-preview!', 'error');
                return;
            }
            
            // Create preview modal or new window
            const previewWindow = window.open('', '_blank', 'width=1000,height=700,scrollbars=yes');
            
            let previewHTML = `
                <html>
                <head>
                    <title>Preview Photostrips</title>
                    <style>
                        body { font-family: 'Poppins', sans-serif; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); margin: 0; }
                        h1 { text-align: center; color: #6C63FF; font-family: 'Fredoka One', cursive; margin-bottom: 30px; }
                        .preview-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; justify-items: center; }
                        .photostrip-preview { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); max-width: 350px; }
                        .photostrip-preview h3 { margin: 0 0 15px 0; color: #FF6584; text-align: center; font-weight: bold; }
                        .photostrip-container { position: relative; width: 300px; height: 600px; margin: 0 auto; border-radius: 15px; overflow: hidden; }
                        .print-btn { background: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 20px; cursor: pointer; margin-top: 15px; width: 100%; font-weight: bold; }
                        .print-btn:hover { background: #45a049; transform: translateY(-1px); }
                        @media print { body { background: white !important; } .print-btn { display: none; } }
                    </style>
                    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
                </head>
                <body>
                    <h1>✨ Preview Photostrips</h1>
                    <div class="preview-container">
            `;
            
            frames.forEach(frame => {
                const frameLayout = layouts[frame.id];
                const hasPhotos = Object.keys(frameLayout).length > 0;
                
                if (hasPhotos) {
                    previewHTML += `
                        <div class="photostrip-preview">
                            <h3>🇫 ${frame.name}</h3>
                            <div class="photostrip-container">
                                <img src="<?= URLROOT ?>${frame.path}" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: 2;">
                    `;
                    
                    Object.entries(frameLayout).forEach(([slotIndex, photo]) => {
                        try {
                            const slotCoords = JSON.parse(frame.slot_coordinates || '[]');
                            let slot;
                            
                            if (slotCoords.length > slotIndex) {
                                // Convert percentage to pixels for 300x600 container
                                const coord = slotCoords[slotIndex];
                                const left = coord.left !== undefined ? coord.left : coord.x || 0;
                                const top = coord.top !== undefined ? coord.top : coord.y || 0;
                                const width = coord.width || 83.33;
                                const height = coord.height || 20;
                                
                                slot = {
                                    x: (left / 100) * 300,
                                    y: (top / 100) * 600,
                                    width: (width / 100) * 300,
                                    height: (height / 100) * 600
                                };
                            } else {
                                // Default slot positions as fallback
                                const defaultSlots = [
                                    { x: 25, y: 40, width: 250, height: 120 },
                                    { x: 25, y: 180, width: 250, height: 120 },
                                    { x: 25, y: 320, width: 250, height: 120 },
                                    { x: 25, y: 460, width: 250, height: 120 }
                                ];
                                slot = defaultSlots[slotIndex] || defaultSlots[0];
                            }
                            
                            previewHTML += `
                                <img src="<?= URLROOT ?>${photo.photoPath}" 
                                     style="position: absolute; 
                                            left: ${slot.x}px; 
                                            top: ${slot.y}px; 
                                            width: ${slot.width}px; 
                                            height: ${slot.height}px; 
                                            object-fit: cover; 
                                            border-radius: 6px; 
                                            z-index: 1;"
                                     onerror="this.style.background='#f0f0f0'; this.alt='Failed to load'">
                            `;
                        } catch (e) {
                            console.error('Error processing slot coordinates:', e);
                        }
                    });
                    
                    previewHTML += `
                            </div>
                            <button class="print-btn" onclick="window.print()">🖶️ Print Photostrip</button>
                        </div>
                    `;
                }
            });
            
            previewHTML += `
                    </div>
                    <script>
                        console.log('Preview loaded successfully');
                        // Auto-focus for better UX
                        window.focus();
                    </script>
                </body>
                </html>
            `;
            
            previewWindow.document.write(previewHTML);
            previewWindow.document.close();
            
            showNotification('👁️ Preview dibuka di tab baru!', 'success');
        }

        function showNotification(message, type = 'success') {
            // Remove existing notification
            const existing = document.querySelector('.notification');
            if (existing) {
                existing.remove();
            }
            
            // Create new notification
            const notification = document.createElement('div');
            notification.className = `notification ${type === 'error' ? 'error' : ''}`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            // Show notification
            setTimeout(() => notification.classList.add('show'), 100);
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        function previewPhotostrips() {
            // Hide slots for preview
            canvases.forEach((canvas, canvasIndex) => {
                Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: false }));
                canvas.renderAll();
            });

            const previewWindow = window.open('', '_blank', 'width=1000,height=700,scrollbars=yes');
            
            let previewHTML = '<html>' +
                '<head>' +
                    '<title>Preview Photostrips</title>' +
                    '<style>' +
                        'body { font-family: "Poppins", sans-serif; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); margin: 0; }' +
                        'h1 { text-align: center; color: #6C63FF; font-family: "Fredoka One", cursive; margin-bottom: 30px; }' +
                        '.preview-container { display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px; justify-items: center; }' +
                        '.photostrip-preview { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); max-width: 350px; }' +
                        '.photostrip-preview h3 { margin: 0 0 15px 0; color: #FF6584; text-align: center; font-weight: bold; }' +
                        '.photostrip-image { width: 300px; height: 600px; border-radius: 15px; }' +
                        '@media print { body { background: white !important; } }' +
                    '</style>' +
                '</head>' +
                '<body>' +
                    '<h1>✨ Preview Photostrips</h1>' +
                    '<div class="preview-container">';
            
            canvases.forEach((canvas, index) => {
                if (Object.keys(frameState[index].images).length > 0) {
                    const dataURL = canvas.toDataURL({ format: 'png', quality: 0.9 });
                    const frameName = FRAMES_DATA[index].name || ('Frame ' + (index + 1));
                    previewHTML += 
                        '<div class="photostrip-preview">' +
                            '<h3>📷 ' + frameName + '</h3>' +
                            '<img src="' + dataURL + '" class="photostrip-image" alt="Photostrip Preview">' +
                        '</div>';
                }
            });
            
            previewHTML += '</div></body></html>';
            
            previewWindow.document.write(previewHTML);
            previewWindow.document.close();
            
            // Show slots again
            canvases.forEach((canvas, canvasIndex) => {
                Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: true }));
                canvas.renderAll();
            });
            
            showNotification('👁️ Preview dibuka di tab baru!', 'success');
        }

        function saveLayouts() {
            const continueBtn = document.getElementById('continue-btn');
            if (continueBtn.disabled) {
                alert('Lengkapi semua slot sebelum melanjutkan!');
                return;
            }
            
            continueBtn.disabled = true;
            continueBtn.textContent = 'Menyimpan...';
            
            // Hide slots before generating final images
            canvases.forEach((canvas, canvasIndex) => {
                Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: false }));
                canvas.renderAll();
            });

            const finalImages = canvases.map(canvas => {
                return canvas.toDataURL({ format: 'png', quality: 0.9, multiplier: 2 });
            });

            // Show slots again
            canvases.forEach((canvas, canvasIndex) => {
                Object.values(frameState[canvasIndex].slots).forEach(slot => slot.set({ visible: true }));
                canvas.renderAll();
            });

            // Save to backend
            fetch(`${URLROOT}/photo/save-layouts`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    final_images: finalImages
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `${URLROOT}/photo/decoration/${sessionId}`;
                } else {
                    throw new Error(data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error: ' + error.message);
                continueBtn.disabled = false;
                continueBtn.textContent = '🎨 Lanjut ke Dekorasi';
            });
        }
    </script>
</body>
</html>