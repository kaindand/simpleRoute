<?php

namespace SimpleRoute;

use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;
use SimpleRoute\Group;
use SimpleRoute\RouteParser;

class RouteCollector
{
    use RouteTrait;

    private $routes = [];

    private $currentPrefix = [];



    public function __construct(array $routes = [], string $currentPrefix = '')
    {
        $this->routes              = $routes;
        $this->currentPrefix       = $currentPrefix;
    }

    public function group($prefix, $callback)
    {
        $this->currentPrefix .= $prefix;

        $callback($this);
    }

    public function addRoute($httpMethod, $route, $handler, $regex = [])
    {
        $parser = new RouteParser();
        $routeDatas = $parser->parse($route, $this->currentPrefix, $regex);

        $route = new Route($routeDatas['route'], $httpMethod, $handler, $this->currentPrefix, $routeDatas['parameters']);
        array_push($this->routes, $route);

        return $route;

    }

    public function getRoutes()
    {
        return $this->routes;
    }

}
