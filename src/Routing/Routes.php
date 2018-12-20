<?php

namespace Microframe\Routing;

class Routes
{
    protected static $routes = [];
    protected static $matchedRoutes = [];

    protected static function addRoute($method, $routePath, $action)
    {
        $route = new Route($method, $routePath, $action);
        static::$routes[] = $route;

        return $route;
    }

    public static function get($routePath, $action)
    {
        return static::addRoute('GET', $routePath, $action);
    }

    public static function post($routePath, $action)
    {
        return static::addRoute('POST', $routePath, $action);
    }

    public static function getRouteByName($name)
    {
        $returnRoute = null;

        foreach (static::$routes as $route) {
            if ($route->name() === $name) {
                $returnRoute = $route;
                break;
            }
        }

        if ($returnRoute === null) {
            throw new \Exception('Route not found: '.$name);
        }

        return $returnRoute;
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
