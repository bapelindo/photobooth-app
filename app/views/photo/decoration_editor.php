<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Dekorasi - Hias Photostrip</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --bg-gradient: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        body {
            height: 100vh;
            margin: 0;
            padding: 20px;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            user-select: none;
            
            display: flex;
            justify-content: center; align-items: center;box-sizing: border-box;
        }

        .decoration-container {
            display: grid;
            grid-template-columns: 200px 150px 1fr 200px;
            grid-template-rows: auto 0.6fr 0.4fr;
            gap: 10px;
            height: 95vh;
            width: 100%;
            padding: 20px;
            box-sizing: border-box;
            
            background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);
            border-radius: 20px; padding: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            backdrop-filter: blur(10px);
            height: fit-content;
        }

        .header-panel h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin: 0 0 5px 0;
            font-size: 1.5rem;
        }

        .header-panel p {
            margin: 0;
            color: #666;
            font-size: 0.9rem;
        }

        .stickers-panel {
            grid-row: 2;
            grid-column: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
        }

        .stickers-panel h3 {
            margin: 0 0 10px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--secondary-color);
            font-size: 1rem;
        }

        .stickers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(45px, 1fr));
            gap: 6px;
            flex-grow: 1;
            overflow-y: auto;
            padding: 5px;
        }

        .sticker-item {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: grab;
            border: 2px solid transparent;
            transition: all 0.3s ease;
            background: rgba(108, 99, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sticker-item:hover {
            border-color: var(--primary-color);
            transform: scale(1.1);
        }

        .sticker-item img {
            width: 80%;
            height: 80%;
            object-fit: contain;
        }

        .tabs-panel {
            grid-row: 2 / 4;
            grid-column: 2;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 10px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .workspace {
            grid-row: 2 / 4;
            grid-column: 3;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 5px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .photostrip-tabs {
            display: flex;
            flex-direction: column;
            gap: 8px;
            width: 100%;
        }

        .photostrip-tab {
            background: rgba(108, 99, 255, 0.2);
            border: 2px solid var(--primary-color);
            padding: 8px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            font-size: 0.75rem;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 5px;
            text-align: center;
        }

        .photostrip-tab:hover {
            background: rgba(108, 99, 255, 0.3);
        }

        .photostrip-tab.active {
            background: var(--primary-color);
            color: white;
        }

        .frame-thumb {
            width: 40px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .photostrip-canvas {
            background: white;
            border: 2px solid #ddd;
            border-radius: 15px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            /* 2:6 inch ratio = 1:3 */
            height: calc(95vh - 180px); /* Adjust to fit workspace */
            aspect-ratio: 1 / 3;
        }

        .canvas-inner {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
            border-radius: 13px;
        }

        .photostrip-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .photo-layer {
            position: absolute;
            border-radius: 6px;
            z-index: 2;
        }

        .decoration-layer {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 3;
            pointer-events: none;
        }

        .sticker-element {
            position: absolute;
            cursor: move;
            pointer-events: all;
            border: 2px dashed transparent;
            transition: border-color 0.3s ease;
            user-select: none;
        }

        .sticker-element:hover {
            border-color: var(--primary-color);
        }

        .sticker-element.selected {
            border-color: var(--secondary-color);
            border-style: solid;
        }

        .sticker-element img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            pointer-events: none;
        }

        .resize-handle {
            position: absolute;
            bottom: -5px;
            right: -5px;
            width: 12px;
            height: 12px;
            background: var(--secondary-color);
            border-radius: 50%;
            cursor: se-resize;
            border: 2px solid white;
        }

        .delete-handle {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 16px;
            height: 16px;
            background: var(--secondary-color);
            border-radius: 50%;
            cursor: pointer;
            border: 2px solid white;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 10px;
            font-weight: bold;
        }

        .layout-manager {
            grid-row: 3;
            grid-column: 1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .tools-panel {
            grid-row: 2 / 4;
            grid-column: 4;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 15px;
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .tools-panel h3 {
            margin: 0 0 5px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            font-size: 1rem;
        }

        .tool-group {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 0px;
            padding: 15px;
        }

        .tool-group h4 {
            margin: 0 0 10px 0;
            font-size: 0.9rem;
            color: var(--primary-color);
        }

        .tool-btn {
            width: 100%;
            padding: 10px;
            margin-bottom: 8px;
            border: none;
            border-radius: 8px;
            background: var(--primary-color);
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .tool-btn:hover {
            background: #554dff;
            transform: translateY(-1px);
        }

        .tool-btn.danger {
            background: var(--secondary-color);
        }

        .tool-btn.danger:hover {
            background: #ff4f6d;
        }

        .layer-list {
            max-height: 120px;
            overflow-y: auto;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 8px;
            padding: 8px;
            flex-grow: 1;
        }

        .layer-item {
            padding: 6px;
            margin-bottom: 4px;
            background: white;
            border-radius: 6px;
            font-size: 0.8rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .layer-item.selected {
            background: var(--primary-color);
            color: white;
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

        .btn-finish {
            background: linear-gradient(135deg, var(--success-color), var(--primary-color));
            color: white;
            font-size: 1.2rem;
            padding: 15px 40px;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        @media (max-width: 1200px) {
            .decoration-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto auto auto;
                gap: 8px;
                padding: 8px;
            }

            .stickers-panel {
                grid-column: 1;
                grid-row: 2;
                height: auto;
                max-height: 120px;
            }

            .tabs-panel {
                grid-column: 1;
                grid-row: 3;
                flex-direction: row;
                height: auto;
                overflow-x: auto;
            }

            .workspace {
                grid-column: 1;
                grid-row: 4;
            }

            .layout-manager {
                grid-column: 1;
                grid-row: 5;
                height: auto;
                margin-top: 0;
            }

            .tools-panel {
                grid-column: 1;
                grid-row: 6;
                height: auto;
                max-height: 150px;
                overflow-y: auto;
            }

            .photostrip-canvas {
                width: min(200px, 60vw);
            }
        }
    </style>
</head>
<body>
    <div class="decoration-container">
        <div class="header-panel">
            <h1>🎨 Editor Dekorasi</h1>
            <p>Tambahkan stiker dan hiasan lainnya untuk membuat photostrip yang lebih menarik!</p>
        </div>

        <div class="stickers-panel">
            <h3>🎭 Pustaka Stiker</h3>
            <div class="stickers-grid" id="stickers-grid">
                <?php if (empty($data['stickers'])): ?>
                    <p style="text-align: center; grid-column: 1 / -1; color: #999; font-size: 0.8rem;">
                        Tidak ada stiker tersedia
                    </p>
                <?php else: ?>
                    <?php foreach ($data['stickers'] as $sticker): ?>
                        <div class="sticker-item" data-sticker-id="<?= $sticker->id ?>" data-sticker-path="<?= $sticker->path ?>">
                            <img src="<?= URLROOT . $sticker->path ?>" alt="<?= $sticker->name ?>">
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="layout-manager">
            <h3 style="margin: 0 0 10px 0; font-family: 'Fredoka One', cursive; color: var(--primary-color); font-size: 1rem;">📋 Layer Manager</h3>
            <div class="layer-list" id="layer-list"></div>
        </div>

        <div class="tabs-panel">
            <div class="photostrip-tabs" id="photostrip-tabs">
                <?php foreach ($data['photostrips'] as $index => $photostrip): ?>
                    <div class="photostrip-tab <?= $index === 0 ? 'active' : '' ?>" data-photostrip-id="<?= $photostrip->id ?>">
                        <img src="<?= URLROOT . $photostrip->frame_path ?>" alt="<?= $photostrip->frame_name ?>" class="frame-thumb">
                        <span><?= $photostrip->frame_name ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="workspace">

            <div id="photostrip-container">
                <?php foreach ($data['photostrips'] as $index => $photostrip): ?>
                    <div class="photostrip-canvas" id="canvas-<?= $photostrip->id ?>" 
                         style="display: <?= $index === 0 ? 'block' : 'none' ?>;">
                        <div class="canvas-inner">
                            <img src="<?= URLROOT . $photostrip->frame_path ?>" alt="Frame" class="photostrip-background">
                            
                            <?php 
                            $layoutData = json_decode($photostrip->layout_data, true) ?: [];
                            // Get actual slot coordinates from frame asset
                            $frameSlotCoordinates = json_decode($photostrip->slot_coordinates ?? '[]', true);
                            
                            // Default slots as fallback if no coordinates in database
                            $defaultSlots = [
                                0 => ['left' => 8.33, 'top' => 6.67, 'width' => 83.33, 'height' => 20],
                                1 => ['left' => 8.33, 'top' => 30, 'width' => 83.33, 'height' => 20],
                                2 => ['left' => 8.33, 'top' => 53.33, 'width' => 83.33, 'height' => 20],
                                3 => ['left' => 8.33, 'top' => 76.67, 'width' => 83.33, 'height' => 20]
                            ];
                            
                            // Use actual coordinates or fallback to defaults
                            $slotsToUse = count($frameSlotCoordinates) > 0 ? $frameSlotCoordinates : $defaultSlots;
                            
                            foreach ($layoutData as $slotIndex => $photo):
                                $slotCoordData = $slotsToUse[$slotIndex] ?? $defaultSlots[0];
                                
                                // Use percentage positioning for responsive design
                                $slot = [
                                    'left' => $slotCoordData['left'],
                                    'top' => $slotCoordData['top'], 
                                    'width' => $slotCoordData['width'],
                                    'height' => $slotCoordData['height']
                                ];
                            ?>
                                <img src="<?= URLROOT . $photo['photoPath'] ?>" 
                                     class="photo-layer"
                                     style="left: <?= $slot['left'] ?>%; top: <?= $slot['top'] ?>%; 
                                            width: <?= $slot['width'] ?>%; height: <?= $slot['height'] ?>%;
                                            object-fit: cover; position: absolute;">
                            <?php endforeach; ?>
                            
                            <div class="decoration-layer" id="decoration-<?= $photostrip->id ?>">
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="tools-panel">
            <div class="tool-group">
                <h4>Aksi Stiker</h4>
                <button class="tool-btn" onclick="duplicateSelected()">📋 Duplikasi</button>
                <button class="tool-btn" onclick="bringToFront()">⬆️ Ke Depan</button>
                <button class="tool-btn" onclick="sendToBack()">⬇️ Ke Belakang</button>
                <button class="tool-btn danger" onclick="deleteSelected()">🗑️ Hapus</button>
            </div>
            
            <div class="tool-group">
                <h4>Reset & Clear</h4>
                <button class="tool-btn danger" onclick="clearCurrentPhotostrip()">🧹 Bersihkan</button>
                <button class="tool-btn danger" onclick="clearAllDecorations()">💥 Reset Semua</button>
            </div>
            
            <div class="tool-group">
                <button class="btn btn-finish" id="finish-btn" onclick="finishDecorations()" style="width: 100%; margin: 0; padding: 12px; font-size: 0.9rem;">
                    ✨ Cetak!
                </button>
            </div>
        </div>
    </div>

    <script>
        const sessionId = <?= $data['session']->id ?>;
        const photostrips = <?= json_encode($data['photostrips']) ?>;
        let currentPhotostripId = photostrips[0]?.id;
        let selectedSticker = null;
        let decorations = {};
        let stickerCounter = 0;
        let isDragging = false;
        let isResizing = false;
        
        // Initialize decorations for each photostrip
        photostrips.forEach(photostrip => {
            decorations[photostrip.id] = [];
        });

        document.addEventListener('DOMContentLoaded', () => {
            initializeStickerLibrary();
            initializePhotostripTabs();
            initializeCanvas();
            updateLayerList();
        });

        function initializeStickerLibrary() {
            const stickerItems = document.querySelectorAll('.sticker-item');
            stickerItems.forEach(item => {
                item.addEventListener('click', () => {
                    addStickerToCanvas(item.dataset.stickerPath, item.dataset.stickerId);
                });
            });
        }

        function initializePhotostripTabs() {
            const tabs = document.querySelectorAll('.photostrip-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active from all tabs
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    
                    // Hide all canvases
                    document.querySelectorAll('.photostrip-canvas').forEach(canvas => {
                        canvas.style.display = 'none';
                    });
                    
                    // Show selected canvas
                    currentPhotostripId = tab.dataset.photostripId;
                    document.getElementById(`canvas-${currentPhotostripId}`).style.display = 'block';
                    
                    // Deselect any selected sticker
                    deselectAllStickers();
                    updateLayerList();
                });
            });
        }

        function initializeCanvas() {
            // Initialize each decoration layer
            photostrips.forEach(photostrip => {
                const decorationLayer = document.getElementById(`decoration-${photostrip.id}`);
                if (decorationLayer) {
                    decorationLayer.addEventListener('click', (e) => {
                        if (e.target === decorationLayer) {
                            deselectAllStickers();
                        }
                    });
                }
            });
        }

        function addStickerToCanvas(stickerPath, stickerId) {
            const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
            const canvasWidth = decorationLayer.offsetWidth;
            const canvasHeight = decorationLayer.offsetHeight;

            stickerCounter++;
            const sticker = {
                id: `sticker-${stickerCounter}`,
                stickerPath: stickerPath,
                stickerAssetId: stickerId,
                x: Math.random() * (canvasWidth > 60 ? canvasWidth - 60 : 0),
                y: Math.random() * (canvasHeight > 60 ? canvasHeight - 60 : 0),
                width: 60,
                height: 60,
                rotation: 0,
                zIndex: stickerCounter
            };
            
            decorations[currentPhotostripId].push(sticker);
            renderSticker(sticker);
            selectSticker(sticker.id);
            updateLayerList();
        }

        function renderSticker(sticker) {
            const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
            const stickerElement = document.createElement('div');
            stickerElement.className = 'sticker-element';
            stickerElement.id = sticker.id;
            stickerElement.style.left = sticker.x + 'px';
            stickerElement.style.top = sticker.y + 'px';
            stickerElement.style.width = sticker.width + 'px';
            stickerElement.style.height = sticker.height + 'px';
            stickerElement.style.transform = `rotate(${sticker.rotation}deg)`;
            stickerElement.style.zIndex = sticker.zIndex;
            
            stickerElement.innerHTML = `
                <img src="<?= URLROOT ?>${sticker.stickerPath}" alt="Sticker">
                <div class="resize-handle"></div>
                <div class="delete-handle">×</div>
            `;
            
            // Add event listeners
            stickerElement.addEventListener('mousedown', startDrag);
            stickerElement.addEventListener('click', (e) => {
                e.stopPropagation();
                selectSticker(sticker.id);
            });
            
            const resizeHandle = stickerElement.querySelector('.resize-handle');
            resizeHandle.addEventListener('mousedown', (e) => {
                e.stopPropagation();
                startResize(e, sticker.id);
            });
            
            const deleteHandle = stickerElement.querySelector('.delete-handle');
            deleteHandle.addEventListener('click', (e) => {
                e.stopPropagation();
                deleteSticker(sticker.id);
            });
            
            decorationLayer.appendChild(stickerElement);
        }

        function startDrag(e) {
            if (isResizing) return;
            
            isDragging = true;
            const stickerId = e.currentTarget.id;
            const stickerElement = e.currentTarget;
            const decorationLayer = stickerElement.parentElement;
            
            selectSticker(stickerId);
            
            const rect = stickerElement.getBoundingClientRect();
            const parentRect = decorationLayer.getBoundingClientRect();
            const offsetX = e.clientX - rect.left;
            const offsetY = e.clientY - rect.top;
            
            function drag(e) {
                if (!isDragging) return;
                
                const newX = e.clientX - parentRect.left - offsetX;
                const newY = e.clientY - parentRect.top - offsetY;
                
                stickerElement.style.left = Math.max(0, Math.min(decorationLayer.offsetWidth - stickerElement.offsetWidth, newX)) + 'px';
                stickerElement.style.top = Math.max(0, Math.min(decorationLayer.offsetHeight - stickerElement.offsetHeight, newY)) + 'px';
                
                // Update sticker data
                const sticker = decorations[currentPhotostripId].find(s => s.id === stickerId);
                if (sticker) {
                    sticker.x = parseInt(stickerElement.style.left);
                    sticker.y = parseInt(stickerElement.style.top);
                }
            }
            
            function stopDrag() {
                isDragging = false;
                document.removeEventListener('mousemove', drag);
                document.removeEventListener('mouseup', stopDrag);
            }
            
            document.addEventListener('mousemove', drag);
            document.addEventListener('mouseup', stopDrag);
        }

        function startResize(e, stickerId) {
            isResizing = true;
            const stickerElement = document.getElementById(stickerId);
            const startX = e.clientX;
            const startY = e.clientY;
            const startWidth = stickerElement.offsetWidth;
            const startHeight = stickerElement.offsetHeight;
            
            function resize(e) {
                if (!isResizing) return;
                
                const deltaX = e.clientX - startX;
                const deltaY = e.clientY - startY;
                const newWidth = Math.max(20, startWidth + deltaX);
                const newHeight = Math.max(20, startHeight + deltaY);
                
                stickerElement.style.width = newWidth + 'px';
                stickerElement.style.height = newHeight + 'px';
                
                // Update sticker data
                const sticker = decorations[currentPhotostripId].find(s => s.id === stickerId);
                if (sticker) {
                    sticker.width = newWidth;
                    sticker.height = newHeight;
                }
            }
            
            function stopResize() {
                isResizing = false;
                document.removeEventListener('mousemove', resize);
                document.removeEventListener('mouseup', stopResize);
            }
            
            document.addEventListener('mousemove', resize);
            document.addEventListener('mouseup', stopResize);
        }

        function selectSticker(stickerId) {
            deselectAllStickers();
            const element = document.getElementById(stickerId);
            if (element) {
                element.classList.add('selected');
                selectedSticker = stickerId;
            }
            updateLayerList();
        }

        function deselectAllStickers() {
            document.querySelectorAll('.sticker-element').forEach(el => {
                el.classList.remove('selected');
            });
            selectedSticker = null;
            updateLayerList();
        }

        function deleteSticker(stickerId) {
            const element = document.getElementById(stickerId);
            if (element) {
                element.remove();
                decorations[currentPhotostripId] = decorations[currentPhotostripId].filter(s => s.id !== stickerId);
                if (selectedSticker === stickerId) {
                    selectedSticker = null;
                }
                updateLayerList();
            }
        }

        function deleteSelected() {
            if (selectedSticker) {
                deleteSticker(selectedSticker);
            }
        }

        function duplicateSelected() {
            if (selectedSticker) {
                const originalSticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (originalSticker) {
                    stickerCounter++;
                    const newSticker = {
                        ...originalSticker,
                        id: `sticker-${stickerCounter}`,
                        x: originalSticker.x + 20,
                        y: originalSticker.y + 20,
                        zIndex: stickerCounter
                    };
                    
                    decorations[currentPhotostripId].push(newSticker);
                    renderSticker(newSticker);
                    selectSticker(newSticker.id);
                    updateLayerList();
                }
            }
        }

        function bringToFront() {
            if (selectedSticker) {
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (sticker) {
                    const maxZ = Math.max(...decorations[currentPhotostripId].map(s => s.zIndex)) + 1;
                    sticker.zIndex = maxZ;
                    document.getElementById(selectedSticker).style.zIndex = maxZ;
                    updateLayerList();
                }
            }
        }

        function sendToBack() {
            if (selectedSticker) {
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (sticker) {
                    const minZ = Math.min(...decorations[currentPhotostripId].map(s => s.zIndex)) - 1;
                    sticker.zIndex = minZ;
                    document.getElementById(selectedSticker).style.zIndex = minZ;
                    updateLayerList();
                }
            }
        }

        function clearCurrentPhotostrip() {
            if (confirm('Hapus semua dekorasi dari photostrip ini?')) {
                const decorationLayer = document.getElementById(`decoration-${currentPhotostripId}`);
                decorationLayer.innerHTML = '';
                decorations[currentPhotostripId] = [];
                selectedSticker = null;
                updateLayerList();
            }
        }

        function clearAllDecorations() {
            if (confirm('Hapus semua dekorasi dari semua photostrip?')) {
                photostrips.forEach(photostrip => {
                    const decorationLayer = document.getElementById(`decoration-${photostrip.id}`);
                    decorationLayer.innerHTML = '';
                    decorations[photostrip.id] = [];
                });
                selectedSticker = null;
                updateLayerList();
            }
        }

        function updateLayerList() {
            const layerList = document.getElementById('layer-list');
            const currentDecorations = decorations[currentPhotostripId] || [];
            
            layerList.innerHTML = '';
            
            if (currentDecorations.length === 0) {
                layerList.innerHTML = '<div style="text-align: center; color: #999; font-style: italic;">Tidak ada stiker</div>';
                return;
            }
            
            // Sort by z-index (highest first)
            const sortedDecorations = [...currentDecorations].sort((a, b) => b.zIndex - a.zIndex);
            
            sortedDecorations.forEach(sticker => {
                const layerItem = document.createElement('div');
                layerItem.className = 'layer-item';
                if (selectedSticker === sticker.id) {
                    layerItem.classList.add('selected');
                }
                layerItem.innerHTML = `
                    <span>Stiker ${sticker.id.split('-')[1]}</span>
                    <span>Z:${sticker.zIndex}</span>
                `;
                layerItem.addEventListener('click', () => selectSticker(sticker.id));
                layerList.appendChild(layerItem);
            });
        }

        function finishDecorations() {
            // Save all decorations to database
            const decorationData = {};
            photostrips.forEach(photostrip => {
                if (decorations[photostrip.id] && decorations[photostrip.id].length > 0) {
                    decorationData[photostrip.id] = decorations[photostrip.id];
                }
            });
            
            fetch('<?= URLROOT ?>/photo/save-decorations', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    decorations: decorationData
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `<?= URLROOT ?>/photo/finalize/${sessionId}`;
                } else {
                    alert('Gagal menyimpan dekorasi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan dekorasi.');
            });
        }

        // Keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            if (selectedSticker) {
                switch (e.key) {
                    case 'Delete':
                    case 'Backspace':
                        e.preventDefault();
                        deleteSelected();
                        break;
                    case 'd':
                        if (e.ctrlKey) {
                            e.preventDefault();
                            duplicateSelected();
                        }
                        break;
                    case 'ArrowUp':
                        e.preventDefault();
                        moveSelected(0, -1);
                        break;
                    case 'ArrowDown':
                        e.preventDefault();
                        moveSelected(0, 1);
                        break;
                    case 'ArrowLeft':
                        e.preventDefault();
                        moveSelected(-1, 0);
                        break;
                    case 'ArrowRight':
                        e.preventDefault();
                        moveSelected(1, 0);
                        break;
                }
            }
        });

        function moveSelected(deltaX, deltaY) {
            if (selectedSticker) {
                const element = document.getElementById(selectedSticker);
                const sticker = decorations[currentPhotostripId].find(s => s.id === selectedSticker);
                if (element && sticker) {
                    const decorationLayer = element.parentElement;
                    sticker.x = Math.max(0, Math.min(decorationLayer.offsetWidth - sticker.width, sticker.x + deltaX));
                    sticker.y = Math.max(0, Math.min(decorationLayer.offsetHeight - sticker.height, sticker.y + deltaY));
                    element.style.left = sticker.x + 'px';
                    element.style.top = sticker.y + 'px';
                }
            }
        }
    </script>
</body>
</html>