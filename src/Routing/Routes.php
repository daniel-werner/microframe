<?php

namespace Microframe\Routing;

class Routes
{
    protected static $routes = [];
    protected static $matchedRoutes = [];

    public static function get($route, $action)
    {
        static::$routes[] = new Route('GET', $route, $action);
    }

    public static function post($route, $action)
    {
        static::$routes[] = new Route('POST', $route, $action);
    }

    public static function getRoute($method, $uri)
    {
        if (empty(static::$matchedRoutes[$method][$uri])) {
            foreach (static::$routes as $route) {
                if ($route->match($method, $uri)) {
                    static::$matchedRoutes[$method][$uri] = $route;
                    break;
                }
            }
        }

        return static::$matchedRoutes[$method][$uri] ?? null;
    }
}
