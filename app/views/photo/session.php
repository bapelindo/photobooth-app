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
            padding: 10px;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            overflow: hidden;
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
            aspect-ratio: 16/9;
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
            background: rgba(0, 0, 0, 0.2);
            pointer-events: none;
        }

        .safe-zone-clear {
            position: absolute;
            background: transparent;
            border: 2px dashed rgba(253, 253, 253, 0.25);
            border-radius: 8px;
        }

        .safe-zone-label {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255, 255, 255, 0.25);
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
            width: 100%;
            height: 100%;
            aspect-ratio: 16/9;
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
            padding: 5px 5px;
            border-radius: 20px;
            backdrop-filter: blur(10px);
        }

        .filter-dropdown label {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 0.7rem;
        }

        .filter-dropdown select {
            padding: 5px 5px;
            border: 2px solid var(--primary-color);
            border-radius: 20px;
            background: white;
            color: var(--primary-color);
            font-weight: 600;
            font-size: 0.7rem;
            cursor: pointer;
            outline: none;
            min-width: 120px;
        }

        .filter-dropdown select:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 2px rgba(255, 101, 132, 0.2);
        }

        .btn {
            padding: 10px 10px;
            border: none;
            border-radius: 25px;
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-capture {
            background: var(--accent-color);
            color: white;
            font-size: 0.7rem;
            padding: 10px 10px;
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
            font-size: 0.7rem;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-fullscreen {
            background: #8A2BE2;
            color: white;
        }
        .btn-fullscreen:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
        }

        .camera-section.fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 1000;
            padding: 0;
            margin: 0;
            border-radius: 0;
        }

        .camera-section.fullscreen .safe-zone {
            width: 100%;
            height: 100%;
            margin: 0;
            border-radius: 0;
        }

        .camera-section.fullscreen .camera-controls {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.2);
            padding: 10px 10px;
            border-radius: 25px;
            z-index: 1001;
            display: flex;
            gap: 15px;
        }

        .fullscreen-timer {
            display: none;
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 10px 20px;
            border-radius: 15px;
            z-index: 1001;
            text-align: center;
        }

        .fullscreen-timer .timer {
            color: white;
        }

        .camera-section.fullscreen .fullscreen-timer {
            display: block;
        }

        .fullscreen-active .header-panel {
            display: none;
        }

        .fullscreen-active .sidebar {
            display: none;
        }

        .fullscreen-active .session-container {
            grid-template-columns: 1fr;
            padding: 0;
            gap: 0;
            height: 100vh;
            width: 100vw;
            max-width: 100vw;
            border-radius: 0px;
        }
        
        .fullscreen-active .camera-section {
            height: 100vh;
            border-radius: 0px;
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

        /* Ensure 16:9 aspect ratio is maintained on all screen sizes */
        @media (max-width: 1200px) {
            .session-container {
                grid-template-columns: 1.8fr 1fr;
                gap: 8px;
                padding: 15px;
            }
            
            .safe-zone {
                margin: 8px;
            }
        }

        @media (max-width: 768px) {
            .session-container {
                grid-template-columns: 1fr;
                grid-template-rows: auto auto 1fr auto;
                height: 100vh;
                padding: 10px;
            }
            
            .camera-section {
                order: 1;
                min-height: 40vh;
            }
            
            .sidebar {
                order: 2;
                flex-direction: row;
                gap: 10px;
                max-height: 30vh;
            }
            
            .gallery-panel, .selected-frames {
                flex: 1;
                min-height: 0;
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

        

        /* Dynamic CSS filters are applied via inline styles to support database-driven filter values */

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
            display: flex;
            flex-direction: column;
            min-height: 0;
            flex: 1;
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
            overflow-y: auto;
            flex: 1;
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
        .frames-list::-webkit-scrollbar {
            width: 8px;
        }
        
        .frames-list::-webkit-scrollbar-track {
            background: rgba(108, 99, 255, 0.1);
            border-radius: 10px;
        }
        
        .frames-list::-webkit-scrollbar-thumb {
            background: rgba(108, 99, 255, 0.5);
            border-radius: 10px;
        }
        
        .frames-list::-webkit-scrollbar-thumb:hover {
            background: rgba(108, 99, 255, 0.7);
        }


        .finish-session-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 10px 10px;
            font-family: 'Fredoka One', cursive;
            font-size: 0.7rem;
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

        .custom-alert-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10000; /* Higher than other overlays */
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .custom-alert-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        .custom-alert-modal {
            background: white;
            padding: 30px;
            border-radius: 15px;
            text-align: center;
            max-width: 350px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transform: translateY(-20px);
            transition: transform 0.3s ease, opacity 0.3s ease;
        }

        .custom-alert-overlay.show .custom-alert-modal {
            transform: translateY(0);
        }

        .custom-alert-modal h2 {
            color: var(--secondary-color);
            font-family: 'Fredoka One', cursive;
            margin-top: 0;
            font-size: 1.8rem;
        }

        .custom-alert-modal p {
            color: #555;
            margin-bottom: 25px;
            line-height: 1.5;
        }

        .custom-alert-modal .btn-continue {
            background: var(--secondary-color); /* Use secondary color for warning */
            font-size: 0.9rem;
            padding: 12px 25px;
        }

        /* Special styling for time extension alert */
        #time-extension-overlay .custom-alert-modal {
            border: 3px solid var(--success-color);
            background: linear-gradient(135deg, #f0fff0 0%, #e6ffe6 100%);
        }

        #time-extension-overlay .custom-alert-modal h2 {
            color: var(--success-color);
        }

        #time-extension-overlay .custom-alert-modal .btn-continue {
            background: var(--success-color);
        }

        .timer.extended {
            color: var(--success-color);
            animation: pulse-green 2s ease-in-out;
        }

        .timer.extended-time {
            color: var(--warning-color);
            border: 2px solid var(--warning-color);
            border-radius: 10px;
            padding: 5px 10px;
            background: rgba(255, 152, 0, 0.1);
        }

        @keyframes pulse-green {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        /* Exact same animation as select-frame */
        html, body {
            height: 100%; 
            margin: 0; 
            overflow: hidden;
        }

        body {
            opacity: 1;
            transition: opacity 0.4s ease-out;
        }

        body.fade-out { 
            opacity: 0; 
        }

        .session-container {
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
        }

        .session-container.content-fade-out { 
            opacity: 0; 
        }
        
        .session-container > * {
            opacity: 0;
            animation: innerElementFadeIn 0.5s ease-in 0.7s forwards;
        }

        @keyframes contentFadeIn { 
            to { opacity: 1; } 
        }

        @keyframes innerElementFadeIn { 
            to { opacity: 1; } 
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
            <div class="stat-item fullscreen-timer">
                <div class="timer" id="session-timer-fullscreen">
                    <?= sprintf("%02d:%02d", floor($data['session_duration'] / 60), $data['session_duration'] % 60) ?>
                </div>
                <div class="stat-label" style="color: white;">Waktu Tersisa</div>
            </div>
            <div class="safe-zone">
                <div class="safe-zone-overlay" id="safe-zone-overlay">
                    <!-- Safe zone boxes will be dynamically added here -->
                </div>
                <video id="camera-feed" autoplay playsinline></video>
                <canvas id="capture-canvas" style="display: none;"></canvas>
                
                <div class="photo-preview" id="photo-preview">
                    <img id="preview-image" class="preview-image" src="" alt="Preview">
                    <div class="preview-actions" id="preview-actions">
                        <button class="btn btn-delete" onclick="deletePhoto()">🗑️ Hapus</button>
                        <button class="btn btn-save" onclick="savePhoto()">💾 Simpan</button>
                    </div>
                </div>
            </div>

            <div class="camera-controls">
                <div class="capture-section">
                    <button class="btn btn-capture" id="capture-btn" onclick="capturePhoto()">📸 Ambil Foto</button>
                    <button class="btn btn-fullscreen" id="fullscreen-btn">🖼️ Fullscreen</button>
                    <button class="finish-session-btn" id="finish-session-btn" onclick="finishSession()">
                        ✨ SELESAI
                    </button>
                    <div class="filter-dropdown">
                        <label for="camera-filter">🎨 FILTER:</label>
                        <select id="camera-filter" onchange="applyFilter(this.value)">
                            <option value="none">Normal</option>
                            <?php if (isset($data['filters']) && is_array($data['filters'])): ?>
                                <?php foreach ($data['filters'] as $filter): ?>
                                    <option value="<?= htmlspecialchars($filter->path ?? 'none') ?>"
                                            data-filter-name="<?= htmlspecialchars($filter->name) ?>">
                                        <?= htmlspecialchars($filter->name) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
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

    <div class="custom-alert-overlay" id="custom-alert-overlay">
        <div class="custom-alert-modal">
            <h2 id="custom-alert-title">Peringatan!</h2>
            <p id="custom-alert-message"></p>
            <button class="btn btn-continue" onclick="hideCustomAlert()">Oke</button>
        </div>
    </div>

    <div class="session-expired" id="session-expired">
        <div class="expired-modal">
            <h2>⏰ Waktu Habis!</h2>
            <p>Sesi foto Anda telah berakhir. Mari lanjut ke tahap selanjutnya untuk menata foto-foto terbaik Anda!</p>
            <button class="btn btn-continue" onclick="finishSession()">Lanjutkan</button>
        </div>
    </div>

    <div class="custom-alert-overlay" id="time-extension-overlay">
        <div class="custom-alert-modal">
            <h2 id="extension-alert-title">⏰ Waktu Diperpanjang!</h2>
            <p id="extension-alert-message"></p>
            <button class="btn btn-continue" onclick="hideTimeExtensionAlert()">Lanjutkan Foto</button>
        </div>
    </div>

    <script>
        // Session data
        const sessionId = <?= $data['session']->id ?>;
        const sessionDuration = <?= $data['session_duration'] ?>;
        const maxSavePhotos = <?= $data['max_save_photos'] ?>;
        <?php
        $totalSlots = 0;
        foreach ($data['frames'] as $frame) {
            $slotCoords = json_decode($frame->slot_coordinates, true);
            if (is_array($slotCoords)) {
                $totalSlots += count($slotCoords);
            }
        }
        ?>
        const numFrameSlots = <?= $totalSlots ?>;
        
        let timeRemaining = sessionDuration;
        let photosTaken = 0;
        let photosSaved = 0;
        let currentPhotoBlob = null;
        let savedPhotos = [];
        let isExtendedTime = false; // Track if we're in extended time
        
        // DOM elements
        const cameraFeed = document.getElementById('camera-feed');
        const captureCanvas = document.getElementById('capture-canvas');
        const photoPreview = document.getElementById('photo-preview');
        const previewImage = document.getElementById('preview-image');
        const photosTakenEl = document.getElementById('photos-taken');
        const photosSavedEl = document.getElementById('photos-saved');
                const timerEl = document.getElementById('session-timer');
        const timerElFullscreen = document.getElementById('session-timer-fullscreen');
        const photoGallery = document.getElementById('photo-gallery');
        const finishBtn = document.getElementById('finish-session-btn');
        
        // Initialize camera with 16:9 aspect ratio
        async function initCamera() {
            try {
                // Request camera with 16:9 aspect ratio
                const stream = await navigator.mediaDevices.getUserMedia({
                    video: { 
                        width: { ideal: 1920, min: 1280 },
                        height: { ideal: 1080, min: 720 },
                        aspectRatio: { ideal: 16/9 },
                        facingMode: 'user' 
                    }
                });
                cameraFeed.srcObject = stream;
                
                // Ensure camera starts playing
                cameraFeed.onloadedmetadata = () => {
                    cameraFeed.play().then(() => {
                        console.log('Camera started successfully with 16:9 aspect ratio');
                        console.log(`Camera resolution: ${cameraFeed.videoWidth}x${cameraFeed.videoHeight}`);
                        
                        // Calculate safe zone after container adjustment
                        setTimeout(() => {
                            calculateSafeZone();
                        }, 500);
                    }).catch(err => {
                        console.error('Error starting camera playback:', err);
                    });
                };
                
                // Recalculate when camera feed loads
                cameraFeed.addEventListener('loadeddata', () => {
                    setTimeout(() => {
                        calculateSafeZone();
                    }, 300);
                });
                
            } catch (err) {
                console.error('Error accessing camera:', err);
                alert('Tidak dapat mengakses kamera. Pastikan browser memiliki izin kamera dan mendukung aspek rasio 16:9.');
            }
        }
        
        // Capture photo with 3-second countdown
        function capturePhoto() {
            // Prevent capture if max photos reached
            if (photosSaved >= maxSavePhotos) {
                showCustomAlert('Anda telah mencapai batas maksimal foto. Tekan tombol SELESAI untuk melanjutkan.');
                return;
            }
            
            // Only auto-prevent capture during extended time when requirement is met
            if (isExtendedTime && photosSaved >= numFrameSlots) {
                showCustomAlert('Anda sudah mencapai jumlah foto yang dibutuhkan untuk frame. Tekan tombol SELESAI untuk melanjutkan.');
                return;
            }
            
            // Prevent multiple captures during preview or countdown
            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn.disabled || photoPreview.style.display === 'flex') {
                return;
            }
            
            // Start 3-second countdown
            captureBtn.disabled = true;
            let countdown = 2;
            
            // Show first countdown number immediately
            captureBtn.innerHTML = `📸 ${countdown}`;
            captureBtn.style.transform = 'scale(1)';
            countdown--;
            
            const countdownInterval = setInterval(() => {
                captureBtn.innerHTML = `📸 ${countdown}`;
                captureBtn.style.transform = 'scale(1)';
                
                countdown--;
                
                if (countdown < 0) {
                    clearInterval(countdownInterval);
                    
                    // Flash effect
                    document.body.style.backgroundColor = 'white';
                    setTimeout(() => {
                        document.body.style.backgroundColor = '';
                    }, 100);
                    
                    // Take the actual photo
                    takePhotoNow(captureBtn);
                }
            }, 1000);
        }
        
        // Actually capture the photo after countdown
        function takePhotoNow(captureBtn) {
            const canvas = captureCanvas;
            const context = canvas.getContext('2d');
            
            canvas.width = cameraFeed.videoWidth;
            canvas.height = cameraFeed.videoHeight;
            
            // Apply current filter to context before drawing
            const currentFilterValue = document.getElementById('camera-filter').value;
            if (currentFilterValue !== 'none' && currentFilterValue) {
                context.filter = getCanvasFilter(currentFilterValue);
            } else {
                context.filter = 'none';
            }
            
            context.drawImage(cameraFeed, 0, 0);
            
            canvas.toBlob(blob => {
                currentPhotoBlob = blob;
                const url = URL.createObjectURL(blob);
                const previewActions = document.getElementById('preview-actions');
                
                // Reset buttons to Hapus/Simpan for new photos
                previewActions.innerHTML = `
                    <button class="btn btn-delete" onclick="deletePhoto()">🗑️ Hapus</button>
                    <button class="btn btn-save" onclick="savePhoto()">💾 Simpan</button>
                `;
                
                previewImage.src = url;
                photoPreview.style.display = 'flex';
                
                photosTaken++;
                updateStats();
                
                // Reset button style and keep disabled while preview is showing
                captureBtn.style.fontSize = '';
                captureBtn.style.transform = '';
                captureBtn.innerHTML = '📸 Foto Diambil';
            }, 'image/png', 1.0);
        }
        
        // Convert CSS filter to canvas filter
        function getCanvasFilter(filterValue) {
            if (filterValue === 'none' || !filterValue) {
                return 'none';
            }
            
            // If it's already a CSS filter property, use it directly
            if (filterValue.includes('(')) {
                return filterValue;
            }
            
            // Fallback for legacy hardcoded filters
            switch(filterValue) {
                case 'sepia': return 'sepia(1)';
                case 'grayscale': return 'grayscale(1)';
                case 'blur': return 'blur(2px)';
                case 'brightness': return 'brightness(1.3)';
                case 'contrast': return 'contrast(1.5)';
                case 'saturate': return 'saturate(1.8)';
                case 'hue-rotate': return 'hue-rotate(90deg)';
                case 'invert': return 'invert(1)';
                case 'vintage': return 'sepia(0.5) contrast(1.2) brightness(1.1)';
                default: return filterValue;
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
                    
                    // Only auto-complete during extended time when requirement is reached
                    if (isExtendedTime && photosSaved >= numFrameSlots) {
                        console.log(`Auto-completing session during extended time: ${photosSaved} photos saved, ${numFrameSlots} slots required`);
                        
                        // Disable capture button and update finish button
                        const captureBtn = document.getElementById('capture-btn');
                        const finishBtn = document.getElementById('finish-session-btn');
                        
                        captureBtn.disabled = true;
                        captureBtn.style.opacity = '0.5';
                        captureBtn.innerHTML = '✅ Cukup';
                        
                        finishBtn.innerHTML = '🎉 SELESAI SESI';
                        finishBtn.style.background = 'var(--success-color)';
                        finishBtn.style.animation = 'pulse 1s infinite';
                        
                        setTimeout(() => {
                            showCustomAlert(`Selamat! Anda telah mencapai ${numFrameSlots} foto yang dibutuhkan. Tekan tombol SELESAI untuk melanjutkan ke layout.`);
                        }, 1000);
                    } else if (photosSaved >= maxSavePhotos) {
                        // Disable capture button when max photos reached
                        const captureBtn = document.getElementById('capture-btn');
                        const finishBtn = document.getElementById('finish-session-btn');
                        
                        captureBtn.disabled = true;
                        captureBtn.style.opacity = '0.5';
                        captureBtn.innerHTML = '📸 Maksimal';
                        
                        finishBtn.innerHTML = '🎉 SELESAI SESI';
                        finishBtn.style.background = 'var(--success-color)';
                        finishBtn.style.animation = 'pulse 1s infinite';
                        
                        // Show alert when max photos reached - user still needs to manually finish
                        setTimeout(() => {
                            showCustomAlert(`Anda telah menyimpan ${maxSavePhotos} foto maksimal. Tekan tombol SELESAI untuk melanjutkan ke layout.`);
                        }, 1000);
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
            
            // Add click handler to show preview (skip if clicking on action buttons)
            photoElement.addEventListener('click', (e) => {
                if (!e.target.classList.contains('photo-action-btn')) {
                    const previewImage = document.getElementById('preview-image');
                    const photoPreview = document.getElementById('photo-preview');
                    const previewActions = document.getElementById('preview-actions');
                    
                    // Show the saved photo
                    previewImage.src = imageUrl;
                    photoPreview.style.display = 'flex';
                    
                    // Change buttons to "Kembali" only for saved photos
                    previewActions.innerHTML = '<button class="btn btn-continue" onclick="returnToCamera()">🔙 Kembali ke Kamera</button>';
                    
                    // Clear any current photo blob since we're viewing a saved photo
                    currentPhotoBlob = null;
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
            updateDeleteButtonStates();
        }

        // Helper function to check if max photos reached
        function isMaxPhotosReached() {
            return photosSaved >= maxSavePhotos || (isExtendedTime && photosSaved >= numFrameSlots);
        }

        // Update delete button visual states
        function updateDeleteButtonStates() {
            const deleteButtons = document.querySelectorAll('.delete-btn');
            deleteButtons.forEach(btn => {
                // Case 1: Photos < minimum OR deleting would go < minimum
                if (photosSaved < numFrameSlots || (photosSaved - 1) < numFrameSlots) {
                    btn.style.opacity = '0.3';
                    if (photosSaved < numFrameSlots) {
                        btn.title = `Tidak boleh hapus - baru ${photosSaved} foto, minimal ${numFrameSlots}`;
                    } else {
                        btn.title = `Tidak boleh hapus - akan jadi ${photosSaved - 1} foto, minimal ${numFrameSlots}`;
                    }
                } else {
                    // Safe to delete
                    btn.style.opacity = '1';
                    btn.title = 'Hapus foto ini';
                }
            });
        }
        
        // Timer functionality
        function startTimer() {
            const timer = setInterval(() => {
                timeRemaining--;
                
                const minutes = Math.floor(timeRemaining / 60);
                const seconds = timeRemaining % 60;
                const timeString = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if(timerEl) timerEl.textContent = timeString;
                if(timerElFullscreen) timerElFullscreen.textContent = timeString;
                
                // Warning when 1 minute remaining
                if (timeRemaining <= 60) {
                    if(timerEl) timerEl.classList.add('warning');
                    if(timerElFullscreen) {
                        timerElFullscreen.classList.add('warning');
                    }
                }
                
                // Session expired
                if (timeRemaining <= 0) {
                    clearInterval(timer);
                    
                    // Check if user has enough photos for frame slots
                    if (photosSaved < numFrameSlots) {
                        // Extend time by 3 minutes if insufficient photos
                        const extensionTime = 180; // 3 minutes in seconds
                        timeRemaining = extensionTime;
                        isExtendedTime = true; // Mark as extended time
                        
                        console.log(`Time extended: Need ${numFrameSlots} photos, have ${photosSaved}, extending by ${extensionTime/60} minutes`);
                        
                        // Show extension notification with special modal
                        const photosNeeded = numFrameSlots - photosSaved;
                        showTimeExtensionAlert(`Waktu diperpanjang ${extensionTime/60} menit! Anda perlu ${photosNeeded} foto lagi untuk melengkapi semua frame slot.`);
                        
                        // Restart timer
                        startTimer();
                        
                        // Remove warning styling and add extended styling
                        if(timerEl) {
                            timerEl.classList.remove('warning');
                            timerEl.classList.add('extended');
                            setTimeout(() => {
                                timerEl.classList.remove('extended');
                                timerEl.classList.add('extended-time'); // Persistent extended time indicator
                            }, 3000);
                        }
                        if(timerElFullscreen) {
                            timerElFullscreen.classList.remove('warning');
                            timerElFullscreen.classList.add('extended');
                            setTimeout(() => {
                                timerElFullscreen.classList.remove('extended');
                                timerElFullscreen.classList.add('extended-time'); // Persistent extended time indicator
                            }, 3000);
                        }
                    } else {
                        // Show normal session expired modal if enough photos
                        document.getElementById('session-expired').style.display = 'flex';
                    }
                }
            }, 1000);
        }
        
        function showCustomAlert(message) {
            const overlay = document.getElementById('custom-alert-overlay');
            const msgElement = document.getElementById('custom-alert-message');
            msgElement.textContent = message;
            overlay.classList.add('show');
        }

        function hideCustomAlert() {
            const overlay = document.getElementById('custom-alert-overlay');
            overlay.classList.remove('show');
        }

        function showTimeExtensionAlert(message) {
            const overlay = document.getElementById('time-extension-overlay');
            const msgElement = document.getElementById('extension-alert-message');
            msgElement.textContent = message;
            overlay.classList.add('show');
        }

        function hideTimeExtensionAlert() {
            const overlay = document.getElementById('time-extension-overlay');
            overlay.classList.remove('show');
        }

        // Finish session
        function finishSession() {
            if (photosSaved < numFrameSlots) {
                showCustomAlert('Simpan minimal ' + numFrameSlots + ' foto sebelum melanjutkan!');
                return;
            }
            
            // Same fade-out animation as select-frame
            document.body.classList.add('fade-out');
            
            // Update session status
            fetch('<?= URLROOT ?>/photo/complete-session', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ session_id: sessionId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Same timing as select-frame (500ms)
                    setTimeout(() => {
                        window.location.href = `<?= URLROOT ?>/photo/layout/${sessionId}`;
                    }, 500);
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
            const safeZoneOverlay = document.getElementById('safe-zone-overlay');
            const frames = <?= json_encode($data['frames']) ?>;
            let allSlots = [];

            // Collect all slots from selected frames
            frames.forEach(frame => {
                try {
                    // Make sure slot_coordinates is a string before parsing
                    if (typeof frame.slot_coordinates !== 'string') {
                        console.warn('Slot coordinates for frame is not a string:', frame.name);
                        return;
                    }
                    const slotCoords = JSON.parse(frame.slot_coordinates || '[]');
                    
                    if (Array.isArray(slotCoords)) {
                        slotCoords.forEach(slot => {
                            // Validate that the slot has the required properties
                            if (slot && typeof slot === 'object' && slot.width && slot.height) {
                                allSlots.push({
                                    width: parseFloat(slot.width),
                                    height: parseFloat(slot.height)
                                });
                            } else {
                                console.warn('Invalid slot data found in frame:', frame.name, slot);
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error parsing slot coordinates for frame:', frame.name, e);
                }
            });

            if (allSlots.length === 0) {
                console.log("No valid slots found, showing basic safe zone.");
                showBasicSafeZone();
                return;
            }

            // Calculate unified safe zone that accommodates all slots
            const unifiedSafeZone = calculateUnifiedSafeZone(allSlots);
            updateSafeZoneDisplay(unifiedSafeZone);
        }
        
        function showBasicSafeZone() {
            const safeZoneOverlay = document.getElementById('safe-zone-overlay');
            safeZoneOverlay.innerHTML = '';
            
            // Create basic safe zone (80% of container, centered)
            const safeZoneClear = document.createElement('div');
            safeZoneClear.className = 'safe-zone-clear';
            safeZoneClear.style.cssText = `
                left: 10%;
                top: 10%;
                width: 80%;
                height: 80%;
            `;
            
            safeZoneOverlay.appendChild(safeZoneClear);
            
            const instruction = document.createElement('div');
            instruction.className = 'safe-zone-instruction';
            instruction.textContent = 'Pastikan subjek berada di dalam area ini untuk hasil foto terbaik';
            safeZoneOverlay.appendChild(instruction);
        }
        
        function updateSafeZoneDisplay(unifiedSafeZone) {
            const overlay = document.getElementById('safe-zone-overlay');
            if (!overlay) return;

            // Clear existing zones
            overlay.innerHTML = '';

            if (!unifiedSafeZone || !unifiedSafeZone.hasValidIntersection) {
                showBasicSafeZone();
                return;
            }

            // Create shaded areas around the safe zone
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

            // Create the safe zone indicator
            const safeZoneClear = document.createElement('div');
            safeZoneClear.className = 'safe-zone-clear';
            safeZoneClear.style.cssText = `
                left: ${unifiedSafeZone.left}%;
                top: ${unifiedSafeZone.top}%;
                width: ${unifiedSafeZone.width}%;
                height: ${unifiedSafeZone.height}%;
            `;
            
            const label = document.createElement('div');
            label.className = 'safe-zone-label';
            label.textContent = `Zona Aman (${unifiedSafeZone.cameraUsage.toFixed(0)}% Terlihat)`;
            safeZoneClear.appendChild(label);
            
            overlay.appendChild(safeZoneClear);

            console.log(`Safe zone updated based on 'contain' logic.`);
        }
        
function calculateUnifiedSafeZone(allSlots) {
            if (!allSlots || allSlots.length === 0) {
                // Jika tidak ada slot, tampilkan safe zone default yang lebih masuk akal.
                return { left: 10, top: 10, width: 80, height: 80, hasValidIntersection: true, cameraUsage: 64 };
            }

            const cameraAspect = 16 / 9; // Rasio aspek kamera (lebar)
            const frameAspect = 1 / 3;   // Rasio aspek photostrip (tinggi)

            // Area aman dimulai dari 100% tampilan kamera, yang kemudian akan diperkecil.
            let safeIntersection = { left: 0, top: 0, right: 100, bottom: 100 }; 

            allSlots.forEach((slot) => {
                const slotWidthPercent = slot.width;
                const slotHeightPercent = slot.height;

                if (isNaN(slotWidthPercent) || isNaN(slotHeightPercent) || slotWidthPercent <= 0 || slotHeightPercent <= 0) {
                    return; // Lewati slot yang tidak valid
                }

                // --- INI BAGIAN PENTING YANG DIPERBAIKI ---
                // Menghitung rasio aspek slot yang sebenarnya dengan memperhitungkan rasio aspek frame.
                // Kita gunakan unit proporsional: anggap lebar frame = 1, maka tingginya = 3.
                const realSlotWidth = (slotWidthPercent / 100) * 1; // Lebar slot proporsional
                const realSlotHeight = (slotHeightPercent / 100) * 3; // Tinggi slot proporsional
                const slotAspect = realSlotWidth / realSlotHeight;

                if (slotAspect > cameraAspect) {
                    // Slot lebih LEBAR dari kamera. Bagian atas/bawah kamera akan terpotong.
                    const scaledCameraHeight = realSlotWidth / cameraAspect;
                    const visibleHeightRatio = realSlotHeight / scaledCameraHeight;
                    const margin = (1 - visibleHeightRatio) / 2;
                    
                    safeIntersection.top = Math.max(safeIntersection.top, margin * 100);
                    safeIntersection.bottom = Math.min(safeIntersection.bottom, 100 - (margin * 100));

                } else {
                    // Slot lebih TINGGI/SEMPIT dari kamera. Bagian kiri/kanan kamera akan terpotong.
                    const scaledCameraWidth = realSlotHeight * cameraAspect;
                    const visibleWidthRatio = realSlotWidth / scaledCameraWidth;
                    const margin = (1 - visibleWidthRatio) / 2;
                    
                    safeIntersection.left = Math.max(safeIntersection.left, margin * 100);
                    safeIntersection.right = Math.min(safeIntersection.right, 100 - (margin * 100));
                }
            });

            // Hitung dimensi akhir dari persimpangan (intersection) semua area aman.
            const finalWidth = Math.max(0, safeIntersection.right - safeIntersection.left);
            const finalHeight = Math.max(0, safeIntersection.bottom - safeIntersection.top);
            
            const result = {
                left: safeIntersection.left,
                top: safeIntersection.top,
                width: finalWidth,
                height: finalHeight,
                hasValidIntersection: finalWidth > 0 && finalHeight > 0,
                cameraUsage: (finalWidth * finalHeight) / 100
            };
            
            console.log(`SAFE ZONE: Posisi (${result.left.toFixed(1)}%, ${result.top.toFixed(1)}%), Ukuran (${result.width.toFixed(1)}% x ${result.height.toFixed(1)}%), Area Terlihat: ${result.cameraUsage.toFixed(1)}%`);

            return result;
        }
        // Apply camera filter
        let currentFilter = 'none';
        
        function applyFilter(filterValue) {
            const cameraFeed = document.getElementById('camera-feed');
            
            // Remove any existing inline filter styles
            cameraFeed.style.filter = '';
            
            // Update select value if called programmatically
            document.getElementById('camera-filter').value = filterValue;
            
            // Apply new filter
            currentFilter = filterValue;
            if (filterValue !== 'none' && filterValue) {
                // Apply CSS filter directly to the video element
                cameraFeed.style.filter = filterValue;
            }
        }

        // Gallery photo action functions
        
        // Return to camera function for gallery back button
        function returnToCamera() {
            // Hide any open preview modal
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview.style.display === 'flex') {
                photoPreview.style.display = 'none';
            }
            
            // Focus back on camera - scroll to camera section if needed
            const cameraSection = document.querySelector('.camera-section');
            cameraSection.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Re-enable capture button if it was disabled due to preview
            const captureBtn = document.getElementById('capture-btn');
            if (captureBtn.disabled && !isMaxPhotosReached()) {
                captureBtn.disabled = false;
                captureBtn.style.opacity = '1';
                captureBtn.innerHTML = '📸 Ambil Foto';
            }
            
            // Clear any current photo blob that might be in preview
            if (currentPhotoBlob) {
                currentPhotoBlob = null;
                const previewImage = document.getElementById('preview-image');
                if (previewImage.src) {
                    URL.revokeObjectURL(previewImage.src);
                }
            }
        }

        function deleteFromGallery(btn, photoId) {
            // Case 1: If current photos < minimum, cannot delete at all
            if (photosSaved < numFrameSlots) {
                showCustomAlert(`Tidak boleh menghapus! Anda baru punya ${photosSaved} foto, minimal diperlukan ${numFrameSlots} foto.`);
                return;
            }
            
            // Case 2: If deleting would make photos < minimum, cannot delete
            if ((photosSaved - 1) < numFrameSlots) {
                showCustomAlert(`Tidak boleh menghapus! Setelah dihapus akan tersisa ${photosSaved - 1} foto, minimal diperlukan ${numFrameSlots} foto.`);
                return;
            }
            
            // Case 3: Safe to delete (will still have >= minimum after deletion)
            if (!confirm('Hapus foto ini dari galeri?')) {
                return;
            }
            
            {
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
                calculateSafeZone();
            }, 300);
        }
        


        // Initialize everything
        document.addEventListener('DOMContentLoaded', () => {
            const fullscreenBtn = document.getElementById('fullscreen-btn');
            const cameraSection = document.querySelector('.camera-section');

            fullscreenBtn.addEventListener('click', () => {
                toggleFullScreen(cameraSection);
            });

            function toggleFullScreen(elem) {
                if (!document.fullscreenElement) {
                    elem.requestFullscreen().catch(err => {
                        alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                    });
                } else {
                    if (document.exitFullscreen) {
                        document.exitFullscreen();
                    }
                }
            }

            document.addEventListener('fullscreenchange', () => {
                if (!document.fullscreenElement) {
                    cameraSection.classList.remove('fullscreen');
                    document.body.classList.remove('fullscreen-active');
                } else {
                    cameraSection.classList.add('fullscreen');
                    document.body.classList.add('fullscreen-active');
                }
                // Recalculate safe zone on fullscreen change
                setTimeout(calculateSafeZone, 300);
            });

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
                        // Prevent space if max photos reached
                        if (photosSaved >= maxSavePhotos) {
                            showCustomAlert('Anda telah mencapai batas maksimal foto. Tekan tombol SELESAI untuk melanjutkan.');
                        // Only prevent space during extended time when requirement is met
                        } else if (isExtendedTime && photosSaved >= numFrameSlots) {
                            showCustomAlert('Anda sudah mencapai jumlah foto yang dibutuhkan untuk frame. Tekan tombol SELESAI untuk melanjutkan.');
                        } else {
                            capturePhoto();
                        }
                    }
                } else if (e.code === 'KeyD' && e.ctrlKey) {
                    // Ctrl+D to toggle slot debug visualization
                    e.preventDefault();
                    window.showSlotDebug = !window.showSlotDebug;
                    console.log('Slot debug visualization:', window.showSlotDebug ? 'ON' : 'OFF');
                    calculateSafeZone(); // Recalculate to show/hide debug indicators
                }
            });
        });
    </script>
</body>
</html>