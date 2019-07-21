<?php

namespace App\Core;

use App\Core\Router;
use \PDO;
use App\Core\Model\AbstractModel;

final class Light
{
    /**
     * Load application configs
     */
    public function loadConfigs()
    {
        $configs = include getcwd() .'/app/config/configs.php';
        Config::loadConfig($configs);
    }

    /**
     * Load application routes
     */
    public function loadRoutes()
    {
        include getcwd() . '/app/config/routes.php';
    }

    /**
     * Load application database connection
     */
    public function loadDatabase()
    {
        Config::setConfig(
            'db_connection',
            new Connection(Config::getConfig('database'))
        );
    }

    /**
     * Get database instance
     *
     * @return PDO
     */
    public static function getDatabase() : PDO
    {
        return Config::getConfig('db_connection')->getDatabase();
    }

    /**
     * Get model
     *
     * @param string $name
     *
     * @return AbstractModel
     */
    public static function getModel(string $name) : AbstractModel
    {
        $modelName = ucfirst(strtolower($name));
        $model = "App\%s\Model\\{$modelName}Model";

        $localModel = sprintf($model, 'Local');

        if (!class_exists($localModel)) {
            $coreModel = sprintf($model, 'Core');
            return new $coreModel();
        }
        
        return new $localModel();
    }

    /**
     * Get application languages
     *
     * @return array
     */
    public static function getLanguages() : array
    {
        return Config::getConfig('languages') ?? ['en'];
    }

    /**
     * Load application session
     */
    public function loadSession()
    {
        session_start();
    }

    /**
     * Dispatch request to the router
     */
    public function dispatch()
    {
        Router::dispatch(
            $_SERVER['REQUEST_URI'] ?? '',
            $_SERVER['REQUEST_METHOD'] ?? R::METHOD_GET,
            $_REQUEST
        );
    }

    /**
     * Run application
     */
    public static function run()
    {
        self::loadConfigs();
        self::loadRoutes();
        self::loadDatabase();
        self::loadSession();
        self::dispatch();
    }
}
