<?php

namespace App\Core;

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
     * @param array $extraDatas
     */
    public static function get(string $routeName, array $callable, array $extraDatas = [])
    {
        self::addRoute([self::METHOD_GET], $routeName, $callable, $extraDatas);
    }

    /**
     * Add a post route
     *
     * @param string $routeName
     * @param array $callable
     * @param array $extraDatas
     */
    public static function post(string $routeName, array $callable, array $extraDatas = [])
    {
        self::addRoute([self::METHOD_POST], $routeName, $callable, $extraDatas);
    }

    /**
     * Add a route
     *
     * @param array $methods
     * @param string $routeName
     * @param array $callable
     * @param array $extraDatas
     */
    private static function addRoute(array $methods, string $routeName, array $callable, array $extraDatas = [])
    {
        self::$routes[$routeName] = [
            'methods' => $methods,
            'callable' => $callable,
            'datas' => $extraDatas
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
        foreach (self::$routes as $routeName => $routeParams) {
            if ($routeName !== $requestUri || !in_array($requestMethod, $routeParams['methods'])) {
                continue;
            }

            $className = "\\App\\Core\\Controller\\{$routeParams['callable'][0]}";

            if (!class_exists($className)) {
                $className = "\\App\\Local\\Controller\\{$routeParams['callable'][0]}";

                if (!class_exists($className)) {
                    throw new \Exception("Controller {$className} not found.");
                }
            }

            $controller = new $className();

            $method = $routeParams['callable'][1];

            if (!method_exists($controller, $method)) {
                throw new \Exception("Controller {$className}->{$method}() not found");
            }

            if ($controller instanceof \App\Core\Controller\AbstractController) {
                $controller->setRequest($request);
            }

            $controller->setExtraDatas($routeParams['datas']);
            $controller->dispatch($method);
        }
    }
}
