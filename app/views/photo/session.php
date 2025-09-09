<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Foto Interaktif</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --success-color: #4CAF50;
            --warning-color: #FF9800;
            --accent-color: #FFD166;
            --bg-gradient: linear-gradient(135deg, #c2e9fb 0%, #fed6e3 100%);
        }

        body {
            height: 100vh;
            margin: 0;
            overflow: hidden;
            font-family: 'Poppins', sans-serif;
            background: var(--bg-gradient);
            padding: 20px;
            display: flex;
            justify-content: center; align-items: center;box-sizing: border-box;
        }

        .session-container {
            display: grid;
            grid-template-rows: auto 1fr;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
            width: 100%;
            height: 95vh;
            padding: 20px;
            box-sizing: border-box;
            background: rgba(255, 255, 255, 0.5); backdrop-filter: blur(10px);
            border-radius: 20px; padding: 20px; box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05);
            
        }

        .header-panel {
            grid-column: 1 / -1;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
        }

        .session-info h1 {
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            margin: 0;
            font-size: 1.8rem;
        }

        .session-stats {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 1.5rem;
            font-weight: bold;
            color: var(--secondary-color);
        }

        .stat-label {
            font-size: 0.8rem;
            color: #666;
            margin-top: 2px;
        }

        .timer {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
        }

        .timer.warning {
            color: var(--warning-color);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .camera-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            position: relative;
            display: flex;
            padding: 5;
            align-items: center;
            flex-direction: column;
        }

        .safe-zone {
            flex-grow: 1;
            position: relative;
            border-radius: 10px;
            margin: 10px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #000;
        }

        .safe-zone-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 1;
        }

        .safe-zone-shade {
            position: absolute;
            background: rgba(0, 0, 0, 0.4);
            pointer-events: none;
        }

        .safe-zone-clear {
            position: absolute;
            background: transparent;
            border: 2px dashed rgba(108, 99, 255, 0.8);
            border-radius: 8px;
        }

        .safe-zone-label {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(108, 99, 255, 0.95);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
            font-weight: 600;
            white-space: nowrap;
            backdrop-filter: blur(5px);
        }

        .safe-zone-instruction {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.95);
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            color: var(--primary-color);
            text-align: center;
            font-size: 0.8rem;
            backdrop-filter: blur(10px);
        }

        #camera-feed {
            width: 59vw;
            height: 58.8vh;
            object-fit: contain;
            border-radius: 7px;
            background: #000;
        }

        .photo-preview {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.9);
            display: none;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            z-index: 10;
        }

        .preview-image {
            max-width: 80%;
            max-height: 60%;
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .preview-actions {
            margin-top: 20px;
            display: flex;
            gap: 15px;
        }

        .capture-section {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            justify-content: center;
        }

        .filter-dropdown {
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 15px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
        }

        .filter-dropdown label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.9rem;
        }

        .filter-dropdown select {
            padding: 8px 12px;
            border: 2px solid var(--primary-color);
            border-radius: 10px;
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            outline: none;
            min-width: 120px;
        }

        .filter-dropdown select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(255, 101, 132, 0.2);
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

        .btn-capture {
            background: var(--accent-color);
            color: white;
            font-size: 1.2rem;
            padding: 15px 40px;
            var(--accent-color);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
        }

        .btn-capture:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .btn-save {
            background: var(--primary-color);
            color: white;
        }

        .btn-delete {
            background: var(--secondary-color);
            color: white;
        }

        .btn-continue {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            font-size: 1.1rem;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .sidebar {
            display: flex;
            flex-direction: column;
            min-height: 0;
            overflow-y: auto;
            gap: 15px;
        }

        .gallery-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            min-height: 0;
        }

        .gallery-panel h3 {
            margin: 0 0 15px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--primary-color);
            flex-shrink: 0;
        }

        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 10px;
            flex: 1;
            overflow-y: auto;
            overflow-x: hidden;
            min-height: 0;
            max-height: 400px;
            padding: 10px;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
            align-items: start;
            justify-items: center;
            grid-auto-rows: min-content;
        }
        
        .photo-gallery::-webkit-scrollbar {
            width: 6px;
        }
        
        .photo-gallery::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.1);
            border-radius: 3px;
        }
        
        .photo-gallery::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }


        /* Responsive grid for different screen sizes */
        @media (max-width: 400px) {
            .photo-gallery {
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                padding: 6px;
            }
            
            .gallery-photo {
                max-width: 80px;
                max-height: 80px;
            }
        }


        .filter-controls {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .filter-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-top: 10px;
        }

        

        #camera-feed.filter-sepia { filter: sepia(1); }
        #camera-feed.filter-grayscale { filter: grayscale(1); }
        #camera-feed.filter-blur { filter: blur(2px); }
        #camera-feed.filter-brightness { filter: brightness(1.3); }
        #camera-feed.filter-contrast { filter: contrast(1.5); }
        #camera-feed.filter-saturate { filter: saturate(1.8); }
        #camera-feed.filter-hue-rotate { filter: hue-rotate(90deg); }
        #camera-feed.filter-invert { filter: invert(1); }
        #camera-feed.filter-vintage { filter: sepia(0.5) contrast(1.2) brightness(1.1); }

        .gallery-photo {
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            position: relative;
            cursor: pointer;
            border: 2px solid transparent;
            transition: border-color 0.2s ease;
            max-width: 85px;
            max-height: 85px;
            width: 100%;
            height: auto;
        }

        .gallery-photo:hover {
            border-color: var(--primary-color);
        }

        .gallery-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-timestamp {
            position: absolute;
            bottom: 22px;
            left: 2px;
            right: 2px;
            background: rgba(0,0,0,0.7);
            color: white;
            font-size: 0.6rem;
            text-align: center;
            padding: 1px 2px;
            border-radius: 3px;
        }

        .photo-actions {
            position: absolute;
            bottom: 2px;
            left: 2px;
            right: 2px;
            display: flex;
            gap: 2px;
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .gallery-photo:hover .photo-actions {
            opacity: 1;
        }

        .photo-action-btn {
            flex: 1;
            border: none;
            background: rgba(0,0,0,0.8);
            color: white;
            font-size: 0.7rem;
            padding: 2px;
            border-radius: 0 0 6px 6px;
            cursor: pointer;
            transition: background 0.2s ease;
        }

        .photo-action-btn:hover {
            background: rgba(0,0,0,0.9);
        }

        .save-btn {
            background: rgba(76, 175, 80, 0.8);
        }

        .delete-btn {
            background: rgba(244, 67, 54, 0.8);
        }

        /* Ensure capture button stays visible */
        .camera-controls {
            position: relative;
            display: flex;
            flex-direction: column; 
            bottom: 6px;
            background: rgba(108, 99, 255, 0.1);
            padding: 10px;
            border-radius: 20px;
        }
        .safe-zone-guidelines {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
        }

        .guideline {
            position: absolute;
            background: rgba(108, 99, 255, 0.3);
            pointer-events: none;
        }

        .guideline.horizontal {
            left: 0;
            right: 0;
            height: 1px;
        }

        .guideline.vertical {
            top: 0;
            bottom: 0;
            width: 1px;
        }

        .selected-frames {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            max-height: 300px;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .selected-frames h3 {
            margin: 0 0 15px 0;
            font-family: 'Fredoka One', cursive;
            color: var(--secondary-color);
        }

        .frames-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .frame-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            background: rgba(108, 99, 255, 0.1);
            border-radius: 10px;
        }

        .frame-thumbnail {
            width: 50px;
            height: 75px;
            object-fit: contain;
            border-radius: 5px;
            border: 2px solid rgba(108, 99, 255, 0.3);
            flex-shrink: 0;
        }
        
        /* Custom scrollbar for frame list */
        .selected-frames::-webkit-scrollbar {
            width: 8px;
        }
        
        .selected-frames::-webkit-scrollbar-track {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 10px;
        }
        
        .selected-frames::-webkit-scrollbar-thumb {
            background: rgba(108, 99, 255, 0.5);
            border-radius: 10px;
        }
        
        .selected-frames::-webkit-scrollbar-thumb:hover {
            background: rgba(108, 99, 255, 0.7);
        }


        .finish-session-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 12px 20px;
            font-family: 'Fredoka One', cursive;
            font-size: 1rem;
            border-radius: 25px;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .finish-session-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3); }

        .session-expired {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.8);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .expired-modal {
            background: white;
            padding: 40px;
            border-radius: 20px;
            text-align: center;
            max-width: 400px;
        }
    </style>
