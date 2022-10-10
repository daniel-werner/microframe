<?php

namespace Microframe\Routing;

class Router
{
    /**
     * @return array
     */
    protected static function getUri()
    {
        $requestUri = $_SERVER['REQUEST_URI'];
        $uriParts = explode('?', $requestUri);

        return $uriParts[0];
    }

    protected static function getPath()
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI']);
        return $requestUri['path'];
    }

    protected static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getParams()
    {
        $method = static::getMethod();
        $uri = static::getUri();

        $route = Routes::getRoute($method, $uri);

        if (empty($route)) {
            header('HTTP/1.0 404 Not Found');
            exit;
        }

        $params = $route->getParams($uri);

        return $params;
    }

    public static function isActive($name, $params = null)
    {
        $requestUri = static::getPath();
        $route = Routes::getRouteByName($name);

        return $requestUri === $route->url($params);
    }

    public static function routeUrl($name, $params = null)
    {
        $route = Routes::getRouteByName($name);

        return static::url($route->url($params));
    }

    public static function url($uri)
    {
        $proto = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $host = $_SERVER['HTTP_HOST'];

        $url = $proto.$host.$uri;

        return $url;
    }
}
