<?php

namespace App\Core;

class Session
{
    /**
     * Check if session exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function hasSession(string $key) : bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Get session if exists
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function getSession(string $key, object $default = null)
    {
        if (self::hasSession($key)) {
            return $_SESSION[$key];
        }
        return $default;
    }

    /**
     * Set session
     *
     * @param string $key
     * @param mixed $value
     */
    public static function setSession(string $key, object $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Unset session
     *
     * @param string $key
     */
    public static function unsetSession(string $key)
    {
        unset($_SESSION[$key]);
    }
}
