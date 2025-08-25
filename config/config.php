<?php

// Application Configuration

// App Root
define('APPROOT', dirname(dirname(__FILE__)) . '/app'); // -> .../photobooth-app/app

// URL Root
// Ganti 'http://localhost/photobooth-app' sesuai dengan URL proyek Anda
define('URLROOT', 'http://10.10.10.240/photobooth-app'); 

// Site Name
define('SITENAME', 'Acara Photobooth');

// Email Configuration (untuk PHPMailer)
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USERNAME', 'user@example.com');
define('SMTP_PASSWORD', 'your_smtp_password');
define('SMTP_PORT', 587); // atau 465
define('SMTP_SECURE', 'tls'); // atau 'ssl'

// Email From
define('EMAIL_FROM_ADDRESS', 'no-reply@example.com');
define('EMAIL_FROM_NAME', 'Panitia Acara Photobooth');