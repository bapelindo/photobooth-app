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
            padding: 20px;
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
    </style>
</head>
<body>

    <div class="main-container">
        <div class="info-panel">
            <h1>Pilih Bingkai Ajaibmu!</h1>
            <p>Klik bingkai favoritmu untuk memulai sesi foto yang tak terlupakan.</p>
        </div>
        <div class="frames-grid-container">
            <div class="frames-grid">
                <?php if (empty($data['frames'])): ?>
                    <p style="text-align: center; grid-column: 1 / -1;">Tidak ada bingkai yang tersedia untuk paket foto ini. Hubungi admin.</p>
                <?php else: ?>
                    <?php foreach ($data['frames'] as $frame): ?>
                        <a href="<?= URLROOT; ?>/photo/capture/<?= $data['transaction_id'] ?>/<?= $frame->id ?>" class="frame-card">
                            <img src="<?= URLROOT . htmlspecialchars($frame->path); ?>" alt="<?= htmlspecialchars($frame->name); ?>">
                            <h2><?= htmlspecialchars($frame->name); ?></h2>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const allFrames = document.querySelectorAll('a.frame-card');
            const infoPanel = document.querySelector('.info-panel');
            const body = document.body;

            if (allFrames.length === 0) return;

            allFrames.forEach(clickedFrame => {
                clickedFrame.addEventListener('click', function(e) {
                    e.preventDefault();
                    const destination = this.href;

                    infoPanel.classList.add('fade-out');
                    allFrames.forEach(frame => {
                        if (frame !== this) {
                            frame.classList.add('frame-fade-out');
                        }
                    });

                    setTimeout(() => {
                        const clickedFrameRect = this.getBoundingClientRect();
                        const clone = this.cloneNode(true);
                        
                        clone.classList.add('frame-card-clone');
                        clone.style.top = `${clickedFrameRect.top}px`;
                        clone.style.left = `${clickedFrameRect.left}px`;
                        clone.style.width = `${clickedFrameRect.width}px`;
                        clone.style.height = `${clickedFrameRect.height}px`;

                        body.appendChild(clone);
                        this.style.opacity = '0';

                        setTimeout(() => {
                            clone.style.left = `calc(50% - ${clickedFrameRect.width / 2}px)`;
                            clone.style.top = `calc(50% - ${clickedFrameRect.height / 2}px)`;
                        }, 50);

                        setTimeout(() => {
                            clone.style.transition = 'all 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
                            clone.style.transform = 'scale(0.1)';
                            clone.style.opacity = '0';
                            
                            body.classList.add('fade-out');
                        }, 600); 

                        setTimeout(() => {
                            window.location.href = destination;
                        }, 1100); 
                    }, 300); 
                });
            });
        });
    </script>

</body>
</html>