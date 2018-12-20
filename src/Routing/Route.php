<?php

namespace Microframe\Routing;

class Route implements RouteInterface
{
    protected $routePath;
    protected $action;
    protected $method;
    protected $routeRegex;
    protected $name;

    public function __construct($method, $route, $action)
    {
        $this->method = $method;
        $this->routePath = $route;
        $this->action = $action;
        $this->name = $action;

        $this->buildRouteRegex();
    }

    protected function getRouteParams()
    {
        $routeParams = [];

        $routeParts = explode('/', $this->routePath);

        foreach ($routeParts as $index => $routePart) {
            if (preg_match('/^{.+}$/', $routePart)) {
                $routeParams[$index] = $routePart;
            }
        }

        return $routeParams;
    }

    protected function buildRouteRegex()
    {
        $routeRegexParts = explode('/', $this->routePath);
        $routeParams = $this->getRouteParams();

        foreach ($routeParams as $index => $routeParam) {
            $routeRegexParts[$index] = '[A-Za-z0-9_-]+';
        }

        $routeRegex = '/^'.implode('\/', $routeRegexParts).'$/';

        $this->routeRegex = $routeRegex;
    }

    protected function getUriParts($uri)
    {
        return explode('/', $uri);
    }

    protected function trimRouteParam($param)
    {
        return rtrim(ltrim($param, '{'), '}');
    }

    protected function getUriParams($uri)
    {
        $uriParts = $this->getUriParts($uri);
        $routeParams = $this->getRouteParams();
        $uriParams = [];

        foreach ($routeParams as $index => $routeParam) {
            if (isset($uriParts[$index])) {
                $key = $this->trimRouteParam($routeParam);
                $uriParams[$key] = $uriParts[$index];
            }
        }

        return $uriParams;
    }

    public function name($name = null)
    {
        if ($name !== null) {
            $this->name = $name;
        }

        return $this->name;
    }

    public function match($method, $uri)
    {
        return ($this->method === $method) && preg_match($this->routeRegex, $uri);
    }

    public function getParams($uri)
    {
        $params = explode('@', $this->action);
        $params[] = $this->getUriParams($uri);

        return $params;
    }

    public function url($params = null)
    {
        $routeParams = $this->getRouteParams();
        $routePathParts = $this->getUriParts($this->routePath);

        foreach ($routeParams as $index => $routeParam) {
            $key = $this->trimRouteParam($routeParam);
            if (isset($params[$key])) {
                $routePathParts[$index] = $params[$key];
            }
        }

        return implode('/', $routePathParts);
    }
}
