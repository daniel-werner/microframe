<?php

namespace Microframe\Routing;

interface RouteInterface
{
    public function __construct($method, $route, $action);

    public function match($method, $uri);

    public function getParams($uri);
}
