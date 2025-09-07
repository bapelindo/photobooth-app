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

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app'); // -> .../photobooth-app/app

// URL Root
// Ganti 'http://localhost/photobooth-app' sesuai dengan URL proyek Anda
define('URLROOT', 'http://localhost/photobooth-app/public'); 

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
define('SMTP_PASSWORD', 'ljum wbxr dhfx jwij');

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

// Maximum file size for photo uploads (bytes)
define('MAX_PHOTO_FILE_SIZE', 10485760); // 10MB

// Supported image formats
define('SUPPORTED_IMAGE_FORMATS', ['jpg', 'jpeg', 'png', 'gif']);

// Photo quality settings (1-100, higher = better quality)
define('PHOTO_QUALITY', 100);

// Thumbnail size for gallery displays
define('THUMBNAIL_WIDTH', 150);
define('THUMBNAIL_HEIGHT', 150);