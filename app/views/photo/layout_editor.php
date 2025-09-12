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
            --bg-gradient: linear-gradient(135deg, #fed6e3 0%, #ffecd2 100%);
            --primary-color-rgb: 108, 99, 255; /* RGB for #6C63FF */
        }
                /* Firefox Scrollbar */
        html {
            scrollbar-width: thin; /* "auto" or "thin" */
            scrollbar-color: rgba(254, 214, 227, 1  ) rgba(255, 255, 255, 0.95); /* thumb and track color */
        }

        /* Exact same animation as select-frame */
        html, body {
            height: 100%; 
            margin: 0; 
            overflow: hidden;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            padding: 20px;
            display: flex;
            justify-content: center; align-items: center;box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out;
        }

        body.fade-out { 
            opacity: 0; 
        }

        .layout-container {
            display: grid;
            grid-template-columns: 300px 1fr;
            grid-template-rows: auto 1fr; /* Removed 'auto' for controls-panel */
            gap: 10px;
            height: 95vh;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);
            border-radius: 20px; padding: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
        }

        .layout-container.content-fade-out { 
            opacity: 0; 
        }
        
        .layout-container > * {
            opacity: 0;
            animation: innerElementFadeIn 0.5s ease-in 0.7s forwards;
        }

        @keyframes contentFadeIn { 
            to { opacity: 1; } 
        }

        @keyframes innerElementFadeIn { 
            to { opacity: 1; } 
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 5px;
            text-align: center;
            backdrop-filter: blur(10px);
        }

        .header-panel h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin: 0 0 10px 0;
            font-size: 2rem;
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
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            padding: 10px;
            max-height: calc(100vh - 250px);
            align-content: flex-start;
        }

        .draggable-photo {
            width: calc(50% - 6px);
            box-sizing: border-box;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            cursor: grab;
            border: 3px solid transparent;
            transition: all 0.3s ease;
            position: relative;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            user-select: none;
            -webkit-user-drag: element;
            -webkit-user-select: none;
            -moz-user-select: none;
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
            pointer-events: none;
            user-select: none;
            -webkit-user-select: none;
            -moz-user-select: none;
        }

        .workspace {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            backdrop-filter: blur(10px);
            display: grid;
            grid-template-columns: auto 1fr auto; /* Added 'auto' for controls-panel */
            gap: 20px;
            position: relative;
            overflow: hidden;
        }

        .frame-tabs {
            display: flex;
            flex-direction: column;
            gap: 12px;
            padding: 0;
            overflow-y: auto;
            max-height: 100%;
        }

        .frame-tab {
            background: rgba(108, 99, 255, 0.1);
            border: 2px solid var(--primary-color);
            padding: 10px;
            border-radius: 15px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            min-width: 90px;
            text-align: center;
        }

        .frame-tab:hover {
            background: rgba(108, 99, 255, 0.3);
        }

        .frame-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .frame-thumb {
            width: 50px;
            height: 75px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            flex-shrink: 0;
        }

        .photostrip-canvas-container {
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .photostrip-canvas-container canvas {
            border: 3px dashed #ddd !important;
            border-radius: 15px !important;
        }

        #photostrip-container {
            height: 100%;
            width: 100%;
            overflow: hidden;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

.controls-panel {
    background: transparent;
    display: flex;
    flex-direction: column;
    justify-content: flex-end; /* Align items to the bottom */
    gap: 15px;
}

.btn {
    padding: 15px;
    border: none;
    border-radius: 18px; /* Slightly more rounded */
    font-family: 'Poppins', sans-serif;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
}

.side-actions {
    display: flex;
    gap: 10px;
}

.btn-side-action {
    flex: 1;
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(8px);
    color: var(--text-dark);
    border: 1px solid rgba(0, 0, 0, 0.05);
}

.btn-side-action:hover {
    background: white;
    border-color: var(--primary-color);
    color: var(--primary-color);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.btn-continue {
    background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
    color: white;
    font-family: 'Fredoka One', cursive;
    font-size: 1.2rem;
    padding: 10px;
}
.btn-continue:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(255, 127, 80, 0.4);
}

.btn:disabled {
    background: #e0e0e0;
    color: #9e9e9e;
    cursor: not-allowed;
    transform: none;
    box-shadow: none;
}

.progress-indicator {
    background: rgba(255,255,255,0.7);
    padding: 15px;
    border-radius: 18px;
    backdrop-filter: blur(8px);
    text-align: center;
    border: 1px solid rgba(0, 0, 0, 0.05);
    margin-bottom: auto; /* Push to the top */
}

#progress-text {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.9rem;
    margin-bottom: 10px;
    display: block;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: rgba(0,0,0,0.08);
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
                grid-template-columns: 1fr;
                grid-template-rows: auto 1fr auto; /* Added 'auto' for controls-panel */
            }
            
            .frame-tabs {
                flex-direction: row;
                overflow-x: auto;
            }

            .controls-panel {
                order: 4;
            }
        }
    </style>
