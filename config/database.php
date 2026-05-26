<?php

// Pastikan file ini MENGEMBALIKAN sebuah array
if (!function_exists('getEnvVar')) {
    function getEnvVar($key, $default = null) {
        $val = getenv($key);
        if ($val !== false) return $val;
        if (isset($_ENV[$key])) return $_ENV[$key];
        if (isset($_SERVER[$key])) return $_SERVER[$key];
        return $default;
    }
}

return [
    'host' => getEnvVar('DB_HOST', '127.0.0.1;port=3307'),
    'socket' => getEnvVar('DB_SOCKET', null),
    'user' => getEnvVar('DB_USER', 'root'),
    'password' => getEnvVar('DB_PASS', 'root'),
    'dbname' => getEnvVar('DB_NAME', 'photobooth_db')
];