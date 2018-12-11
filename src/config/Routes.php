<?php

namespace Microframe\Config;


class Routes
{
    public static function get($route, $action){
        self::$get[$route] = $action;
    }

    public static function post($route, $action){
        self::$post[$route] = $action;
    }

    public static $get = [];

    public static $post = [];
}