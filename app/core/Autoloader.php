<?php

namespace App\Core;

class Autoloader
{
    /**
     * This autoloader will search classes in folders
     */
    public static function register()
    {
        spl_autoload_register(function ($className) {
            $className = str_replace('\\', '/', $className);

            $cwd = getcwd();

            $fileName = "{$cwd}/{$className}.php";

            if (file_exists($fileName)) {
                require_once $fileName;
            }
        });
    }
}
