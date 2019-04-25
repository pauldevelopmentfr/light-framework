<?php

namespace App\Core;

class Config
{
    /**
     * Contains configs
     *
     * @var array $configs
     */
    private static $configs = [];

    /**
     * Load configs in $configs
     *
     * @param array $configs
     */
    public static function loadConfig(array $configs)
    {
        self::$configs = $configs;
    }

    /**
     * Check if config exists
     *
     * @param string $key
     *
     * @return bool
     */
    public static function hasConfig(string $key) : bool
    {
        return isset(self::$configs[$key]);
    }

    /**
     * Get config if exists
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    public static function getConfig(string $key, object $default = null)
    {
        if (self::hasConfig($key)) {
            return self::$configs[$key];
        }

        return $default;
    }

    /**
     * Set config
     *
     * @param string $key
     * @param mixed $value
     */
    public static function setConfig(string $key, object $value)
    {
        self::$configs[$key] = $value;
    }

    /**
     * Unset config
     *
     * @param string $key
     */
    public static function unsetConfig(string $key)
    {
        unset(self::$configs[$key]);
    }
}
