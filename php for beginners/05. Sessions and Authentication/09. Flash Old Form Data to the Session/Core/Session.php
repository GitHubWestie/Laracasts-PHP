<?php

namespace Core;

class Session {
    // Put something in the session
    public static function put($key, $value)
    {
        $_SESSION[$key] = [$value];
    }

    // Get something from the session
    public static function get($key, $default = null)
    {
        if(isset($_SESSION['_flash'][$key])) {
            return $_SESSION['_flash'][$key];
        }
        
        return $_SESSION[$key] ?? $default;
    }

    // Query if something exists in the session
    public static function has($key)
    {
        return (bool) static::get($key);
    }

    // Add a flash key to the session
    public static function flash($key, $value)
    {
        $_SESSION['_flash'][$key] = $value;
    }

    // Destroy a flash session key
    public static function unflash()
    {
        unset($_SESSION['_flash']);
    }

    // Flush the $_SESSION superglobal
    public static function flush()
    {
        $_SESSION = [];
    }

    // Destroy the session
    public static function destroy()
    {
        static::flush();

        session_destroy();

        $params = session_get_cookie_params();
        
        setcookie('PHPSESSID', '', time() - 3600, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
}