<?php

namespace App\Core;

use \Exception;

class Router
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';

    /**
     * Contains routes
     *
     * @var array $routes
     */
    private static $routes = [];

    /**
     * Add a get route
     *
     * @param string $routeName
     * @param array $callable
     */
    public static function get(string $routeName, array $callable)
    {
        self::addRoute([self::METHOD_GET], $routeName, $callable);
    }

    /**
     * Add a post route
     *
     * @param string $routeName
     * @param array $callable
     */
    public static function post(string $routeName, array $callable)
    {
        self::addRoute([self::METHOD_POST], $routeName, $callable);
    }

    /**
     * Add a route
     *
     * @param array $methods
     * @param string $routeName
     * @param array $callable
     */
    private static function addRoute(array $methods, string $routeName, array $callable)
    {
        self::$routes[$routeName] = [
            'methods' => $methods,
            'callable' => $callable
        ];
    }

    /**
     * Dispatch controller on a route
     *
     * @param string $requestUri
     * @param string $requestMethod
     * @param array $request
     */
    public static function dispatch(string $requestUri, string $requestMethod, array $request = [])
    {
        $explodeRequest = array_values(array_filter(explode('/', $requestUri)));
        $realRouteName = !empty($explodeRequest) ? "/{$explodeRequest[0]}" : $requestUri;
        $parameters = [];

        if (is_array($explodeRequest)) {
            array_shift($explodeRequest);

            foreach ($explodeRequest as $parameter) {
                array_push($parameters, $parameter);
            }
        }

        foreach (self::$routes as $routeName => $routeParams) {
            if ($routeName !== "{$realRouteName}" || !in_array($requestMethod, $routeParams['methods'])) {
                continue;
            }

            $className = "\\App\\Core\\Controller\\{$routeParams['callable'][0]}";

            if (!class_exists($className)) {
                $className = "\\App\\Local\\Controller\\{$routeParams['callable'][0]}";

                if (!class_exists($className)) {
                    throw new Exception("Controller {$className} not found.");
                }
            }

            $controller = new $className();

            $method = $routeParams['callable'][1];

            if (!method_exists($controller, $method)) {
                throw new Exception("Controller {$className}->{$method}() not found");
            }

            if ($controller instanceof \App\Core\Controller\AbstractController) {
                $controller->setRequest($request);
            }

            $controller->dispatch($method, $parameters);
        }
    }
}
