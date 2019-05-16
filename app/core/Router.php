<?php

namespace App\Core;

use \App\Core\Controller\AbstractController;
use \App\Core\Controller\ErrorController;
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
     * Contains namespaces
     *
     * @var array $namespaces
     */
    private static $namespaces = [
        'Core',
        'Local'
    ];

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
     * Clean URL
     *
     * @param string $url
     *
     * @return string
     */
    private static function cleanUrl(string $url) : string
    {
        return '/' . trim($url, '/');
    }

    // TODO
    private static function getParameters(string $matchedRoute) : array
    {
        preg_match_all('/{.*?}/', $matchedRoute, $matches);
        $routeParameters = $matches[0];

        $parameters = [
            'mandatory' => [],
            'optional' => []
        ];

        foreach ($routeParameters as $routeParameter) {
            if (preg_match('/{.*?\?}/', $routeParameter)) {
                $parameters['optional'][] = $routeParameter;
            } else {
                $parameters['mandatory'][] = $routeParameter;
            }
        }

        return $parameters;
    }

    /**
     * Set parameters in URL and keep them on an array
     *
     * @param string $route
     * @param string $routeParameter
     * @param array $requestParameters
     * @param array $parameters
     *
     * @return void
     */
    private static function extractParameter(
        string &$route,
        string $routeParameter,
        array &$requestParameters,
        array &$parameters
    ) : void {
        $parameterName = preg_match('/(?<={).+?(?=})/', preg_replace('/\?/', '', $routeParameter), $matches);
        $parameterName = $matches[0];
        $routeParameter = preg_quote($routeParameter);
        $route = preg_replace("/{$routeParameter}/", current($requestParameters), $route);
        $parameters[$parameterName] = current($requestParameters);
        array_shift($requestParameters);
    }

    /**
     * Render not found page
     *
     * @param array $request
     *
     * @return void
     */
    private static function renderNotFoundPage(array $request) : void
    {
        $controller = new ErrorController();
        $controller->setRequest($request);
        $controller->dispatch('notFoundAction', []);
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
        $routes = self::$routes;
        $parameters = [];
        $requestParameters = array_values(array_filter(explode('/', $requestUri)));
        $pageName = htmlspecialchars(array_shift($requestParameters));
        $routesUrl = array_keys($routes);
        $matchedRoute = current(preg_grep("/{$pageName}/", $routesUrl));

        if ($matchedRoute === false) {
            self::renderNotFoundPage($request);
            return;
        }

        $routeInfos = $routes[$matchedRoute];

        if (empty($matchedRoute) || !in_array($requestMethod, $routeInfos['methods'])) {
            self::renderNotFoundPage($request);
            return;
        }

        $matchedRoute = self::cleanUrl($matchedRoute);
        $routeParameters = self::getParameters($matchedRoute);

        if (count($requestParameters) < count($routeParameters['mandatory'])) {
            self::renderNotFoundPage($request);
            return;
        }

        foreach ($routeParameters['mandatory'] as $routeParameter) {
            self::extractParameter($matchedRoute, $routeParameter, $requestParameters, $parameters);
        }

        while (count($requestParameters) > 0) {
            $routeParameter = current($routeParameters['optional']);
            self::extractParameter($matchedRoute, $routeParameter, $requestParameters, $parameters);
            array_shift($routeParameters['optional']);
        }

        foreach (self::$namespaces as $namespace) {
            $className = "\\App\\{$namespace}\\Controller\\{$routeInfos['callable'][0]}";
            $isControllerExists = true;

            if (class_exists($className)) {
                break;
            }

            $isControllerExists = false;
        }

        if ($isControllerExists === false) {
            throw new Exception("Controller {$className} not found.");
        }

        $controller = new $className();
        $method = $routeInfos['callable'][1];

        if (!method_exists($controller, $method)) {
            throw new Exception("Controller {$className}->{$method}() not found");
        }

        if ($controller instanceof AbstractController) {
            $controller->setRequest($request);
        }

        $controller->dispatch($method, $parameters);
    }
}