</head>
<body>
    <div class="layout-container">
        <div class="header-panel">
            <h1>✨ Editor Layout Photostrip</h1>
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
                    <!-- Single canvas container that will be reused for all frames -->
                    <div class="photostrip-canvas-container" id="main-canvas-container">
                        <canvas id="main-canvas"></canvas>
                    </div>
                <?php endif; ?>
            </div>

<div class="controls-panel">
    <div class="progress-indicator">
        <span id="progress-text">Lengkapi semua slot foto!</span>
        <div class="progress-bar">
            <div class="progress-fill" id="progress-fill"></div>
        </div>
    </div>
    
    <div class="side-actions">
        <button class="btn btn-side-action" onclick="clearAllSlots()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            <span>Bersihkan</span>
        </button>
        <button class="btn btn-side-action" onclick="previewPhotostrips()">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
            <span>Preview</span>
        </button>
    </div>

    <button class="btn btn-continue" id="continue-btn" onclick="saveLayouts()" disabled>
        <span>Lanjut ke Dekorasi</span>
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"></path><path d="M12 5l7 7-7 7"></path></svg>
    </button>
</div>
        </div>
    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const FRAMES_DATA = <?= json_encode($data['frames']) ?> || [];
        const URLROOT = '<?= URLROOT ?>';
        let mainCanvas = null;
        const frameData = [];
        let currentFrameIndex = 0;
        let draggedItemData = null;
        let totalSlots = 0;

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
                // Initialize frame data for all frames
                FRAMES_DATA.forEach((frame, index) => {
                    const slotCoords = frame.slot_coordinates ? JSON.parse(frame.slot_coordinates) : [];
                    frameData[index] = {
                        id: frame.id,
                        name: frame.name,
                        path: frame.path,
                        slots: slotCoords,
                        images: {}
                    };
                });

                initializeMainCanvas();
                initializeFrameTabs();
                initializeDragAndDrop();
                loadCurrentFrame();
                
                console.log('Fabric.js layout editor initialized successfully');
            } catch (error) {
                console.error('Error initializing layout editor:', error);
                alert('Error initializing layout editor: ' + error.message);
            }
            
            // Update used status on photos in the gallery
            updatePhotoUsedStatus();
        });

        function updatePhotoUsedStatus() {
            // Remove all 'used' classes first
            document.querySelectorAll('.draggable-photo.used').forEach(el => el.classList.remove('used'));
            
            // Re-apply 'used' status based on current frame data
            frameData.forEach(frame => {
                if (frame.images) {
                    Object.values(frame.images).forEach(imgData => {
                        if (imgData && imgData.photoId) {
                            const photoElement = document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`);
                            if (photoElement) {
                                photoElement.classList.add('used');
                            }
                        }
                    });
                }
            });
        }

        function initializeMainCanvas() {
            const container = document.getElementById('photostrip-container');
            const canvasEl = document.getElementById('main-canvas');
            
            if (!container || !canvasEl) {
                console.error('Canvas elements not found');
                return;
            }

            // Debounce resize events
            let resizeTimeout;
            const resizeObserver = new ResizeObserver(entries => {
                clearTimeout(resizeTimeout);
                resizeTimeout = setTimeout(() => {
                    const entry = entries[0];
                    const containerRect = entry.contentRect;
                    let canvasWidth, canvasHeight;
                    
                    const targetAspectRatio = 1 / 3; // 2:6 ratio
                    const containerAspectRatio = containerRect.width / containerRect.height;

                    if (containerAspectRatio > targetAspectRatio) {
                        // Container is wider than target, so height is the constraint
                        canvasHeight = containerRect.height;
                        canvasWidth = canvasHeight * targetAspectRatio;
                    } else {
                        // Container is taller or equal, so width is the constraint
                        canvasWidth = containerRect.width;
                        canvasHeight = canvasWidth / targetAspectRatio;
                    }

                    mainCanvas.setDimensions({
                        width: canvasWidth,
                        height: canvasHeight
                    });
                    
                    // Reload frame to redraw background and slots with new dimensions
                    loadCurrentFrame();

                }, 100); // 100ms debounce
            });

            resizeObserver.observe(container);

            // Initial setup
            const initialRect = container.getBoundingClientRect();
            let initialWidth, initialHeight;
            const targetAspectRatio = 1 / 3;
            const containerAspectRatio = initialRect.width / initialRect.height;

            if (containerAspectRatio > targetAspectRatio) {
                initialHeight = initialRect.height;
                initialWidth = initialHeight * targetAspectRatio;
            } else {
                initialWidth = initialRect.width;
                initialHeight = initialWidth / targetAspectRatio;
            }

            mainCanvas = new fabric.Canvas(canvasEl, {
                width: initialWidth,
                height: initialHeight,
                selection: true,
                stopContextMenu: true
            });
            
            mainCanvas.allowTouchScrolling = true;
            
            mainCanvas.on('dragover', function(opt) {
                opt.e.preventDefault();
                opt.e.dataTransfer.dropEffect = 'copy';
            });

            mainCanvas.on('drop', function(opt) {
                opt.e.preventDefault();
                if (!draggedItemData) {
                    console.log('❌ No dragged item data');
                    return;
                }
                const pointer = mainCanvas.getPointer(opt.e);
                const currentFrame = frameData[currentFrameIndex];
                let foundSlot = null;
                if (currentFrame && currentFrame.slotObjects) {
                    for (const key in currentFrame.slotObjects) {
                        if (currentFrame.slotObjects[key].containsPoint(pointer)) {
                            foundSlot = currentFrame.slotObjects[key];
                            break;
                        }
                    }
                }
                if (foundSlot) {
                    handleDrop(mainCanvas, foundSlot);
                } else {
                    showNotification('Lepas foto di area slot yang tersedia!', 'error');
                }
                draggedItemData = null;
            });
        }

        function loadCurrentFrame() {
            if (!mainCanvas || !frameData[currentFrameIndex]) {
                console.error('Canvas or frame data not available');
                return;
            }

            const currentFrame = frameData[currentFrameIndex];
            console.log('Loading frame:', currentFrame.name);
            
            // Clear canvas
            mainCanvas.clear();
            mainCanvas.backgroundColor = 'lightblue';

            const imageUrl = URLROOT + currentFrame.path;
            console.log('Attempting to load frame from URL:', imageUrl);

            // Load frame background
            fabric.Image.fromURL(imageUrl, (img, isError) => {
                if (isError || !img) {
                    console.error(`Failed to load frame from ${imageUrl}. isError: ${isError}`);
                    const errorRect = new fabric.Rect({
                        width: mainCanvas.width,
                        height: mainCanvas.height,
                        fill: 'rgba(255, 0, 0, 0.2)',
                        stroke: 'red',
                        strokeWidth: 2
                    });
                    mainCanvas.add(errorRect);
                    mainCanvas.sendToBack(errorRect);
                    mainCanvas.renderAll();
                    return;
                }
                
                console.log('Image loaded successfully. Setting as background.', img);

                mainCanvas.setBackgroundImage(img, () => {
                    console.log('setBackgroundImage callback executed. Rendering canvas.');
                    mainCanvas.renderAll();
                    console.log('renderAll called after setBackgroundImage.');
                }, {
                    scaleX: mainCanvas.width / img.width,
                    scaleY: mainCanvas.height / img.height,
                });
            }, { crossOrigin: 'anonymous' });

            // Create slot rectangles from current frame data
            const slotCoords = currentFrame.slots;
            
            // Default slots if none provided
            const defaultSlots = [
                { left: 8.33, top: 6.67, width: 83.33, height: 20 },
                { left: 8.33, top: 30, width: 83.33, height: 20 },
                { left: 8.33, top: 53.33, width: 83.33, height: 20 },
                { left: 8.33, top: 76.67, width: 83.33, height: 20 }
            ];
            
            const slotsToUse = slotCoords.length > 0 ? slotCoords : defaultSlots;

            // Create slot rectangles
            slotsToUse.forEach((slotCoordData, slotIndex) => {
                const slotRect = new fabric.Rect({
                    left: mainCanvas.width * (slotCoordData.left / 100),
                    top: mainCanvas.height * (slotCoordData.top / 100),
                    width: mainCanvas.width * (slotCoordData.width / 100),
                    height: mainCanvas.height * (slotCoordData.height / 100),
                    fill: 'rgba(108, 99, 255, 0.2)',
                    stroke: '#6C63FF',
                    strokeDashArray: [6, 6],
                    strokeWidth: 3,
                    selectable: false,
                    evented: false,
                    hoverCursor: 'copy',
                    isSlot: true,
                    frameIndex: currentFrameIndex,
                    slotIndex: slotIndex,
                    rx: 10,
                    ry: 10
                });
                
                console.log(`Created slot ${slotIndex}:`, {
                    left: slotRect.left,
                    top: slotRect.top,
                    width: slotRect.width,
                    height: slotRect.height
                });
                mainCanvas.add(slotRect);
                
                // Add slot number text
                const slotText = new fabric.Text(`📷 ${slotIndex + 1}`, {
                    left: slotRect.left + slotRect.width/2,
                    top: slotRect.top + slotRect.height/2,
                    fontSize: 16,
                    fill: '#6C63FF',
                    textAlign: 'center',
                    originX: 'center',
                    originY: 'center',
                    selectable: false,
                    evented: false,
                    fontWeight: 'bold'
                });
                mainCanvas.add(slotText);
                
                // Store slot reference in frame data
                if (!currentFrame.slotObjects) {
                    currentFrame.slotObjects = {};
                }
                currentFrame.slotObjects[slotIndex] = slotRect;
            });

            // Drag and drop handled by HTML5 events in initializeMainCanvas
            console.log('✅ Frame loaded with slot objects:', Object.keys(currentFrame.slotObjects || {}));
            
            // Load any saved images for this frame
            if (currentFrame.images) {
                Object.keys(currentFrame.images).forEach(slotIndex => {
                    const imageData = currentFrame.images[slotIndex];
                    if (imageData && currentFrame.slotObjects[slotIndex]) {
                        // Restore saved image
                        fabric.Image.fromURL(imageData.src, (img) => {
                            const slot = currentFrame.slotObjects[slotIndex];
                            img.set({
                                left: imageData.left || slot.left,
                                top: imageData.top || slot.top,
                                scaleX: imageData.scaleX,
                                scaleY: imageData.scaleY,
                                clipPath: new fabric.Rect({
                                    left: slot.left,
                                    top: slot.top,
                                    width: slot.width,
                                    height: slot.height,
                                    absolutePositioned: true
                                }),
                                selectable: true,
                                hasControls: false,
                                hasBorders: true,
                                moveCursor: 'move',
                                hoverCursor: 'move',
                                lockScalingX: true,
                                lockScalingY: true,
                                lockRotation: true
                            });
                            
                            // Add panning constraints for restored image
                            img.on('moving', function() {
                                const slotWidth = slot.width;
                                const slotHeight = slot.height;
                                const rightBound = slot.left;
                                const leftBound = slot.left - (this.getScaledWidth() - slotWidth);
                                const bottomBound = slot.top;
                                const topBound = slot.top - (this.getScaledHeight() - slotHeight);

                                if (this.left > rightBound) this.left = rightBound;
                                if (this.left < leftBound) this.left = leftBound;
                                if (this.top > bottomBound) this.top = bottomBound;
                                if (this.top < topBound) this.top = topBound;
                            });

                            // Save position when restored photo stops moving
                            img.on('modified', function() {
                                // Update position in frame data
                                if (currentFrame.images[slotIndex]) {
                                    currentFrame.images[slotIndex].left = this.left;
                                    currentFrame.images[slotIndex].top = this.top;
                                }
                            });
                            
                            mainCanvas.add(img);
                            // Restore the fabricImage reference
                            imageData.fabricImage = img;
                        });
                    }
                });
            }
        }

        function initializeDragAndDrop() {
            console.log('Initializing drag and drop...');
            
            const photoSource = document.getElementById('photo-source');
            if (!photoSource) {
                console.error('Photo source element not found');
                return;
            }

            // Add event listeners to each draggable photo individually
            const draggablePhotos = photoSource.querySelectorAll('.draggable-photo');
            console.log('Found draggable photos:', draggablePhotos.length);
            
            draggablePhotos.forEach((photo, index) => {
                console.log(`Setting up photo ${index}:`, photo.dataset);
                
                // Test if photo is actually draggable
                console.log('Photo draggable attribute:', photo.getAttribute('draggable'));
                console.log('Photo element:', photo);
                
                photo.addEventListener('dragstart', (e) => {
                    console.log('🚀 DRAGSTART EVENT TRIGGERED!', e);
                    
                    draggedItemData = {
                        photoSrc: URLROOT + photo.dataset.photoPath,
                        photoId: photo.dataset.photoId,
                        photoPath: photo.dataset.photoPath
                    };
                    console.log('✅ Drag started:', draggedItemData);
                    photo.classList.add('dragging');
                    
                    // Set drag effect
                    if (e.dataTransfer) {
                        e.dataTransfer.effectAllowed = 'copy';
                        e.dataTransfer.setData('text/plain', photo.dataset.photoId);
                        console.log('DataTransfer set successfully');
                    } else {
                        console.error('No dataTransfer object available');
                    }
                });

                photo.addEventListener('dragend', (e) => {
                    photo.classList.remove('dragging');
                    console.log('Drag ended for photo:', photo.dataset.photoId);
                });
                
                // Ensure draggable attribute is set
                photo.setAttribute('draggable', 'true');
                photo.style.cursor = 'grab';
                
                // Test click event to ensure element is interactive
                photo.addEventListener('click', (e) => {
                    console.log('📸 Photo clicked!', photo.dataset.photoId);
                });
                
                // Add hover effects
                photo.addEventListener('mousedown', () => {
                    photo.style.cursor = 'grabbing';
                });
                
                photo.addEventListener('mouseup', () => {
                    photo.style.cursor = 'grab';
                });
                
                photo.addEventListener('mouseover', () => {
                    if (!photo.classList.contains('dragging')) {
                        photo.style.transform = 'scale(1.05)';
                        photo.style.boxShadow = '0 8px 25px rgba(108, 99, 255, 0.4)';
                    }
                });
                
                photo.addEventListener('mouseout', () => {
                    if (!photo.classList.contains('dragging')) {
                        photo.style.transform = '';
                        photo.style.boxShadow = '';
                    }
                });
            });

            console.log('Drag and drop initialized');
        }
        
        function handleDrop(canvas, slot) {
            if (!draggedItemData) return;

            const { photoSrc, photoId: newPhotoId, photoPath: newPhotoPath } = draggedItemData;
            const { frameIndex, slotIndex } = slot;

            console.log('Drop detected:', { photoSrc, newPhotoId, frameIndex, slotIndex });

            const currentFrame = frameData[currentFrameIndex];
            
            // Step 1: Remove old image from target slot (if any)
            const existingImageInTargetSlot = currentFrame.images[slotIndex];
            if (existingImageInTargetSlot) {
                const oldPhotoId = existingImageInTargetSlot.photoId;
                document.querySelector(`.draggable-photo[data-photo-id="${oldPhotoId}"]`)?.classList.remove('used');
                canvas.remove(existingImageInTargetSlot.fabricImage);
            }

            // Step 2: Find & remove same image from OTHER slots in ALL frames (if user moves photo)
            frameData.forEach((frame, fIdx) => {
                if (!frame.images) return;
                Object.entries(frame.images).forEach(([sIdx, imgData]) => {
                    if (imgData && imgData.photoId === newPhotoId) {
                        // If it's current frame, remove from canvas
                        if (fIdx === currentFrameIndex) {
                            canvas.remove(imgData.fabricImage);
                        }
                        // Remove from frame data
                        delete frame.images[sIdx];
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
                    selectable: true, 
                    hasControls: false,
                    hasBorders: true,
                    moveCursor: 'move',
                    hoverCursor: 'move',
                    lockScalingX: true,
                    lockScalingY: true,
                    lockRotation: true
                });

                // Center the image in the slot
                photoImg.left -= (photoImg.getScaledWidth() - slotWidth) / 2;
                photoImg.top -= (photoImg.getScaledHeight() - slotHeight) / 2;

                canvas.add(photoImg);
                
                // Initialize images object if needed
                if (!currentFrame.images) {
                    currentFrame.images = {};
                }
                
                // Update state after new image is added
                currentFrame.images[slotIndex] = { 
                    fabricImage: photoImg, 
                    photoId: newPhotoId,
                    src: photoSrc,
                    photoPath: newPhotoPath,
                    scaleX: scale,
                    scaleY: scale,
                    left: photoImg.left,
                    top: photoImg.top
                };
                
                // Update 'used' status in gallery based on latest state
                updatePhotoUsedStatus();

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

                // Save position when photo stops moving
                photoImg.on('modified', function() {
                    // Update position in frame data
                    if (currentFrame.images[slotIndex]) {
                        currentFrame.images[slotIndex].left = this.left;
                        currentFrame.images[slotIndex].top = this.top;
                    }
                });

                canvas.setActiveObject(photoImg);
                canvas.renderAll();

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
                        
                        // Switch to the selected frame
                        currentFrameIndex = index;
                        console.log('Frame switched to index:', currentFrameIndex);
                        
                        // Load the new frame
                        loadCurrentFrame();
                        
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
            frameData.forEach(state => {
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

            const currentFrame = frameData[currentFrameIndex];
            if (!mainCanvas || !currentFrame) return;

            // Remove all images from the current frame's data and canvas
            if (currentFrame.images) {
                Object.values(currentFrame.images).forEach(imgData => {
                    if (imgData && imgData.fabricImage) {
                        mainCanvas.remove(imgData.fabricImage);
                    }
                    // Unmark the photo in the gallery
                    document.querySelector(`.draggable-photo[data-photo-id="${imgData.photoId}"]`)?.classList.remove('used');
                });
            }

            // Clear the images state for the current frame
            currentFrame.images = {};
            
            // Reload the frame to show empty slots
            loadCurrentFrame();
            checkIfDone();
            
            showNotification('🗑️ Layout dibersihkan!', 'success');
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
            const frame = frameData[currentFrameIndex];
            if (!frame || Object.keys(frame.images).length === 0) {
                showNotification('Tidak ada foto untuk di-preview di frame ini.', 'error');
                return;
            }

            // Hide slots for preview
            if (frame.slotObjects) {
                Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: false }));
            }
            mainCanvas.renderAll();

            const dataURL = mainCanvas.toDataURL({ format: 'png', quality: 0.9 });

            // Show slots again
            if (frame.slotObjects) {
                Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: true }));
            }
            mainCanvas.renderAll();

            const previewWindow = window.open('', '_blank', 'width=1000,height=700,scrollbars=yes');
            
            let previewHTML = '<html>' +
                '<head>' +
                    '<title>Preview Photostrip</title>' +
                    '<style>' +
                        'body { font-family: "Poppins", sans-serif; padding: 20px; background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%); margin: 0; display: flex; align-items: center; justify-content: center; }' +
                        'h1 { text-align: center; color: #6C63FF; font-family: "Fredoka One", cursive; margin-bottom: 30px; }' +
                        '.photostrip-preview { background: white; padding: 25px; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); }' +
                        '.photostrip-preview h3 { margin: 0 0 15px 0; color: #FF6584; text-align: center; font-weight: bold; }' +
                        '.photostrip-image { width: 300px; height: 900px; border-radius: 15px; object-fit: contain; }' +
                        '@media print { body { background: white !important; } }' +
                    '</style>' +
                '</head>' +
                '<body>' +
                    '<div class="photostrip-preview">' +
                        '<h3>📷 ' + frame.name + '</h3>' +
                        '<img src="' + dataURL + '" class="photostrip-image" alt="Photostrip Preview">' +
                    '</div>' +
                '</body></html>';
            
            previewWindow.document.write(previewHTML);
            previewWindow.document.close();
            
            showNotification('👁️ Preview dibuka di tab baru!', 'success');
        }

        async function saveLayouts() {
            const continueBtn = document.getElementById('continue-btn');
            if (continueBtn.disabled) {
                alert('Lengkapi semua slot sebelum melanjutkan!');
                return;
            }
            
            continueBtn.disabled = true;
            continueBtn.textContent = 'Menyimpan...';
            
            const finalImages = [];
            const payloadFrameData = [];
            
            // Generate final images for each frame
            for (let frameIndex = 0; frameIndex < frameData.length; frameIndex++) {
                const frame = frameData[frameIndex];
                
                // Switch to this frame if not current
                if (frameIndex !== currentFrameIndex) {
                    currentFrameIndex = frameIndex;
                    loadCurrentFrame();
                    
                    // Wait a bit for frame to fully load and render
                    await new Promise(resolve => setTimeout(resolve, 100));
                }
                
                // Hide slots before generating final image
                if (frame.slotObjects) {
                    Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: false }));
                }
                
                // Force render multiple times to ensure all elements are properly positioned
                mainCanvas.renderAll();
                await new Promise(resolve => setTimeout(resolve, 50));
                mainCanvas.renderAll();
                await new Promise(resolve => setTimeout(resolve, 50));
                
                // Generate final image
                finalImages.push(mainCanvas.toDataURL({ format: 'png', quality: 0.9, multiplier: 2 }));
                
                // Show slots again
                if (frame.slotObjects) {
                    Object.values(frame.slotObjects).forEach(slot => slot.set({ visible: true }));
                }
                mainCanvas.renderAll();

                // Prepare frame data for payload
                const photos = frame.images ? Object.entries(frame.images).map(([slotIndex, imageData]) => {
                    const fabricImage = imageData.fabricImage;
                    const slot = frame.slotObjects[slotIndex];
                    
                    let panX = 0.5;
                    let panY = 0.5;

                    if (fabricImage && slot) {
                        const slotWidth = slot.width;
                        const slotHeight = slot.height;
                        const scaledWidth = fabricImage.getScaledWidth();
                        const scaledHeight = fabricImage.getScaledHeight();

                        const rightBoundX = slot.left;
                        const leftBoundX = slot.left - (scaledWidth - slotWidth);
                        
                        const bottomBoundY = slot.top;
                        const topBoundY = slot.top - (scaledHeight - slotHeight);

                        if (scaledWidth > slotWidth) {
                            panX = (fabricImage.left - leftBoundX) / (rightBoundX - leftBoundX);
                        }

                        if (scaledHeight > slotHeight) {
                            panY = (fabricImage.top - topBoundY) / (bottomBoundY - topBoundY);
                        }
                    }

                    return {
                        slot: parseInt(slotIndex),
                        photoId: imageData.photoId,
                        photoPath: imageData.photoPath,
                        panX: isNaN(panX) ? 0.5 : panX,
                        panY: isNaN(panY) ? 0.5 : panY,
                        left: fabricImage ? fabricImage.left : 0,
                        top: fabricImage ? fabricImage.top : 0,
                        scaleX: fabricImage ? fabricImage.scaleX : 1,
                        scaleY: fabricImage ? fabricImage.scaleY : 1
                    };
                }) : [];

                payloadFrameData.push({
                    frame_id: frame.id,
                    photos: photos
                });
            }

            // Save to backend
            fetch(`${URLROOT}/photo/save-layouts`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    session_id: sessionId,
                    final_images: finalImages,
                    frame_data: payloadFrameData
                })
            })
            .then(response => {
                if (!response.ok) {
                    // Try to get error message from body
                    return response.json().then(err => { 
                        throw new Error(err.message || `HTTP error! status: ${response.status}`);
                    }).catch(() => {
                        // If body is not json or empty
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Same fade-out animation as select-frame
                    document.body.classList.add('fade-out');
                    
                    setTimeout(() => {
                        window.location.href = `${URLROOT}/photo/decoration/${sessionId}`;
                    }, 500);
                } else {
                    throw new Error(data.message || 'An unknown error occurred.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Tidak bisa Lanjut ke Dekorasi: ' + error.message);
                continueBtn.disabled = false;
                continueBtn.textContent = '🎨 Lanjut ke Dekorasi';
            });
        }
    </script>
</body>
</html>