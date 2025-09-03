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

        .info-panel {
            text-align: center;
            padding: 5px 10px;
            background-color: var(--card-bg);
            border-radius: 20px;
            flex-shrink: 0;
            margin-bottom: 10px;
            transition: opacity 0.3s ease-out, transform 0.3s ease-out;
        }
        .info-panel h1 {
            font-family: var(--font-display);
            color: var(--primary-color);
            margin: 0;
            font-size: clamp(1.5rem, 3.5vh, 2rem);
        }
        .info-panel p {
            margin: 5px 0 0;
            color: #555;
            font-size: clamp(0.8rem, 2vh, 1rem);
        }
        .info-panel .selection-counter {
            font-weight: 700;
            color: var(--secondary-color);
        }
        .info-panel.fade-out { opacity: 0; transform: scale(0.95); };

        .frames-grid-container {
            flex-grow: 1;
            overflow-y: hidden; /* No scrollbar */
            min-height: 0;
            padding: 5px;
            display: flex; /* Changed to flex */
            flex-wrap: wrap; /* Allow wrapping */
            justify-content: center; /* Center items horizontally */
            align-items: center; /* Center items vertically */
            align-content: center; /* Distribute rows evenly */
        }

        .frames-grid {
            width: 100%; /* Take full width of container */
            height: 100%; /* Take full height of container */
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(23vh, 1fr));
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
            position: relative;
            margin: 5px; /* Spacing between cards */
            flex-shrink: 0; /* Prevent shrinking */
            flex-grow: 0; /* Prevent growing */
            /* Width and Height will be set by JavaScript */
        }

        .frame-card:hover {
            transform: translateY(-8px) scale(1.03);
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.25);
            border-color: var(--primary-color);
        }

        .frame-card.selected {
            border-color: var(--primary-color);
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(108, 99, 255, 0.25);
        }
        .frame-card.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .frame-card .selection-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: var(--font-display);
            font-size: 1rem;
            transform: scale(0);
            transition: transform 0.3s ease;
        }
        .frame-card.selected .selection-badge {
            transform: scale(1);
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
        
        .submit-container {
            text-align: center;
            padding-top: 10px;
            flex-shrink: 0;
        }
        .submit-btn {
            font-family: var(--font-display);
            font-size: 1rem;
            padding: 10px 30px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            color: white;
            background-color: var(--secondary-color);
            transition: all 0.2s ease;
            box-shadow: 0 4px 0px #d45a60;
        }
        .submit-btn:disabled {
            background-color: #ccc;
            box-shadow: 0 4px 0px #999;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

    <div class="main-container">
        <form id="frame-form" action="<?= URLROOT; ?>/photo/capture/<?= $data['transaction_id'] ?>" method="POST">
            <div class="info-panel">
                <h1>Pilih <?= $package->frame_count ?> Bingkai Ajaibmu!</h1>
                <p>Kamu telah memilih <span id="selection-count" class="selection-counter">0</span> dari <?= $package->frame_count ?> bingkai.</p>
            </div>
            <div class="frames-grid-container">
                <div class="frames-grid">
                    <?php if (empty($data['frames'])): ?>
                        <p style="text-align: center; width: 100%;">Tidak ada bingkai yang tersedia.</p>
                    <?php else: ?>
                        <?php foreach ($data['frames'] as $frame): ?>
                            <div class="frame-card" data-id="<?= $frame->id ?>">
                                <input type="checkbox" name="selected_frames[]" value="<?= $frame->id ?>" style="display: none;">
                                <img src="<?= URLROOT . htmlspecialchars($frame->path); ?>" alt="<?= htmlspecialchars($frame->name); ?>">
                                <div class="selection-badge">✓</div>
                                <h2><?= htmlspecialchars($frame->name); ?></h2>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="submit-container">
                <button type="submit" id="submit-btn" class="submit-btn" disabled>Lanjutkan ke Sesi Foto</button>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('frame-form');
            const frameCards = document.querySelectorAll('.frame-card');
            const selectionCountEl = document.getElementById('selection-count');
            const submitBtn = document.getElementById('submit-btn');
            const maxSelections = <?= $package->frame_count ?>;
            let selectedFrames = [];
            const CARD_ASPECT_RATIO = 2 / 6; // Width / Height

            // Original selection logic
            frameCards.forEach(card => {
                card.addEventListener('click', () => {
                    const frameId = card.dataset.id;
                    const checkbox = card.querySelector('input[type="checkbox"]');
                    const isSelected = selectedFrames.includes(frameId);

                    if (isSelected) {
                        // Deselect
                        selectedFrames = selectedFrames.filter(id => id !== frameId);
                        card.classList.remove('selected');
                        checkbox.checked = false;
                    } else {
                        // Select
                        if (selectedFrames.length < maxSelections) {
                            selectedFrames.push(frameId);
                            card.classList.add('selected');
                            checkbox.checked = true;
                        }
                    }
                    updateUI();
                });
            });

            function updateUI() {
                selectionCountEl.textContent = selectedFrames.length;

                if (selectedFrames.length === maxSelections) {
                    submitBtn.disabled = false;
                    frameCards.forEach(card => {
                        if (!selectedFrames.includes(card.dataset.id)) {
                            card.classList.add('disabled');
                        }
                    });
                } else {
                    submitBtn.disabled = true;
                    frameCards.forEach(card => {
                        card.classList.remove('disabled');
                    });
                }
            }

            // Function to adjust frame card sizes dynamically
            function adjustFrameCardSizes() {
                const framesGridContainer = document.querySelector('.frames-grid-container');
                const availableHeight = framesGridContainer.clientHeight;

                let bestCardWidth = 0;
                let bestCardHeight = 0;

                // Calculate ideal card height based on container height and number of rows
                const numRows = 2; // We want 2 rows
                bestCardHeight = (availableHeight - (numRows - 1) * (2 * cardMargin)) / numRows;

                // Calculate card width based on height and aspect ratio
                bestCardWidth = bestCardHeight * CARD_ASPECT_RATIO;

                // Apply calculated sizes
                frameCards.forEach(card => {
                    card.style.width = `${bestCardWidth}px`;
                    card.style.height = `${bestCardHeight}px`;
                });
            }

            // New animation logic, triggered by submit button
            submitBtn.addEventListener('click', function(e) {
                if (this.disabled) { // Only animate if button is enabled
                    return;
                }
                e.preventDefault(); // Prevent immediate form submission

                const infoPanel = document.querySelector('.info-panel');
                const body = document.body;

                infoPanel.classList.add('fade-out');
                frameCards.forEach(frame => {
                    frame.classList.add('frame-fade-out'); // Apply fade-out to all frames
                });
                
                body.classList.add('fade-out'); // Fade out the entire body

                setTimeout(() => {
                    form.submit(); // Submit the form after animation
                }, 1100); // Adjust timeout based on CSS transition duration
            });

            // Initial content fade-in
            const mainContainer = document.querySelector('.main-container');
            mainContainer.style.opacity = '1'; // Trigger contentFadeIn animation

            // Adjust sizes on load and resize
            adjustFrameCardSizes();
            window.addEventListener('resize', adjustFrameCardSizes);
        });
    </script>

</body>
</html>
