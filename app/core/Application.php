<?php

namespace App\Core;

use App\Core\Router;

final class Application
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
    public function run()
    {
        $this->loadConfigs();
        $this->loadRoutes();
        $this->loadDatabase();
        $this->loadSession();
        $this->dispatch();
    }
}