</head>
<body>
    <div class="session-container">
        <div class="header-panel">
            <div class="session-info">
                <h1>Sesi Foto Interaktif</h1>
                <p>Ambil foto sebanyak-banyaknya, simpan yang terbaik!</p>
            </div>
            <div class="session-stats">
                <div class="stat-item">
                    <div class="stat-value" id="photos-taken">0</div>
                    <div class="stat-label">Foto Diambil</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value" id="photos-saved">0</div>
                    <div class="stat-label">Foto Tersimpan</div>
                </div>
                <div class="stat-item">
                    <div class="stat-value"><?= $data['max_save_photos'] ?></div>
                    <div class="stat-label">Maksimal Simpan</div>
                </div>
                <div class="stat-item">
                    <div class="timer" id="session-timer">
                        <?= sprintf("%02d:%02d", floor($data['session_duration'] / 60), $data['session_duration'] % 60) ?>
                    </div>
                    <div class="stat-label">Waktu Tersisa</div>
                </div>
            </div>
        </div>

        <div class="camera-section">
            <div class="safe-zone">
                <div class="safe-zone-overlay" id="safe-zone-overlay">
                    <!-- Safe zone boxes will be dynamically added here -->
                </div>
                <video id="camera-feed" autoplay playsinline></video>
                <canvas id="capture-canvas" style="display: none;"></canvas>
                
                <div class="photo-preview" id="photo-preview">
                    <img id="preview-image" class="preview-image" src="" alt="Preview">
                    <div class="preview-actions">
                        <button class="btn btn-delete" onclick="deletePhoto()">🗑️ Hapus</button>
                        <button class="btn btn-save" onclick="savePhoto()">💾 Simpan</button>
                    </div>
                </div>
            </div>

            <div class="camera-controls">
                <div class="capture-section">
                    <button class="btn btn-capture" id="capture-btn" onclick="capturePhoto()">📸 Ambil Foto</button>
                    <div class="filter-dropdown">
                        <label for="camera-filter">🎨 Filter:</label>
                        <select id="camera-filter" onchange="applyFilter(this.value)">
                            <option value="none">Normal</option>
                            <option value="sepia">Sepia</option>
                            <option value="grayscale">B&W</option>
                            <option value="vintage">Vintage</option>
                            <option value="brightness">Terang</option>
                            <option value="contrast">Kontras</option>
                            <option value="saturate">Vivid</option>
                            <option value="hue-rotate">Warna</option>
                            <option value="blur">Blur</option>
                        </select>
                    </div>
                    <button class="finish-session-btn" id="finish-session-btn" onclick="finishSession()">
                        ✨ Selesai & Lanjut
                    </button>
                </div>
            </div>
        </div>

        <div class="sidebar">
            <div class="gallery-panel">
                <h3 id="gallery-title">📸 Galeri Foto Sesi (0/<?= $data['max_save_photos'] ?>)</h3>
                <div class="photo-gallery" id="photo-gallery">
                    <!-- Saved photos will appear here -->
                </div>
            </div>


            <div class="selected-frames">
                <h3>Frame Terpilih</h3>
                <div class="frames-list">
                    <?php foreach ($data['frames'] as $frame): ?>
                        <div class="frame-item">
                            <img src="<?= URLROOT . $frame->path ?>" alt="<?= $frame->name ?>" class="frame-thumbnail">
                            <span><?= $frame->name ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="session-expired" id="session-expired">
        <div class="expired-modal">
            <h2>⏰ Waktu Habis!</h2>
            <p>Sesi foto Anda telah berakhir. Mari lanjut ke tahap selanjutnya untuk menata foto-foto terbaik Anda!</p>
            <button class="btn btn-continue" onclick="finishSession()">Lanjutkan</button>
        </div>
    </div>

    <script>
        // Session data
        const sessionId = <?= $data['session']->id ?>;
        const sessionDuration = <?= $data['session_duration'] ?>;
        const maxSavePhotos = <?= $data['max_save_photos'] ?>;
        
        let timeRemaining = sessionDuration;
        let photosTaken = 0;
        let photosSaved = 0;
        let currentPhotoBlob = null;
        let savedPhotos = [];
        
        // DOM elements
        const cameraFeed = document.getElementById('camera-feed');
        const captureCanvas = document.getElementById('capture-canvas');
        const photoPreview = document.getElementById('photo-preview');
        const previewImage = document.getElementById('preview-image');
        const photosTakenEl = document.getElementById('photos-taken');
        const photosSavedEl = document.getElementById('photos-saved');
        const timerEl = document.getElementById('session-timer');
        const photoGallery = document.getElementById('photo-gallery');
        const finishBtn = document.getElementById('finish-session-btn');
        
        // Adjust camera container to match video aspect ratio
        function adjustCameraContainer() {
            const safeZone = document.querySelector('.safe-zone');
            const cameraFeed = document.getElementById('camera-feed');
            
            if (!cameraFeed.videoWidth || !cameraFeed.videoHeight) {
                // Retry after a short delay if video dimensions aren't available yet
                setTimeout(adjustCameraContainer, 100);
                return;
            }
            
            const videoAspectRatio = cameraFeed.videoWidth / cameraFeed.videoHeight;
            const containerWidth = safeZone.parentElement.clientWidth - 40; // Account for padding
            const containerHeight = containerWidth / videoAspectRatio;
            
            // Set the safe zone to match the video aspect ratio
            safeZone.style.width = containerWidth + 'px';
            safeZone.style.height = containerHeight + 'px';
            safeZone.style.maxHeight = 'none'; // Remove any height constraints
            
            console.log(`Camera container adjusted: ${containerWidth}x${containerHeight} (ratio: ${videoAspectRatio.toFixed(2)})`);
        }

        // Initialize camera
        async function initCamera() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { width: 1280, height: 720, facingMode: 'user' }
                });
                cameraFeed.srcObject = stream;
                
                // Ensure camera starts playing
                cameraFeed.onloadedmetadata = () => {
                    cameraFeed.play().then(() => {
                        console.log('Camera started successfully');
                        // Adjust container to match camera aspect ratio
                        adjustCameraContainer();
                        // Delay safe zone calculation to ensure proper dimensions
                        setTimeout(() => {
                            calculateSafeZone();
                        }, 500);
                    }).catch(err => {
                        console.error('Error starting camera playback:', err);
                    });
                };
                
                // Also calculate safe zone when camera feed resizes
                cameraFeed.addEventListener('loadeddata', () => {
                    setTimeout(() => {
                        calculateSafeZone();
                    }, 300);
                });
                
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera.');
            }
        }
        
        // Capture photo
        function capturePhoto() {
            // Prevent multiple captures during preview
            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn.disabled || photoPreview.style.display === 'flex') {
                return;
            }
            
            // Disable capture button immediately
            captureBtn.disabled = true;
            captureBtn.style.opacity = '0.6';
            captureBtn.innerHTML = '⏳ Memproses...';
            
            const canvas = captureCanvas;
            const context = canvas.getContext('2d');
            
            canvas.width = cameraFeed.videoWidth;
            canvas.height = cameraFeed.videoHeight;
            
            // Apply current filter to context before drawing
            const currentFilterValue = document.getElementById('camera-filter').value;
            if (currentFilterValue !== 'none') {
                context.filter = getCanvasFilter(currentFilterValue);
            } else {
                context.filter = 'none';
            }
            
            context.drawImage(cameraFeed, 0, 0);
            
            canvas.toBlob(blob => {
                currentPhotoBlob = blob;
                const url = URL.createObjectURL(blob);
                previewImage.src = url;
                photoPreview.style.display = 'flex';
                
                photosTaken++;
                updateStats();
                
                // Keep button disabled while preview is showing
                captureBtn.innerHTML = '📸 Foto Diambil';
            }, 'image/png', 1.0);
        }
        
        // Convert CSS filter to canvas filter
        function getCanvasFilter(filterName) {
            switch(filterName) {
                case 'sepia': return 'sepia(1)';
                case 'grayscale': return 'grayscale(1)';
                case 'blur': return 'blur(2px)';
                case 'brightness': return 'brightness(1.3)';
                case 'contrast': return 'contrast(1.5)';
                case 'saturate': return 'saturate(1.8)';
                case 'hue-rotate': return 'hue-rotate(90deg)';
                case 'invert': return 'invert(1)';
                case 'vintage': return 'sepia(0.5) contrast(1.2) brightness(1.1)';
                default: return 'none';
            }
        }
        
        // Delete current photo
        function deletePhoto() {
            photoPreview.style.display = 'none';
            currentPhotoBlob = null;
            URL.revokeObjectURL(previewImage.src);
            
            // Re-enable capture button
            const captureBtn = document.getElementById('capture-btn');
            captureBtn.disabled = false;
            captureBtn.style.opacity = '1';
            captureBtn.innerHTML = '📸 Ambil Foto';
        }
        
        // Save current photo
        function savePhoto() {
            if (!currentPhotoBlob || photosSaved >= maxSavePhotos) return;
            
            const formData = new FormData();
            formData.append('session_id', sessionId);
            formData.append('photo', currentPhotoBlob, 'photo.png');
            
            fetch('<?= URLROOT ?>/photo/save-session-photo', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    savedPhotos.push(data.photo);
                    photosSaved++;
                    updateStats();
                    addToGallery(data.photo);
                    deletePhoto();
                    
                    // Auto-finish session when max photos reached
                    if (photosSaved >= maxSavePhotos) {
                        setTimeout(() => {
                            alert(`Selamat! Anda telah menyimpan ${maxSavePhotos} foto. Sesi akan dilanjutkan ke tahap layout.`);
                            finishSession();
                        }, 1500);
                    }
                }
            })
            .catch(error => {
                console.error('Error saving photo:', error);
                alert('Gagal menyimpan foto. Coba lagi.');
            });
        }
        
        // Add photo to gallery
        function addToGallery(photo) {
            const timestamp = Date.now();
            const photoElement = document.createElement('div');
            photoElement.className = 'gallery-photo';
            photoElement.dataset.timestamp = timestamp;
            photoElement.dataset.saved = 'true';
            photoElement.dataset.photoId = photo.id;
            
            // Ensure we have the right path format
            const imagePath = photo.file_path || photo.path || '';
            const imageUrl = imagePath.startsWith('/') ? '<?= URLROOT ?>' + imagePath : '<?= URLROOT ?>/' + imagePath;
            
            photoElement.innerHTML = `
                <img src="${imageUrl}" alt="Session Photo" onerror="this.alt='Failed to load image'; this.style.background='#f0f0f0'; this.style.color='#999';">
                <div class="photo-timestamp">${new Date().toLocaleTimeString()}</div>
                <div class="photo-actions">
                    <button class="photo-action-btn delete-btn" onclick="deleteFromGallery(this, ${photo.id})" title="Hapus">🗑️</button>
                </div>
            `;
            
            // Add click handler for preview
            photoElement.addEventListener('click', (e) => {
                if (!e.target.classList.contains('photo-action-btn')) {
                    const previewImage = document.getElementById('preview-image');
                    const photoPreview = document.getElementById('photo-preview');
                    previewImage.src = imageUrl;
                    photoPreview.style.display = 'flex';
                }
            });
            
            photoGallery.appendChild(photoElement);
            allPhotos.push({ url: imageUrl, timestamp, saved: true, data: photo });
            
            // Update gallery title
            updateGalleryTitle();
                
            // Show finish button if we have saved photos
            if (photosSaved > 0) {
                finishBtn.style.display = 'inline-block';
            }
        }
        
        // Update statistics
        function updateStats() {
            photosTakenEl.textContent = photosTaken;
            photosSavedEl.textContent = photosSaved;
        }
        
        // Timer functionality
        function startTimer() {
            const timer = setInterval(() => {
                timeRemaining--;
                
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                timerEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                // Warning when 1 minute remaining
                if (timeRemaining <= 60) {
                    timerEl.classList.add('warning');
                }
                
                // Session expired
                if (timeRemaining <= 0) {
                    clearInterval(timer);
                    document.getElementById('session-expired').style.display = 'flex';
                }
            }, 1000);
        }
        
        // Finish session
        function finishSession() {
            if (photosSaved === 0) {
                alert('Simpan minimal satu foto sebelum melanjutkan!');
                return;
            }
            
            // Update session status
            fetch('<?= URLROOT ?>/photo/complete-session', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = `<?= URLROOT ?>/photo/layout/${sessionId}`;
                }
            });
        }
        
        // Filter gallery photos
        let allPhotos = [];
        let galleryFilter = 'all';
        
        function filterPhotos(filter) {
            galleryFilter = filter;
            
            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
            event.target.classList.add('active');
            
            const photoGallery = document.getElementById('photo-gallery');
            const photos = photoGallery.querySelectorAll('.gallery-photo');
            
            photos.forEach(photo => {
                const timestamp = parseInt(photo.dataset.timestamp || '0');
                const saved = photo.dataset.saved === 'true';
                const now = Date.now();
                const isRecent = (now - timestamp) < 60000; // Last minute
                
                let show = false;
                switch(filter) {
                    case 'all':
                        show = true;
                        break;
                    case 'saved':
                        show = saved;
                        break;
                    case 'recent':
                        show = isRecent;
                        break;
                }
                
                photo.style.display = show ? 'block' : 'none';
            });
        }

        
        function updateGalleryTitle() {
            const saved = document.querySelectorAll('.gallery-photo[data-saved="true"]').length;
            const total = maxSavePhotos;
            document.getElementById('gallery-title').textContent = `📸 Galeri Foto Sesi (${saved}/${total})`;
        }
        
        function calculateSafeZone() {
    const cameraFeed = document.getElementById('camera-feed');
    const safeZoneContainer = document.querySelector('.safe-zone');
    const safeZoneOverlay = document.getElementById('safe-zone-overlay');
    const frames = <?= json_encode($data['frames']) ?>;
    let allSlots = [];

    frames.forEach(frame => {
        try {
            const slotCoords = JSON.parse(frame.slot_coordinates || '[]');
            if (Array.isArray(slotCoords) && slotCoords.length > 0) {
                slotCoords.forEach((slot, index) => {
                    if (slot && typeof slot === 'object') {
                        let w = slot.width || 100;
                        let h = slot.height || 150;
                        if (w > h) {
                            [w, h] = [h, w];
                        }
                        const validSlot = {
                            left: slot.x || 50,
                            top: slot.y || (50 + index * 125),
                            width: w,
                            height: h,
                        };
                        allSlots.push(validSlot);
                    }
                });
            }
        } catch (e) {
            console.warn('Error parsing slot coordinates for frame:', frame.name, e);
        }
    });

    if (!cameraFeed.videoWidth || !allSlots || allSlots.length === 0) {
        return;
    }

    let minX = 100, minY = 100, maxX = 0, maxY = 0;

    allSlots.forEach(slot => {
        minX = Math.min(minX, slot.left);
        minY = Math.min(minY, slot.top);
        maxX = Math.max(maxX, slot.left + slot.width);
        maxY = Math.max(maxY, slot.top + slot.height);
    });

    const containerWidth = safeZoneContainer.clientWidth;
    const containerHeight = safeZoneContainer.clientHeight;
    const videoWidth = cameraFeed.videoWidth;
    const videoHeight = cameraFeed.videoHeight;

    const containerAspect = containerWidth / containerHeight;
    const videoAspect = videoWidth / videoHeight;

    let renderedWidth, renderedHeight, x, y;

    if (videoAspect > containerAspect) {
        // Video is wider than container, it will be cropped left and right
        renderedHeight = containerHeight;
        renderedWidth = renderedHeight * videoAspect;
        x = (containerWidth - renderedWidth) / 2;
        y = 0;
    } else {
        // Video is taller than container, it will be cropped top and bottom
        renderedWidth = containerWidth;
        renderedHeight = renderedWidth / videoAspect;
        x = 0;
        y = (containerHeight - renderedHeight) / 2;
    }

    safeZoneOverlay.innerHTML = ''; // Clear previous safe zone

    const safeZoneWidth = ((maxX - minX) / 100) * renderedWidth;
    const safeZoneHeight = ((maxY - minY) / 100) * renderedHeight;

    const safeZoneX = x + (renderedWidth - safeZoneWidth) / 2;
    const safeZoneY = y + (renderedHeight - safeZoneHeight) / 2;

    const safeZoneClear = document.createElement('div');
    safeZoneClear.className = 'safe-zone-clear';
    safeZoneClear.style.left = `${safeZoneX}px`;
    safeZoneClear.style.top = `${safeZoneY}px`;
    safeZoneClear.style.width = `${safeZoneWidth}px`;
    safeZoneClear.style.height = `${safeZoneHeight}px`;
    safeZoneOverlay.appendChild(safeZoneClear);
}
        
        function updateSafeZoneDisplay(allSlots) {
            const overlay = document.getElementById('safe-zone-overlay');
            if (!overlay) return;

            // Clear existing zones
            overlay.innerHTML = '';
            overlay.style.clipPath = ''; // Hapus clip-path jika ada

            if (!allSlots || allSlots.length === 0) {
                const instruction = document.createElement('div');
                instruction.className = 'safe-zone-instruction';
                instruction.textContent = '📸 Posisikan diri di tengah untuk hasil foto terbaik';
                overlay.appendChild(instruction);
                return;
            }

            const cameraFeed = document.getElementById('camera-feed');
            const cameraRect = cameraFeed.getBoundingClientRect();
            const unifiedSafeZone = calculateUnifiedSafeZone(allSlots, cameraRect);

            // Buat 4 div untuk area gelap di sekitar zona aman
            const topShade = document.createElement('div');
            topShade.className = 'safe-zone-shade';
            topShade.style.cssText = `top: 0; left: 0; right: 0; height: ${unifiedSafeZone.top}%;`;

            const bottomShade = document.createElement('div');
            bottomShade.className = 'safe-zone-shade';
            bottomShade.style.cssText = `bottom: 0; left: 0; right: 0; height: ${100 - (unifiedSafeZone.top + unifiedSafeZone.height)}%;`;

            const leftShade = document.createElement('div');
            leftShade.className = 'safe-zone-shade';
            leftShade.style.cssText = `top: ${unifiedSafeZone.top}%; left: 0; width: ${unifiedSafeZone.left}%; height: ${unifiedSafeZone.height}%;`;

            const rightShade = document.createElement('div');
            rightShade.className = 'safe-zone-shade';
            rightShade.style.cssText = `top: ${unifiedSafeZone.top}%; right: 0; width: ${100 - (unifiedSafeZone.left + unifiedSafeZone.width)}%; height: ${unifiedSafeZone.height}%;`;

            overlay.append(topShade, bottomShade, leftShade, rightShade);

            // Buat kotak garis putus-putus untuk zona aman
            const safeZoneClear = document.createElement('div');
            safeZoneClear.className = 'safe-zone-clear';
            safeZoneClear.style.cssText = `
                left: ${unifiedSafeZone.left}%;
                top: ${unifiedSafeZone.top}%;
                width: ${unifiedSafeZone.width}%;
                height: ${unifiedSafeZone.height}%;
            `;
            
            // Tambahkan label
            const label = document.createElement('div');
            label.className = 'safe-zone-label';
            const aspectRatio = (unifiedSafeZone.width / unifiedSafeZone.height).toFixed(2);
            label.textContent = `Zona Aman`;
            label.style.cssText = `
                left: 50%;
                top: -15px;
                transform: translateX(-50%);
            `;
            safeZoneClear.appendChild(label);
            
            overlay.appendChild(safeZoneClear);

            // Tambahkan instruksi
            const instruction = document.createElement('div');
            instruction.className = 'safe-zone-instruction';
            instruction.textContent = 'Pastikan subjek berada di dalam area ini';
            overlay.appendChild(instruction);

            setTimeout(() => {
                window.safeZoneCalculating = false;
            }, 100);
        }
        
        function calculateUnifiedSafeZone(allSlots, cameraRect) {
            if (!cameraRect || cameraRect.width === 0 || cameraRect.height === 0) {
                // Fallback if camera dimensions aren't available yet
                return { left: 15, top: 15, width: 70, height: 70 };
            }

            const cameraAspect = cameraRect.width / cameraRect.height;
            
            // Calculate average aspect ratio from all available photo slots
            let totalAspectRatio = 0;
            let validSlots = 0;
            allSlots.forEach(slot => {
                if (slot.width > 0 && slot.height > 0) {
                    totalAspectRatio += (slot.width / slot.height);
                    validSlots++;
                }
            });

            // If no valid slots, use a default that's slightly portrait
            const avgSlotAspect = validSlots > 0 ? totalAspectRatio / validSlots : (2/3);
            console.log(`Camera Aspect: ${cameraAspect.toFixed(2)}, Avg Slot Aspect: ${avgSlotAspect.toFixed(2)}`);

            let safeWidthPercent, safeHeightPercent, safeLeftPercent, safeTopPercent;

            if (cameraAspect > avgSlotAspect) {
                // Camera is WIDER than the target slot (e.g., 16:9 camera, 4:3 slot)
                // The final image will be cropped on the sides.
                safeWidthPercent = (avgSlotAspect / cameraAspect) * 100;
                safeHeightPercent = 100;
                safeLeftPercent = (100 - safeWidthPercent) / 2;
                safeTopPercent = 0;
            } else {
                // Camera is TALLER than or equal to the target slot (e.g., 4:3 camera, 1:1 slot)
                // The final image will be cropped on the top and bottom.
                safeWidthPercent = 100;
                safeHeightPercent = (cameraAspect / avgSlotAspect) * 100;
                safeLeftPercent = 0;
                safeTopPercent = (100 - safeHeightPercent) / 2;
            }

            // Apply a safety margin for printing (e.g., 5% from each side)
            const printMargin = 10; // 5% from each side = 10% total reduction
            const marginX = (safeWidthPercent * (printMargin / 100)) / 2;
            const marginY = (safeHeightPercent * (printMargin / 100)) / 2;

            const finalWidth = safeWidthPercent - (marginX * 2);
            const finalHeight = safeHeightPercent - (marginY * 2);
            const finalLeft = safeLeftPercent + marginX;
            const finalTop = safeTopPercent + marginY;

            const safeZone = {
                left: finalLeft,
                top: finalTop,
                width: finalWidth,
                height: finalHeight
            };
            
            console.log('Calculated safe zone:', safeZone);
            return safeZone;
        }

        // Apply camera filter
        let currentFilter = 'none';
        
        function applyFilter(filterName) {
            const cameraFeed = document.getElementById('camera-feed');
            
            // Remove all filter classes
            cameraFeed.className = cameraFeed.className.replace(/filter-\w+/g, '');
            
            // Update select value if called programmatically
            document.getElementById('camera-filter').value = filterName;
            
            // Apply new filter
            currentFilter = filterName;
            if (filterName !== 'none') {
                cameraFeed.classList.add(`filter-${filterName}`);
            }
        }

        // Gallery photo action functions

        function deleteFromGallery(btn, photoId) {
            if (confirm('Hapus foto ini dari galeri?')) {
                const photoElement = btn.closest('.gallery-photo');
                
                btn.disabled = true;
                btn.textContent = '⏳';
                
                // Remove from server
                fetch('<?= URLROOT ?>/photo/deleteSessionPhoto', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ 
                        session_id: sessionId,
                        photo_id: photoId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove from UI
                        photoElement.remove();
                        photosSaved--;
                        
                        // Remove from allPhotos array
                        const photoData = allPhotos.find(p => p.data && p.data.id === photoId);
                        if (photoData) {
                            const index = allPhotos.indexOf(photoData);
                            if (index > -1) allPhotos.splice(index, 1);
                        }
                        
                        updateStats();
                        updateGalleryTitle();
                        
                        // Hide finish button if no photos left
                        if (photosSaved === 0) {
                            finishBtn.style.display = 'none';
                        }
                    } else {
                        btn.disabled = false;
                        btn.textContent = '🗑️';
                        alert('Gagal menghapus foto: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error deleting photo:', error);
                    btn.disabled = false;
                    btn.textContent = '🗑️';
                    alert('Terjadi kesalahan saat menghapus foto');
                });
            }
        }
        
        // Recalculate safe zone when window resizes
        function handleResize() {
            clearTimeout(window.resizeTimer);
            window.resizeTimer = setTimeout(() => {
                console.log('Window resized, adjusting camera and recalculating safe zone');
                adjustCameraContainer();
                calculateSafeZone();
            }, 300);
        }
        

        // Initialize everything
        document.addEventListener('DOMContentLoaded', () => {
            initCamera();
            startTimer();
            
            // Add resize listener
            window.addEventListener('resize', handleResize);
            
            
            // Initial safe zone calculation (fallback if camera doesn't trigger)
            setTimeout(() => {
                if (!window.safeZoneCalculated) {
                    calculateSafeZone();
                }
            }, 1500);
            
            // Keyboard shortcuts
            document.addEventListener('keydown', (e) => {
                if (e.code === 'Space') {
                    e.preventDefault();
                    if (photoPreview.style.display === 'none') {
                        capturePhoto();
                    }
                }
            });
        });
    </script>
</body>
</html>