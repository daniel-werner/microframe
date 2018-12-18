<?php

namespace Microframe\Routing;

class Route implements RouteInterface
{
    protected $route;
    protected $action;
    protected $method;
    protected $routeRegex;

    public function __construct($method, $route, $action)
    {
        $this->method = $method;
        $this->route = $route;
        $this->action = $action;

        $this->buildRouteRegex();
    }

    public function match($method, $uri)
    {
        return ($this->method === $method) && preg_match($this->routeRegex, $uri);
    }

    protected function getRouteParams(){
        $routeParams = [];

        $routeParts = explode('/', $this->route);

        foreach ($routeParts as $index => $routePart){
            if(preg_match('/^{.+}$/', $routePart)){
                $routeParams[$index] = $routePart;
            }
        }

        return $routeParams;
    }

    protected function buildRouteRegex(){
        $routeRegexParts = explode('/', $this->route);
        $routeParams = $this->getRouteParams();

        foreach ($routeParams as $index => $routeParam){
            $routeRegexParts[$index] = '[A-Za-z0-9_-]+';
        }

        $routeRegex = '/^' . implode( '\/', $routeRegexParts ) . '$/';

        $this->routeRegex = $routeRegex;
    }

    protected function getUriParams($uri){
        $uriParts = explode('/', $uri);
        $routeParams = $this->getRouteParams();
        $uriParams = [];

        foreach ($routeParams as $index => $routeParam){
            if(isset($uriParts[$index])){
                $key = ltrim($routeParam, '{');
                $key = rtrim($key, '}');
                $uriParams[$key] = $uriParts[$index];
            }
        }

        return $uriParams;
    }

    public function getParams($uri)
    {
        $params = explode('@', $this->action);
        $params[] = $this->getUriParams($uri);

        return $params;
    }
}