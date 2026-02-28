<?php

// Pastikan file ini MENGEMBALIKAN sebuah array
return [
    'host' => getenv('DB_HOST') ?: 'localhost',
    'user' => getenv('DB_USER') ?: 'root',
    'password' => getenv('DB_PASS') ?: 'root',
    'dbname' => getenv('DB_NAME') ?: 'photobooth_db'
];