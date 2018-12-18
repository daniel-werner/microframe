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

    protected static function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getParams(){

        $method = static::getMethod();
        $uri = static::getUri();

        $route = Routes::getRoute($method, $uri);

        if( empty($route ) ){
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $params = $route->getParams($uri);

        return $params;
    }

    public static function isActive($uri){
        $requestUri = static::getUri();

        return $requestUri === $uri;
    }
}