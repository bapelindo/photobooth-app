<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Bingkai Favoritmu!</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #FF6584;
            --card-bg: #FFFFFF;
            --dark-text: #333;
            --font-display: 'Fredoka One', cursive;
            --font-main: 'Poppins', sans-serif;
        }
        html, body {
            height: 100%; margin: 0; overflow: hidden;
            background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
        }
        body {
            font-family: var(--font-main);
            display: flex; justify-content: center; align-items: center;
            padding: 20px; box-sizing: border-box;
            opacity: 1;
            transition: opacity 0.4s ease-out;
        }
        body.fade-out { opacity: 0; }
        .main-container {
            display: flex;
            flex-direction: column;
            width: 100%; max-width: 1200px; height: 95vh;
            background: rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(12px); border-radius: 25px;
            padding: 20px; box-sizing: border-box;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            opacity: 0;
            animation: contentFadeIn 0.5s ease-in 0.2s forwards;
            transition: opacity 0.5s ease-out;
        }
        .main-container.content-fade-out { opacity: 0; }
        
        .main-container > * {
            opacity: 0;
            animation: innerElementFadeIn 0.5s ease-in 0.7s forwards;
        }

        @keyframes contentFadeIn { to { opacity: 1; } }
        @keyframes innerElementFadeIn { to { opacity: 1; } }

        .info-panel { text-align: center; padding: 10px; background-color: var(--card-bg); border-radius: 20px; flex-shrink: 0; margin-bottom: 15px; transition: opacity 0.3s ease-out, transform 0.3s ease-out; }
        .info-panel h1 { font-family: var(--font-display); color: var(--primary-color); margin: 0; font-size: clamp(1.8rem, 4vh, 2.2rem); }
        .info-panel p { margin: 5px 0 0; color: #555; font-size: clamp(0.8rem, 2vh, 1rem); }
        .info-panel.fade-out { opacity: 0; transform: scale(0.95); }
        
        .frames-grid-container {
            flex-grow: 1;
            overflow-y: auto;
            min-height: 0;
            padding: 10px;
        }

        .frames-grid-container::-webkit-scrollbar {
            width: 8px;
        }
        .frames-grid-container::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .frames-grid-container::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 10px;
        }
        .frames-grid-container::-webkit-scrollbar-thumb:hover {
            background: #554cff;
        }

        .frames-grid {
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(24vh, 1fr)); 
            gap: 20px; 
        }

        .frame-card {
            background-color: var(--card-bg); 
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            cursor: pointer;
            text-decoration: none; 
            color: inherit;
            border: 3px solid transparent;
            overflow: hidden;
            transition: all 0.3s ease-out;
            aspect-ratio: 2 / 6;
            margin-top: 8px;
        }

        .frame-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.25);
            border-color: var(--primary-color);
        }

        .frame-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .frame-card h2 {
            display: none;
        }

        .frame-card.frame-fade-out { opacity: 0; transform: scale(0.95); pointer-events: none; }
        
        .frame-card-clone {
            position: fixed;
            z-index: 1000;
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.3);
        }

        .selection-status {
            font-weight: 600;
            color: var(--primary-color);
            font-size: 1.2rem;
            margin-top: 10px;
        }

        .frame-card {
            position: relative;
        }

        .frame-card.selected {
            border-color: var(--secondary-color);
            transform: scale(0.95);
            box-shadow: 0 0 0 3px rgba(255, 101, 132, 0.3);
        }

        .selection-indicator {
            position: absolute;
            top: 10px;
            right: 10px;
            background: var(--secondary-color);
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 16px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
        }

        .frame-card.selected .selection-indicator {
            opacity: 1;
            transform: scale(1);
        }

        .continue-panel {
            text-align: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            margin-top: 15px;
            flex-shrink: 0;
        }

        .continue-btn {
            font-family: var(--font-display);
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border: none;
            padding: 15px 40px;
            font-size: 1.2rem;
            border-radius: 50px;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(108, 99, 255, 0.3);
        }

        .continue-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(108, 99, 255, 0.4);
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="info-panel">
            <h1>Pilih Bingkai Ajaibmu!</h1>
            <p>Pilih <strong><?= $data['frame_limit'] ?></strong> bingkai favorit untuk photostrip yang menakjubkan.</p>
            <div class="selection-status">
                <span id="selection-count">0</span> / <?= $data['frame_limit'] ?> dipilih
            </div>
        </div>
        <div class="frames-grid-container">
            <div class="frames-grid">
                <?php if (empty($data['frames'])): ?>
                    <p style="text-align: center; grid-column: 1 / -1;">Tidak ada bingkai yang tersedia untuk paket foto ini. Hubungi admin.</p>
                <?php else: ?>
                    <form id="frame-selection-form" method="POST" action="<?= URLROOT; ?>/photo/submit-frame-selection" style="display: contents;">
                        <input type="hidden" name="transaction_id" value="<?= $data['transaction_id'] ?>">
                        <?php foreach ($data['frames'] as $frame): ?>
                            <div class="frame-card" data-frame-id="<?= $frame->id ?>">
                                <input type="checkbox" name="selected_frames[]" value="<?= $frame->id ?>" style="display: none;">
                                <img src="<?= URLROOT . htmlspecialchars($frame->path); ?>" alt="<?= htmlspecialchars($frame->name); ?>">
                                <div class="selection-indicator">✓</div>
                                <h2><?= htmlspecialchars($frame->name); ?></h2>
                            </div>
                        <?php endforeach; ?>
                    </form>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="continue-panel" id="continue-panel" style="display: none;">
            <button type="submit" form="frame-selection-form" class="continue-btn">Lanjutkan ke Sesi Foto</button>
        </div>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const frameCards = document.querySelectorAll('.frame-card');
            const selectionCount = document.getElementById('selection-count');
            const continuePanel = document.getElementById('continue-panel');
            const frameLimit = <?= $data['frame_limit'] ?>;
            let selectedFrames = [];

            frameCards.forEach(frameCard => {
                frameCard.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const frameId = this.dataset.frameId;
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    
                    if (this.classList.contains('selected')) {
                        // Deselect frame
                        this.classList.remove('selected');
                        checkbox.checked = false;
                        selectedFrames = selectedFrames.filter(id => id !== frameId);
                    } else {
                        // Select frame if limit not reached
                        if (selectedFrames.length < frameLimit) {
                            this.classList.add('selected');
                            checkbox.checked = true;
                            selectedFrames.push(frameId);
                        }
                    }

                    // Update selection count
                    selectionCount.textContent = selectedFrames.length;
                    
                    // Show/hide continue button
                    if (selectedFrames.length === frameLimit) {
                        continuePanel.style.display = 'block';
                        continuePanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        continuePanel.style.display = 'none';
                    }
                });
            });

            // Form submission with animation
            const form = document.getElementById('frame-selection-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (selectedFrames.length !== frameLimit) {
                    alert(`Silakan pilih ${frameLimit} frame untuk melanjutkan.`);
                    return;
                }

                // Add fade out animation
                document.body.classList.add('fade-out');
                
                setTimeout(() => {
                    this.submit();
                }, 500);
            });
        });
    </script>

</body>
</html>