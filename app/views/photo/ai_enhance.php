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
      min-height: 0;
      /* allow flex child to shrink within fixed height parent */
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
      flex: 1;
      /* fill remaining space in flex-column left panel */
      min-height: 150px;
      /* always show at least 1 row */
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

        <!-- ── BACKGROUND ONLY ── -->
        <span class="category-label">🌍 Ganti Background Saja</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Keep the subjects\' faces, facial features, expressions, and gestures 100% identical to the original. Do NOT change their pose or identity. Allow their skin tone to adapt naturally to match the warm golden Ghibli lighting. Replace only the background with a breathtaking Studio Ghibli-style hand-painted landscape: lush rolling green hills, a winding crystal river, vibrant wildflowers, red-roofed village in the distance, and warm golden-hour light through wispy clouds.')">
            🌿 Studio Ghibli</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve the subjects\' faces, facial features, expressions, and gestures exactly. Zero changes to their pose or identity. Allow their skin tone to adapt naturally to look soft and luminous in the spring sakura light. Swap only the background with a stunning pink cherry blossom park: thousands of pale sakura petals falling softly, a stone path flanked by blooming trees, and warm spring bokeh light filtering through the branches.')">
            🌸 Taman Sakura</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT change the faces, facial features, expressions, or gestures of the subjects. Allow their skin tone to adapt to look warm and sun-kissed matching the tropical Bali sunset atmosphere. Replace only the background with a Bali tropical paradise: a luxury infinity pool overlooking lush green rice terraces, with a dramatic orange-pink sunset sky and palm trees silhouetted against the horizon.')">
            🏝️ Bali Rice Terrace</div>
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures completely unchanged. Allow skin tone to adapt to match the warm golden-hour amber light of the NYC sunset. Change only the background to a cinematic New York City rooftop at golden hour: Manhattan skyline glowing warm amber, city lights twinkling in the distance, and slight bokeh blur on the buildings.')">
            🗽 NYC Rooftop</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT alter faces, facial features, expressions, or gestures. Allow skin tone to adapt to take on a cool neon-lit hue reflecting the cyan and magenta Tokyo night lights. Replace only the background with Tokyo Shibuya crossing at night: towering neon signs in Japanese kanji, rain-slicked streets reflecting colorful neon lights, and a vibrant electric nightlife atmosphere.')">
            🏙️ Tokyo Shibuya</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT change faces, facial features, expressions, or gestures. Allow skin tone to adapt to glow softly in the warm romantic Parisian afternoon light. Replace only the background with a stunning Parisian street scene: the Eiffel Tower in the distance, cobblestone streets, French café terraces, flower baskets, and a soft romantic bokeh background.')">
            🗼 Paris Eiffel Tower</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve faces, facial features, expressions, and gestures exactly. Allow skin tone to adapt to look warm and sun-kissed, glowing in the rich golden sunset light. Replace only the background with a magical tropical sunset beach: golden sand, crystal-clear turquoise waves, a vivid orange-pink-purple sunset sky, and warm golden light bathing the entire scene.')">
            🌊 Golden Sunset Beach</div>
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures unchanged. Allow skin tone to adapt to appear cool, pale, and luminous under the ethereal green and purple aurora glow. Change only the background to a Northern Lights aurora borealis scene in Iceland: a snow-covered landscape under a spectacular aurora dancing across a star-filled sky, with a frozen lake reflection below.')">
            🌌 Aurora Iceland</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT change faces, facial features, expressions, or gestures. Allow skin tone to adapt to look radiant and healthy under the bright tropical Maldivian noon sun. Replace only the background with a Maldives overwater bungalow resort: calm turquoise waters, white sand, vibrant coral reef visible below, and a cloudless brilliant blue sky overhead.')">
            🌴 Maldives Resort</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT alter faces, facial features, expressions, or gestures. Allow skin tone to adapt to take on a soft ethereal glow matching the bioluminescent moonlit forest atmosphere. Replace only the background with a magical enchanted forest at night: ancient trees with glowing bioluminescent mushrooms, floating orbs of light, beams of moonlight through the canopy, and a mystical misty forest floor.')">
            🧚 Enchanted Forest</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve faces, facial features, expressions, and gestures completely. Allow skin tone to adapt to reflect the golden-white light of the space station, appearing luminous against the dark cosmos. Replace only the background with an outer space view from a space station window: Earth\'s blue curve below, the Milky Way visible, golden sunlight streaming from the side.')">
            🚀 Outer Space</div>
          <div class="preset"
            onclick="setPreset(this,'Do NOT alter faces, facial features, expressions, or gestures. Allow skin tone to adapt to glow warmly in the golden autumn afternoon light. Replace only the background with a breathtaking peak-autumn forest: a canopy of blazing orange, red, and gold maple leaves, a leaf-covered forest path, golden afternoon sunlight streaming through the trees, and soft misty atmosphere.')">
            🍂 Autumn Forest</div>
        </div>

        <!-- ── OUTFIT / COSTUME ONLY ── -->
        <span class="category-label">👗 Ganti Baju / Kostum Saja</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures EXACTLY as in the original. Do NOT change their pose. Keep the background unchanged. Allow skin tone to adapt to complement the warm earthy soga-brown and indigo batik palette. Change only their clothing to elegant traditional Indonesian batik formal attire — beautiful hand-drawn batik pattern, a matching kebaya or formal batik shirt, and a neat traditional headband for male subjects.')">
            🎎 Batik Formal</div>
          <div class="preset"
            onclick="setPreset(this,'Faces, facial features, expressions, and gestures must remain 100% identical. Do NOT change the background or pose. Allow skin tone to adapt to match the refined golden candlelight of a Javanese royal ceremony. Replace only their clothing with traditional Javanese royal wedding attire: gold-embroidered dark green or maroon beskap for males and an elaborate kebaya with silk jarik batik skirt for females, with golden accessories.')">
            👑 Pakaian Keraton</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve faces, facial features, expressions, and gestures exactly. Do NOT touch the background or change their pose. Allow skin tone to adapt to look polished and healthy under professional studio lighting. Change only outfits to sleek modern business professional attire: a tailored navy or charcoal suit with crisp white shirt and silk tie for males; an elegant blazer with a professional blouse for females.')">
            💼 Business Formal</div>
          <div class="preset"
            onclick="setPreset(this,'Keep all faces, facial features, expressions, and gestures completely unchanged. Do NOT alter the background or pose. Allow skin tone to adapt to look fresh and vibrant matching the energetic urban streetwear aesthetic. Change only clothing to a chic streetwear fashion look: oversized branded hoodies or stylish graphic tees, baggy trendy cargo pants, and fashionable sneakers — a modern urban Gen-Z look.')">
            🧢 Streetwear Urban</div>
          <div class="preset"
            onclick="setPreset(this,'Faces, facial features, expressions, and gestures must be perfectly preserved. Do NOT change the background or pose. Allow skin tone to adapt to appear smooth and porcelain-like, complementing the delicate silk kimono colors. Replace only outfits with traditional Japanese kimono: a gorgeous silk kimono with intricate floral pattern in red and gold for females, a formal black haori jacket with grey kimono for males, and traditional wooden sandals.')">
            🌸 Japanese Kimono</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve faces, facial features, expressions, and gestures exactly. Do NOT change the background or pose. Allow skin tone to adapt to appear clear and luminous, matching the elegant hanbok jewel tones. Dress only the subjects in stylish traditional Korean hanbok: a bright jewel-toned jeogori with a full flowing chima skirt for females in soft pink or mint; a pale blue durumagi overcoat with matching baji pants for males.')">
            🎋 Korean Hanbok</div>
          <div class="preset"
            onclick="setPreset(this,'Faces, facial features, expressions, and gestures must remain completely untouched. Do NOT modify the background or pose. Allow skin tone to adapt to look dramatic and cinematic, complementing the rich velvet and gold medieval costume palette. Transform only clothing into glamorous royal medieval fantasy costumes: an opulent velvet gown with gold brocade and jeweled crown for females; a regal knight armor or royal cape with ornate gold trim for males.')">
            ⚔️ Royal Medieval</div>
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures exactly as in the original. Do NOT change the background or pose. Allow skin tone to adapt to look radiant and stage-ready under K-pop spotlight lighting, with a healthy dewy glow. Replace only outfits with modern K-pop idol stage costumes: dazzling holographic or metallic fabric outfits with bold cuts, sparkling sequin embellishments, and on-trend idol styling.')">
            🎤 K-Pop Idol</div>
        </div>

        <!-- ── BACKGROUND + OUTFIT ── -->
        <span class="category-label">🎭 Ganti Background + Baju Sekaligus</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures EXACTLY as in the original. Do NOT change their pose or identity. Allow skin tone to adapt to glow warmly under traditional ceremonial candlelight. Change BOTH clothing AND background. Dress subjects in traditional Indonesian wedding attire (kebaya and batik for females, beskap for males). Place them against a royal Javanese palace garden with lush greenery, ornate golden decorations, and warm ceremonial lighting.')">
            💍 Wedding Tradisional</div>
          <div class="preset"
            onclick="setPreset(this,'Preserve faces, facial features, expressions, and gestures perfectly. Zero changes to their pose. Allow skin tone to adapt to appear cool and luminous under the clinical white LED lighting of a space station. Change BOTH clothing AND background. Dress subjects in futuristic sci-fi spacesuits or sleek metallic jumpsuits, against a stunning outer space background — a high-tech space station with Earth visible through a large viewport window.')">
            🚀 Astronaut Sci-Fi</div>
          <div class="preset"
            onclick="setPreset(this,'Faces, facial features, expressions, and gestures must remain 100% identical to the original. Do NOT change their pose. Allow skin tone to adapt to appear soft and porcelain-smooth, matching the delicate sakura spring palette. Change BOTH clothing AND background. Dress subjects in elegant traditional Japanese kimono with floral patterns. Place them in a breathtaking cherry blossom garden: sakura petals falling softly, stone lanterns, a wooden bridge over a koi pond, and warm golden-hour sunlight.')">
            ⛩️ Kimono & Sakura</div>
          <div class="preset"
            onclick="setPreset(this,'Keep faces, facial features, expressions, and gestures completely unchanged. Do NOT change their pose. Allow skin tone to adapt to look polished and professional, complementing the modern city evening lighting. Change BOTH clothing AND background. Dress subjects in stylish smart-casual professional attire. Place them against a high-end modern cityscape — a luxury glass-and-steel office tower lobby or a rooftop terrace overlooking a gleaming downtown city skyline at dusk.')">
            🌆 City Professional</div>
        </div>

        <!-- ── MODIFIKASI WAJAH SAJA ── -->
        <span class="category-label">😎 Modifikasi Wajah Saja</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects\' faces, facial features, and expressions into a high-quality 2D Japanese anime style. Keep their original gestures, pose, clothing, and background EXACTLY the same. Do NOT change their pose. Allow skin tone to adapt to a cel-shaded anime aesthetic.')">
            🎨 Anime Style</div>
          <div class="preset"
            onclick="setPreset(this,'Change the subjects\' faces and facial features into a 3D Pixar/Disney style cartoon. Keep their original gestures, pose, clothing, and background completely unchanged. Do NOT alter their pose. Allow skin tone to adapt to a smooth, vibrant 3D animated look.')">
            🎬 3D Pixar Cartoon</div>
          <div class="preset"
            onclick="setPreset(this,'Modify the subjects\' faces to make them look 40 years older, adding realistic wrinkles, silver/gray hair, and textured skin. Keep their original gestures, pose, clothing, and background completely unchanged. Do NOT change their pose.')">
            🧓 Aging (Tua)</div>
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects\' faces by adding sleek metallic cyberpunk cybernetic implants, glowing neon optics, and futuristic facial plating. Keep their original gestures, pose, clothing, and background exactly the same. Do NOT change their pose.')">
            🤖 Cyberpunk Cyborg</div>
        </div>

        <!-- ── MODIFIKASI WAJAH + BACKGROUND ── -->
        <span class="category-label">🎭 Modifikasi Wajah + Background</span>
        <div class="preset-row">
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects into comic book superheroes with stylized heroic facial features or cool domino masks. Change the background to a dramatic, action-packed cinematic city skyline with explosions. Keep their original gestures, pose, and clothing completely unchanged. Do NOT change their pose.')">
            🦸‍♂️ Superhero Comic</div>
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects\' faces into terrifying cinematic zombies with pale decaying skin and glowing eyes. Change the background to a dark, ruined post-apocalyptic city street with fog. Keep their original gestures, pose, and clothing completely unchanged. Do NOT alter their pose.')">
            🧟 Zombie Apocalypse</div>
          <div class="preset"
            onclick="setPreset(this,'Change the subjects\' faces to look like elegant, dangerous vampires with pale skin, sharp fangs, and piercing red eyes. Change the background to a dark, gothic medieval castle interior lit by candles. Keep their original gestures, pose, and clothing completely unchanged. Do NOT change their pose.')">
            🦇 Vampire Noble</div>
          <div class="preset"
            onclick="setPreset(this,'Transform the subjects\' faces into a classic 16th-century Renaissance oil painting style with visible brushstrokes. Change the background to a dark, moody classic portrait studio with dramatic chiaroscuro lighting. Keep their original gestures, pose, and clothing completely unchanged. Do NOT alter their pose.')">
            🖼️ Renaissance Painting</div>
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