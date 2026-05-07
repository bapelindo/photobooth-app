<?php

// Application Configuration
// pip install Pillow pywin32
// install imagick
// install Sony Remote SDK
// install webshocket
// install midtrans-php
// install phpmailer
// install composer
// ngrok http 80
// php scripts/email_worker.php

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app'); // -> .../photobooth-app/app

// URL Root
// Detect environment and set URLROOT accordingly
// For Docker: check if running in container (DOCUMENT_ROOT points to /var/www/html/public)
$isDocker = isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['DOCUMENT_ROOT'], '/var/www/html/public') !== false;

if ($isDocker || strpos($_SERVER['HTTP_HOST'], 'localhost') !== false || strpos($_SERVER['HTTP_HOST'], '127.0.0.1') !== false) {
    // Docker or local development - no subdirectory needed
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    define('URLROOT', $protocol . '://' . $_SERVER['HTTP_HOST']);
} else {
    // Production
    define('URLROOT', 'https://photobooth.bapel.my.id');
}

// Site Name
define('SITENAME', 'Photobooth App');

// Live View WebSocket URL
define('LIVE_VIEW_WEBSOCKET_URL', 'ws://localhost:8765');


// --- PENGATURAN EMAIL (SMTP) ---
// Ganti nilai di bawah ini dengan detail Anda

// Alamat server SMTP Anda
define('SMTP_HOST', 'smtp.gmail.com');

// Nama pengguna untuk login ke server SMTP (biasanya alamat email Anda)
define('SMTP_USERNAME', 'bapelhacker@gmail.com');

// Kata sandi untuk login ke server SMTP (gunakan App Password jika memakai Gmail)
define('SMTP_PASSWORD', 'iddc pzqs zgqx dzbp');

// Tipe enkripsi (biasanya 'tls' atau 'ssl')
define('SMTP_SECURE', 'tls');

// Port untuk koneksi SMTP
define('SMTP_PORT', 587);

// Alamat email pengirim
define('EMAIL_FROM_ADDRESS', 'bapelhacker@gmail.com');

// Nama pengirim
define('EMAIL_FROM_NAME', 'Photobooth App');

// Enable/Disable Session Refresh and Back Functionality
define('ENABLE_SESSION_REFRESH_BACK', false);

// Enable/Disable Payment Bypass (for testing only - set to false in production)
define('ENABLE_PAYMENT_BYPASS', true);

// --- PHOTOBOOTH SESSION SETTINGS ---
// Default session duration in seconds
define('DEFAULT_SESSION_DURATION', 300);

// Default maximum saved photos per session
define('DEFAULT_MAX_SAVE_PHOTOS', 20);

// Default frame limit per package
define('DEFAULT_FRAME_LIMIT', 2);

// Enable/Disable auto-print after session completion
define('AUTO_PRINT_ENABLED', true);

// Print queue processing interval (seconds)
define('PRINT_QUEUE_INTERVAL', 30);

// Print method: 'gdi' (Windows Printer Driver - Recommended) or 'raw' (Raw BMP data)
// Use 'gdi' for most printers including Epson L3110
// Use 'raw' if your printer supports raw BMP data
define('PRINT_METHOD', 'gdi');

// Maximum file size for photo uploads (bytes)
define('MAX_PHOTO_FILE_SIZE', 20971520); // 20MB

// Supported image formats
define('SUPPORTED_IMAGE_FORMATS', ['jpg', 'jpeg', 'png', 'gif']);

// Photo quality settings (1-100, higher = better quality)
define('PHOTO_QUALITY', 100);

// Thumbnail size for gallery displays
define('THUMBNAIL_WIDTH', 150);
define('THUMBNAIL_HEIGHT', 150);

// --- GOOGLE CLOUD SETTINGS ---
define('GOOGLE_CLOUD_PROJECT_ID', 'still-summit-495602-v8');
define('GOOGLE_CLOUD_LOCATION', 'us-central1'); // Default location for Vertex AI
define('AI_PROVIDER', 'GEMINI'); // Choices: 'REPLICATE' or 'GEMINI'
define('GEMINI_MODEL', 'gemini-2.5-flash-image'); // Code name: nano banana 2

// Parameter Generasi AI (Gemini)
// Temperature: 0.0 (konsisten) hingga 1.0 (kreatif). Untuk photo enhancement, 0.3 - 0.5 cukup baik.
define('GEMINI_TEMPERATURE', 0.5);
define('GEMINI_TOP_K', 32);
define('GEMINI_TOP_P', 1.0);
define('GEMINI_MAX_TOKENS', 2048);

// --- REPLICATE AI SETTINGS ---
// Dapatkan API Token di https://replicate.com/account/api-tokens
define('REPLICATE_API_TOKEN', 'r8_dAuspDDjH4ZIKtHLowDJLNLSVDIXpHT3ex9MW');

// Model yang digunakan - HARUS yang mendukung img2img (input gambar)
// JANGAN gunakan flux-2-pro / flux-1-pro / sdxl-lightning karena itu hanya text-to-image!
// Model img2img yang benar (gunakan VERSION HASH, bukan nama owner/model):
//   SD 2.1 img2img (RECOMMENDED): '15a3689ee13b0d2616e98820eca31d4c3abcd36672df6afce5cb6feb1d66087d'
//   SD 1.5 img2img (alternatif) : '9a9b6aa5ac2793993aaaff48fd0e05fc5be213bc85a0bafd24e578d3bb81e628'
define('REPLICATE_MODEL', '15a3689ee13b0d2616e98820eca31d4c3abcd36672df6afce5cb6feb1d66087d');

// Parameter Generasi AI (Image-to-Image)
// prompt_strength: 0.0 = gambar asli tidak berubah, 1.0 = gambar asli diabaikan sepenuhnya
// Untuk style transfer (anime, vintage, dll) yang TETAP mempertahankan wajah/subjek: 0.30 - 0.50
// Untuk perubahan besar (ganti background, dll): 0.60 - 0.80
define('REPLICATE_PROMPT_STRENGTH', 0.90);
define('REPLICATE_NUM_INFERENCE_STEPS', 30);
define('REPLICATE_GUIDANCE', 7.5);

// Enable/Disable AI Enhance step in workflow
define('AI_ENHANCE_ENABLED', true);

// Default prompt jika user tidak mengubah
define('AI_ENHANCE_DEFAULT_PROMPT', 'Enhance this photobooth photo: make it vibrant, well-lit, and professional looking.');