<?php

use Microframe\Routing\Router;

function isActive($name, $params = null)
{
    return Router::isActive($name, $params);
}

function url($uri)
{
    return Router::url($uri);
}

function route($name, $params = null)
{
    return Router::routeUrl($name, $params);
}
