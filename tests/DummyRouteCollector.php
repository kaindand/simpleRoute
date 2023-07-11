<?php

namespace SimpleRoute\Tests;

use SimpleRoute\RouteCollector;
use SimpleRoute\Route;

class DummyRouteCollector extends RouteCollector
{
    private $routes = [];

    private $currentPrefix;

    private $currentName;
    
    public function __construct(string $currentPrefix = '', string $currentName = '')
    {
        $this->currentPrefix       = $currentPrefix;
        $this->$currentName        = $currentName;
    }

    public function addRoute($httpMethod, string $route, $handler, string $name = '')
    {
        $route = $this->currentPrefix.$route;
        $name = $this->currentName.$name;

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($httpMethod, $route, $handler, $this->currentPrefix, [], $name);
        array_push($this->routes, $route);
    }
}
