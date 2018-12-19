<?php

namespace Microframe\Core;

class Dispatcher
{
    public static function dispatch()
    {
        list($controller, $action) = $params = Router::getParams();
        $controller = '\Controllers\\'.$controller;
        $action = new \ReflectionMethod($controller, $action);

        $params = !empty($_POST) ? $_POST : $_GET;

        return $action->invoke(new $controller(), $params);
    }
}
