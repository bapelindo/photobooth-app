<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AI Photo Enhance - Photobooth Airways</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link
    href="https://fonts.googleapis.com/css2?family=Orbitron:wght@700;900&family=Roboto+Condensed:wght@400;700&family=Roboto+Mono&display=swap"
    rel="stylesheet">
  <style>
    :root {
      --blue: #00BFFF;
      --blue2: #007FFF;
      --dark: #1B365D;
      --peach: #FB9F8B;
      --peach2: #F58C75;
      --bg: linear-gradient(160deg, #87CEEB 0%, #E0F7FA 55%, #FFDAB9 100%);
      --glass: rgba(255, 255, 255, 0.18);
      --glass-border: rgba(255, 255, 255, 0.35);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0
    }

    body {
      min-height: 100vh;
      background: var(--bg);
      font-family: 'Roboto Condensed', sans-serif;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px
    }

    /* HEADER */
    .top-bar {
      width: 100%;
      max-width: 1100px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      background: var(--glass);
      backdrop-filter: blur(12px);
      border: 1px solid var(--glass-border);
      border-radius: 16px;
      padding: 12px 24px;
      margin-bottom: 20px
    }

    .logo {
      font-family: 'Orbitron', sans-serif;
      font-size: .8rem;
      font-weight: 900;
      color: var(--dark);
      letter-spacing: 2px
    }

    .logo span {
      color: var(--blue);
      display: block;
      font-size: .55rem;
      letter-spacing: 4px;
      font-weight: 400
    }

    .step-badge {
      display: flex;
      gap: 8px;
      align-items: center
    }

    .step {
      width: 28px;
      height: 28px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: .65rem;
      font-weight: 700;
      font-family: 'Orbitron', sans-serif
    }

    .step.done {
      background: var(--blue);
      color: #fff
    }

    .step.active {
      background: linear-gradient(135deg, var(--peach), var(--peach2));
      color: #fff;
      box-shadow: 0 0 12px rgba(251, 159, 139, .5)
    }

    .step.next {
      background: rgba(255, 255, 255, .3);
      color: #aaa;
      border: 1px dashed #ccc
    }

    .step-line {
      width: 24px;
      height: 2px;
      background: rgba(255, 255, 255, .4)
    }

    /* ── MAIN LAYOUT ───────────────────────────────────── */
    .main {
      width: 100%;
      max-width: 1200px;
      display: grid;
      grid-template-columns: 380px 1fr;
      gap: 16px;
      align-items: stretch;
      height: calc(100vh - 130px);
    }

    /* ── PANEL ──────────────────────────────────────────── */
    .panel {
      background: var(--glass);
      backdrop-filter: blur(12px);
      border: 1px solid var(--glass-border);
      border-radius: 20px;
      padding: 20px;
      overflow-y: auto;
      scrollbar-width: thin;
      scrollbar-color: var(--blue) transparent;
      min-height: 0;  /* allow flex child to shrink within fixed height parent */
    }

    .panel::-webkit-scrollbar {
      width: 4px
    }

    .panel::-webkit-scrollbar-thumb {
      background: var(--blue);
      border-radius: 2px
    }

    .panel-title {
      font-family: 'Orbitron', sans-serif;
      font-size: .8rem;
      font-weight: 900;
      color: var(--dark);
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 14px;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .panel-title svg {
      width: 18px;
      height: 18px;
      color: var(--blue);
      flex-shrink: 0;
    }

    /* ── PHOTO GRID (Left Panel) ────────────────────────── */
    .photo-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-auto-rows: 150px;
      gap: 6px;
      overflow-y: auto;
      overflow-x: hidden;
      padding-right: 4px;
      align-content: start;
      flex: 1;         /* fill remaining space in flex-column left panel */
      min-height: 150px; /* always show at least 1 row */
    }

    .photo-grid::-webkit-scrollbar {
      width: 4px
    }

    .photo-grid::-webkit-scrollbar-thumb {
      background: var(--blue);
      border-radius: 2px
    }

    .photo-item {
      position: relative;
      border-radius: 8px;
      overflow: hidden;
      cursor: pointer;
      border: 2px solid transparent;
      transition: all .2s;
      width: 100%;
      height: 100%;
    }

    .photo-item img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .photo-item.selected {
      border-color: var(--peach);
      box-shadow: 0 0 0 2px var(--peach);
    }

    .photo-item .check {
      position: absolute;
      top: 5px;
      right: 5px;
      width: 20px;
      height: 20px;
      background: var(--peach);
      border-radius: 50%;
      display: none;
      align-items: center;
      justify-content: center;
      color: #fff;
      font-size: .7rem;
    }

    .photo-item.selected .check {
      display: flex
    }

    .photo-item:hover {
      transform: scale(1.03);
      border-color: var(--blue)
    }

    /* ── RIGHT PANEL SCROLLABLE ─────────────────────────── */
    /* Both panels now scroll via .panel — panel-right inherits from panel */
    .panel-right {
      /* no extra styles needed; inherits .panel scroll */
    }

    /* ── CONFIG SECTIONS ────────────────────────────────── */
    .config-section {
      margin-bottom: 14px
    }

    .config-label {
      font-size: .72rem;
      font-weight: 700;
      color: var(--dark);
      margin-bottom: 6px;
      display: block;
    }

    .api-input {
      width: 100%;
      padding: 9px 14px;
      border: 1.5px solid rgba(0, 191, 255, .3);
      border-radius: 12px;
      background: rgba(255, 255, 255, .5);
      font-size: .78rem;
      color: var(--dark);
      outline: none;
      transition: border .2s;
      font-family: 'Roboto Mono', monospace;
    }

    .api-input:focus {
      border-color: var(--blue);
      background: rgba(255, 255, 255, .8)
    }

    .api-input::placeholder {
      color: #aaa
    }

    /* ── CATEGORY LABEL ─────────────────────────────────── */
    .category-label {
      font-size: .6rem;
      font-weight: 700;
      color: var(--blue2);
      text-transform: uppercase;
      letter-spacing: 2px;
      margin-bottom: 5px;
      margin-top: 10px;
      display: block;
      opacity: .75;
    }

    /* ── PRESET CHIPS ───────────────────────────────────── */
    .preset-row {
      display: flex;
      flex-wrap: wrap;
      gap: 5px;
      margin-bottom: 4px;
    }

    .preset {
      padding: 4px 10px;
      border-radius: 20px;
      border: 1.5px solid rgba(0, 191, 255, .4);
      background: rgba(255, 255, 255, .4);
      font-size: .68rem;
      cursor: pointer;
      transition: all .2s;
      color: var(--dark);
    }

    .preset:hover,
    .preset.active {
      background: var(--blue);
      color: #fff;
      border-color: var(--blue);
    }

    /* ── PROMPT TEXTAREA ────────────────────────────────── */
    textarea.prompt-box {
      width: 100%;
      padding: 10px 14px;
      border: 1.5px solid rgba(0, 191, 255, .3);
      border-radius: 12px;
      background: rgba(255, 255, 255, .5);
      font-size: .8rem;
      color: var(--dark);
      outline: none;
      resize: vertical;
      min-height: 72px;
      transition: border .2s;
      font-family: 'Roboto Condensed', sans-serif;
    }

    textarea.prompt-box:focus {
      border-color: var(--blue);
      background: rgba(255, 255, 255, .8)
    }

    /* ── RESULT AREA ────────────────────────────────────── */
    .result-area {
      min-height: 180px;
      border-radius: 14px;
      border: 2px dashed rgba(0, 191, 255, .3);
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      position: relative;
      background: rgba(255, 255, 255, .15);
      margin-bottom: 12px;
      transition: border .3s;
    }

    .result-area.has-image {
      border-style: solid;
      border-color: var(--blue)
    }

    .result-area img {
      width: 100%;
      height: 100%;
      object-fit: contain
    }

    .result-placeholder {
      text-align: center;
      color: #aaa;
      padding: 24px
    }

    .result-placeholder svg {
      width: 44px;
      height: 44px;
      opacity: .4;
      margin-bottom: 8px
    }

    .result-placeholder p {
      font-size: .78rem
    }

    /* ── LOADING OVERLAY ────────────────────────────────── */
    .loading-overlay {
      position: absolute;
      inset: 0;
      background: rgba(27, 54, 93, .85);
      display: none;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      border-radius: 12px;
      gap: 12px;
    }

    .loading-overlay.active {
      display: flex
    }

    .spinner {
      width: 44px;
      height: 44px;
      border: 3px solid rgba(255, 255, 255, .2);
      border-top-color: var(--blue);
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    .loading-text {
      color: #fff;
      font-family: 'Orbitron', sans-serif;
      font-size: .7rem;
      letter-spacing: 2px
    }

    @keyframes spin {
      to {
        transform: rotate(360deg)
      }
    }

    /* ── BUTTONS ────────────────────────────────────────── */
    .btn {
      padding: 10px 18px;
      border: none;
      border-radius: 25px;
      font-family: 'Orbitron', sans-serif;
      font-size: .62rem;
      font-weight: 700;
      cursor: pointer;
      transition: all .25s;
      text-transform: uppercase;
      letter-spacing: 1px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 7px;
    }

    .btn svg {
      width: 1em;
      height: 1em
    }

    .btn-generate {
      background: linear-gradient(135deg, var(--peach), var(--peach2));
      color: #fff;
      box-shadow: 0 4px 15px rgba(251, 159, 139, .4);
      width: 100%;
      margin-bottom: 8px;
    }

    .btn-generate:hover:not(:disabled) {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(251, 159, 139, .5)
    }

    .btn-generate:disabled {
      opacity: .5;
      cursor: not-allowed
    }

    .btn-save {
      background: linear-gradient(135deg, #4CAF50, #00BFFF);
      color: #fff;
      box-shadow: 0 4px 15px rgba(0, 191, 255, .3);
      flex: 1;
    }

    .btn-save:hover:not(:disabled) {
      transform: translateY(-2px)
    }

    .btn-save:disabled {
      opacity: .4;
      cursor: not-allowed
    }

    .btn-skip {
      background: rgba(255, 255, 255, .35);
      color: var(--dark);
      border: 1.5px solid rgba(0, 191, 255, .3);
    }

    .btn-skip:hover {
      background: rgba(255, 255, 255, .6)
    }

    .action-row {
      display: flex;
      gap: 8px;
      align-items: stretch
    }

    /* ── ALERTS ─────────────────────────────────────────── */
    .alert {
      padding: 9px 14px;
      border-radius: 10px;
      font-size: .78rem;
      margin-top: 8px;
      display: none;
    }

    .alert.show {
      display: block
    }

    .alert-error {
      background: rgba(255, 80, 80, .15);
      color: #c0392b;
      border: 1px solid rgba(255, 80, 80, .3)
    }

    .alert-success {
      background: rgba(76, 175, 80, .15);
      color: #27ae60;
      border: 1px solid rgba(76, 175, 80, .3)
    }

    /* ── SELECT HINT ────────────────────────────────────── */
    .select-hint {
      font-size: .72rem;
      color: #888;
      margin-bottom: 10px;
      background: rgba(255, 255, 255, .3);
      padding: 7px 12px;
      border-radius: 8px;
    }

    /* ── COMPARE AREA (Preview + AI Result) ────────────── */
    .compare-wrap {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 8px;
      margin-top: 12px;
    }

    .compare-box {
      position: relative;
      border-radius: 12px;
      overflow: hidden;
      background: rgba(255, 255, 255, .15);
      border: 2px dashed rgba(0, 191, 255, .3);
      min-height: 120px;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-direction: column;
      gap: 4px;
      transition: border .3s;
    }

    .compare-box.has-image {
      border-style: solid;
      border-color: var(--blue);
    }

    .compare-box img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      display: block;
      position: absolute;
      inset: 0;
    }

    .compare-label {
      font-size: .58rem;
      font-weight: 700;
      font-family: 'Orbitron', sans-serif;
      color: var(--dark);
      letter-spacing: 1px;
      text-transform: uppercase;
      opacity: .7;
      position: relative;
      z-index: 1;
    }

    .compare-placeholder svg {
      width: 28px;
      height: 28px;
      opacity: .3;
      position: relative;
    }

    /* ── RESPONSIVE: TABLET (768px – 1024px) ────────────── */
    @media (max-width: 1024px) {
      .main {
        grid-template-columns: 1fr 1fr;
        max-width: 100%;
        height: calc(100vh - 120px);
      }

      .photo-grid {
        grid-template-columns: repeat(3, 1fr);
      }
    }

    /* ── RESPONSIVE: MOBILE (<768px) ────────────────────── */
    @media (max-width: 767px) {
      body {
        padding: 12px
      }

      .top-bar {
        padding: 10px 14px;
        border-radius: 12px;
        margin-bottom: 12px;
      }

      .logo {
        font-size: .7rem
      }

      .logo span {
        font-size: .5rem
      }

      .step {
        width: 24px;
        height: 24px;
        font-size: .55rem
      }

      .step-line {
        width: 16px
      }

      .main {
        grid-template-columns: 1fr;
        height: auto;
        gap: 12px;
      }

      /* On mobile both panels auto-height, no viewport constraint */
      .panel {
        overflow-y: visible;
        height: auto;
        padding: 14px;
      }

      .photo-grid {
        grid-template-columns: repeat(4, 1fr);
        max-height: 180px;
        overflow-y: auto;
        gap: 6px;
      }

      .preset {
        font-size: .65rem;
        padding: 4px 9px
      }

      .btn {
        font-size: .58rem;
        padding: 9px 14px
      }

      .action-row {
        flex-direction: row
      }

      textarea.prompt-box {
        min-height: 60px
      }
    }

    /* ── RESPONSIVE: SMALL MOBILE (<420px) ──────────────── */
    @media (max-width: 420px) {
      .photo-grid {
        grid-template-columns: repeat(3, 1fr)
      }

      .preset {
        font-size: .62rem
      }

      .btn-generate {
        font-size: .56rem
      }
    }
  </style>
</head>

<body>

  <!-- TOP BAR -->
  <div class="top-bar">
    <div class="logo">PHOTOBOOTH AIRWAYS<span>AI ENHANCE STUDIO</span></div>
    <div class="step-badge">
      <div class="step done">1</div>
      <div class="step-line"></div>
      <div class="step active" title="AI Enhance">AI</div>
      <div class="step-line"></div>
      <div class="step next">3</div>
      <div class="step-line"></div>
      <div class="step next">4</div>
    </div>
  </div>

  <div class="main">

    <!-- LEFT: PHOTO PICKER + PREVIEW + AI RESULT -->
    <div class="panel" style="display:flex;flex-direction:column;gap:0">
      <div class="panel-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <rect x="3" y="3" width="18" height="18" rx="2" />
          <circle cx="8.5" cy="8.5" r="1.5" />
          <polyline points="21 15 16 10 5 21" />
        </svg>
        Pilih Foto untuk di-Enhance
      </div>

      <!-- COMPARE: Original Preview + AI Result (di atas grid) -->
      <div class="compare-wrap" style="margin-bottom:12px">
        <!-- Original Selected -->
        <div>
          <label class="config-label" style="margin-bottom:4px">📷 Foto Dipilih</label>
          <div class="compare-box" id="preview-original">
            <div class="compare-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="3" y="3" width="18" height="18" rx="2" />
                <circle cx="8.5" cy="8.5" r="1.5" />
                <polyline points="21 15 16 10 5 21" />
              </svg>
              <span class="compare-label">Pilih foto</span>
            </div>
            <img id="preview-original-img" src="" alt="Selected" style="display:none">
          </div>
        </div>
        <!-- AI Result -->
        <div>
          <label class="config-label" style="margin-bottom:4px">✨ Hasil AI</label>
          <div class="compare-box" id="result-area">
            <div class="loading-overlay" id="loading">
              <div class="spinner"></div>
              <div class="loading-text">AI...</div>
            </div>
            <div class="compare-placeholder" id="result-placeholder">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
              </svg>
              <span class="compare-label">Generate</span>
            </div>
            <img id="result-img" src="" alt="AI Result" style="display:none">
          </div>
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="action-row" style="margin-bottom:12px">
        <button class="btn btn-save" id="btn-save" onclick="saveAndContinue()" disabled>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="20 6 9 17 4 12" />
          </svg>
          Pakai & Lanjut
        </button>
        <button class="btn btn-skip" onclick="skipAi()">Lewati AI →</button>
      </div>
      <div id="alert-box" class="alert"></div>

      <!-- PHOTO GRID (di bawah preview) -->
      <p class="select-hint" style="margin-top:1px">Klik foto untuk memilihnya, lalu tekan Generate di panel kanan.</p>
      <div class="photo-grid" id="photo-grid">
        <?php foreach ($data['photos'] as $photo): ?>
          <div class="photo-item" data-path="<?= htmlspecialchars($photo->file_path) ?>" onclick="selectPhoto(this)">
            <img src="<?= URLROOT . htmlspecialchars($photo->file_path) ?>" alt="Session Photo">
            <div class="check">✓</div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- RIGHT: CONTROLS ONLY -->
    <div class="panel panel-right" style="display:flex;flex-direction:column;gap:0">
      <div class="panel-title">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M12 2L2 7l10 5 10-5-10-5z" />
          <path d="M2 17l10 5 10-5" />
          <path d="M2 12l10 5 10-5" />
        </svg>
        <?= $data['provider'] === 'GEMINI' ? 'Google Gemini AI' : 'Replicate AI' ?> Generator
      </div>

      <!-- API KEY STATUS -->
      <div class="config-section">
        <?php if ($data['provider'] === 'GEMINI'): ?>
          <div
            style="display:flex;align-items:center;gap:8px;background:rgba(0,127,255,.1);border:1px solid rgba(0,127,255,.3);border-radius:10px;padding:9px 14px;font-size:.78rem;color:var(--blue2)">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12" />
            </svg>
            Google Project: <code><?= GOOGLE_CLOUD_PROJECT_ID ?></code>
          </div>
        <?php elseif ($data['api_key_set']): ?>
          <div
            style="display:flex;align-items:center;gap:8px;background:rgba(76,175,80,.15);border:1px solid rgba(76,175,80,.3);border-radius:10px;padding:9px 14px;font-size:.78rem;color:#27ae60">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <polyline points="20 6 9 17 4 12" />
            </svg>
            Replicate API Token terkonfigurasi
          </div>
        <?php else: ?>
          <div
            style="display:flex;align-items:center;gap:8px;background:rgba(255,80,80,.12);border:1px solid rgba(255,80,80,.3);border-radius:10px;padding:9px 14px;font-size:.78rem;color:#c0392b">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" />
              <line x1="12" y1="8" x2="12" y2="12" />
              <line x1="12" y1="16" x2="12" y2="16" />
            </svg>
            API Token belum diset! Edit <code>config.php</code>
          </div>
        <?php endif; ?>
      </div>

      <!-- PRESETS -->
      <div class="config-section">
        <label class="config-label">⚡ Preset Prompt (Gemini Enhanced)</label>

        <span class="category-label">🔥 Paling Viral</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Transform this photo into a breathtaking Studio Ghibli animated film scene. Render the subjects with soft, warm hand-painted Ghibli-style illustration — smooth rosy skin, large gentle eyes, and naturally flowing hair. The background should be a lush, painterly Japanese countryside with rolling green hills, a clear blue sky with soft white clouds, wildflowers, and a distant small village. Warm golden afternoon sunlight bathes the entire scene.')">
            🌿 Studio Ghibli</div>
          <div class="preset"
            onclick="setPreset(this,'Create a stunning cinematic Hollywood movie poster look. Apply dramatic professional color grading — deep rich shadows, bright highlights, and a warm orange-teal color grade. Make the subjects look like the lead actors of a blockbuster film. Add subtle lens flare and depth of field blur. The background should be an epic, dramatic cityscape at golden hour or a breathtaking mountain vista at sunset.')">
            🎬 Sinema Hollywood</div>
          <div class="preset"
            onclick="setPreset(this,'Transform into a Marvel superhero epic. Give the subjects powerful, heroic poses and expressions. Apply dramatic comic-book style shading and vibrant saturated colors to faces and skin. Add a glowing energy aura around the subjects in electric blue or red. The background should be a dynamic action scene with a crumbling city, dramatic storm clouds, and lightning.')">
            ⚡ Marvel Hero</div>
        </div>

        <span class="category-label">📸 Enhancement Foto</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Create a stunning professional LinkedIn profile headshot. Apply flawless airbrushed skin retouching — smooth even skin tone, bright clear eyes, and a confident natural smile. Use professional softbox lighting with a soft shadow on one side. Style the subjects to look polished and well-dressed. Change the background to a clean, modern office bokeh or a plain gray/navy professional backdrop.')">
            💼 LinkedIn Pro</div>
          <div class="preset"
            onclick="setPreset(this,'Apply the most popular Instagram golden hour preset. Flood the entire scene with warm, rich golden sunlight as if shot during magic hour. Give the subjects a radiant, sun-kissed skin glow, glowing hair from backlighting, and bright dewy eyes. Enhance colors to be vivid and lush. Background should be a beautiful outdoor scene — a beach, rooftop, or garden — bathed in gorgeous warm light.')">
            🌅 Instagram Glow</div>
          <div class="preset"
            onclick="setPreset(this,'Transform into an elegant wedding or pre-wedding portrait. Apply soft, romantic film photography look with creamy pastel tones and gentle light leaks. Give the subjects a beautiful bridal glow — flawless porcelain skin, soft makeup, and romantic flowing hair. The background should be a stunning garden venue with blooming roses, soft bokeh fairy lights, and a dreamy soft-focus backdrop.')">
            💍 Wedding Dream</div>
        </div>

        <span class="category-label">🎨 Gaya Seni</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Paint this photo as a magnificent royal oil portrait in the style of 18th century European nobility. Give the subjects regal, dignified expressions. Render faces with soft luminous skin and rich detailed textures. Dress the subjects in opulent royal velvet attire with gold embellishments. The background should be a grand palace interior with heavy draped curtains in deep red and gold, and an ornate gilded frame visible at the edges.')">
            👑 Royal Portrait</div>
          <div class="preset"
            onclick="setPreset(this,'Transform into a beautiful Korean drama (K-drama) scene. Apply the signature K-drama visual filter — soft pastel tones, milky dewy skin, bright natural eye makeup, and perfectly styled straight black hair. Make the subjects look like lead K-drama actors. The background should be a romantic cherry blossom park in spring with soft pink petals falling, warm afternoon sun, and a blurred romantic Seoul cityscape.')">
            🌸 K-Drama</div>
          <div class="preset"
            onclick="setPreset(this,'Recreate this photo as a Disney Pixar animated movie character. Transform the subjects into charming Pixar-style 3D animated characters — large expressive eyes, smooth rounded features, and exaggerated friendly expressions. The style should match high-quality Pixar renders with beautiful subsurface skin scattering and soft studio lighting. The background should be a colorful, magical Pixar movie world.')">
            🎠 Disney Pixar</div>
        </div>

        <span class="category-label">✨ Fantasi & Estetika</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects into powerful dark fantasy warrior characters. Apply dramatic battle-worn makeup, glowing magical eyes, and epic armor costumes with glowing runes. Add dramatic moody lighting from below. The background should be a cinematic dark fantasy world — a dramatic castle ruin under a blood-red full moon with swirling mist and magical particles floating in the air.')">
            🗡️ Dark Fantasy</div>
          <div class="preset"
            onclick="setPreset(this,'Apply a dreamy, ethereal soft aesthetic inspired by popular TikTok and Pinterest trends. Give all subjects a porcelain glass-skin glow, soft blush cheeks, delicate lip gloss, and glittery dewdrop highlights on the skin. Add floating sparkle particles and soft lens flares around the subjects. The background should be a magical soft-pink and white dreamy cloud world with floating flower petals and bokeh lights.')">
            🦋 Soft Aesthetic</div>
          <div class="preset"
            onclick="setPreset(this,'Transform into a futuristic cyberpunk neon world at night. Give the subjects glowing holographic tattoos on their face and neck, illuminated cybernetic eye implants, and dramatic neon-colored hair streaks. Apply striking dual-color rim lighting — electric cyan on one side, hot magenta on the other. The background is a rain-soaked neon-lit Tokyo megacity street with giant hologram advertisements.')">
            🏙️ Neo Tokyo</div>
        </div>
      </div>

      <!-- CUSTOM PROMPT -->
      <div class="config-section">
        <label class="config-label">💬 Custom Prompt</label>
        <textarea class="prompt-box" id="prompt"
          placeholder="Deskripsikan efek yang diinginkan..."><?= htmlspecialchars($data['default_prompt']) ?></textarea>
      </div>

      <!-- GENERATE BUTTON -->
      <button class="btn btn-generate" id="btn-generate" onclick="generate()" <?= $data['api_key_set'] ? '' : 'disabled title="Set REPLICATE_API_TOKEN di config.php"' ?>>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
        </svg>
        Generate AI Enhance
      </button>
    </div>
  </div>

  <script>
    const SESSION_ID = '<?= $data['session']->id ?>';
    const URLROOT = '<?= URLROOT ?>';
    let selectedPath = null;
    let savedPhotoId = null;
    let savedImagePath = null;
    let hasAiResult = false; // track if ANY generation has succeeded

    function selectPhoto(el) {
      document.querySelectorAll('.photo-item').forEach(p => p.classList.remove('selected'));
      el.classList.add('selected');
      selectedPath = el.dataset.path;
      updateGenerateBtn();
      // Update selected photo preview (do NOT reset AI result)
      const previewImg = document.getElementById('preview-original-img');
      const previewBox = document.getElementById('preview-original');
      const placeholder = previewBox.querySelector('.compare-placeholder');
      previewImg.src = URLROOT + selectedPath + '?t=' + Date.now();
      previewImg.style.display = 'block';
      previewBox.classList.add('has-image');
      if (placeholder) placeholder.style.display = 'none';
    }

    function updateGenerateBtn() {
      document.getElementById('btn-generate').disabled = !selectedPath;
      // btn-save only depends on whether we have a result, not on selectedPath
      document.getElementById('btn-save').disabled = !hasAiResult;
    }

    function setPreset(el, text) {
      document.querySelectorAll('.preset').forEach(p => p.classList.remove('active'));
      el.classList.add('active');
      document.getElementById('prompt').value = text;
    }

    function showAlert(msg, type) {
      const box = document.getElementById('alert-box');
      box.textContent = msg;
      box.className = 'alert show alert-' + type;
      setTimeout(() => box.classList.remove('show'), 5000);
    }

    async function generate() {
      const prompt = document.getElementById('prompt').value.trim();
      if (!selectedPath) return;

      document.getElementById('loading').classList.add('active');
      document.getElementById('btn-generate').disabled = true;
      document.getElementById('result-placeholder').style.display = 'none';
      document.getElementById('result-img').style.display = 'none';

      try {
        const res = await fetch(URLROOT + '/photo/ai-enhance-process', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ session_id: SESSION_ID, photo_path: selectedPath, prompt })
        });
        const data = await res.json();

        if (data.success) {
          const img = document.getElementById('result-img');
          img.src = URLROOT + data.image_path + '?t=' + Date.now();
          img.style.display = 'block';
          document.getElementById('result-area').classList.add('has-image');
          document.getElementById('result-placeholder').style.display = 'none';
          document.getElementById('btn-save').disabled = false;
          hasAiResult = true;
          savedPhotoId = data.photo_id;
          savedImagePath = data.image_path;
          if (data.text_response) showAlert('✨ ' + data.text_response, 'success');

          // --- AUTO-ADD to photo grid (no reload needed) ---
          const grid = document.getElementById('photo-grid');
          const newItem = document.createElement('div');
          newItem.className = 'photo-item';
          newItem.dataset.path = data.image_path;
          newItem.onclick = function () { selectPhoto(this); };
          newItem.innerHTML = `
            <img src="${URLROOT + data.image_path}?t=${Date.now()}" alt="AI Result" loading="lazy">
            <div class="check">✓</div>
            <div style="position:absolute;bottom:3px;left:3px;background:rgba(0,191,255,.85);color:#fff;font-size:.55rem;padding:2px 5px;border-radius:4px;font-weight:700">✨ AI</div>
          `;
          // Prepend so the new photo appears at the top
          grid.prepend(newItem);
          // Pulse animation only — do NOT call selectPhoto (would overwrite preview)
          newItem.style.transition = 'box-shadow .4s';
          newItem.style.boxShadow = '0 0 0 3px #00BFFF, 0 0 20px rgba(0,191,255,.6)';
          setTimeout(() => { newItem.style.boxShadow = ''; }, 2000);
          // Mark as selected in grid visually
          document.querySelectorAll('.photo-item').forEach(p => p.classList.remove('selected'));
          newItem.classList.add('selected');
        } else {
          showAlert('❌ ' + (data.message || 'Gagal generate'), 'error');
        }
      } catch (e) {
        document.getElementById('result-placeholder').style.display = 'flex';
        showAlert('❌ Error: ' + e.message, 'error');
      } finally {
        document.getElementById('loading').classList.remove('active');
        updateGenerateBtn();
      }
    }

    function saveAndContinue() {
      window.location.href = URLROOT + '/photo/layout/' + SESSION_ID;
    }

    function skipAi() {
      window.location.href = URLROOT + '/photo/layout/' + SESSION_ID;
    }
  </script>
</body>

</html>