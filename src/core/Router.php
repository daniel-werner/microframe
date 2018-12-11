<?php

namespace Microframe\Core;
use Microframe\Config\Routes;

class Router
{
    /**
     * @return array
     */
    public static function getParams(){
        $requestUri = $_SERVER['REQUEST_URI'];
        $method = strtolower($_SERVER['REQUEST_METHOD']);

        $uriParts = explode('?', $requestUri);

        $uri = $uriParts[0];

        if( empty(Routes::$$method[$uri] ) ){
            header("HTTP/1.0 404 Not Found");
            exit;
        }

        $route = Routes::$$method[$uri];
        $params = explode('@', $route);

        return $params;
    }
}