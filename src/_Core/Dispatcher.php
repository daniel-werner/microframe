<?php

namespace Microframe\Core;

use Microframe\Routing\Router;

class Dispatcher
{
    public static function dispatch(){
        list($controller, $action, $routerParams) = $params = Router::getParams();
        $controller = '\Controllers\\' . $controller;
        $action = new \ReflectionMethod($controller, $action);

        $params = !empty($_POST) ? $_POST : $_GET;
        $params = array_merge($params, $routerParams);

        return $action->invoke( new $controller, $params );
    }
}