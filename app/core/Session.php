<?php

namespace App\Core;

class Session
{
    /**
     * Starts the session if it's not already started.
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        self::start();
        $_SESSION[$key] = $value;
        if ($key === 'payment_finished_displayed') {
            error_log('Session::set - Key: ' . $key . ', Value: ' . (is_bool($value) ? ($value ? 'true' : 'false') : $value));
        }
    }

    public static function get($key, $default = null)
    {
        self::start();
        $value = $_SESSION[$key] ?? $default;
        if ($key === 'payment_finished_displayed') {
            error_log('Session::get - Key: ' . $key . ', Value: ' . (is_bool($value) ? ($value ? 'true' : 'false') : $value));
        }
        return $value;
    }

    public static function has($key)
    {
        self::start();
        return isset($_SESSION[$key]);
    }

    public static function destroy()
    {
        self::start();
        session_destroy();
    }

    public static function unset($key)
    {
        self::start();
        if ($key === 'payment_finished_displayed') {
            error_log('Session::unset - Key: ' . $key);
        }
        unset($_SESSION[$key]);
    }
}