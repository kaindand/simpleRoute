<?php

namespace SimpleRoute;

use SimpleRoute\Traits\AddRouteTrait;
use SimpleRoute\Route;
use SimpleRoute\Group;
use SimpleRoute\RouteParser;

class RouteCollector
{
    //use AddRouteTrait;
    
    private $routes = [];

    private $currentPrefix = [];

    private $currentParentGroups = [];


    public function __construct(array $routes = [], string $currentPrefix = '', array $currentParentGroups = []){
        $this->routes              = $routes;
        $this->currentPrefix       = $currentPrefix;
        $this->currentParentGroups = $currentParentGroups;
    }
    
    public function addGroup($prefix,$callback)
    {
        $group = new Group($prefix,$this->currentParentGroups);

        array_push($this->currentParentGroups,$group);
        $this->currentPrefix .= $prefix;

        $callback($this);
    }

    public function addRoute($httpMethod,$route,$handler)
    {       
        $parser = new RouteParser();
        $routeDatas = $parser->parse($route,$this->currentPrefix);
        
        $route = new Route($routeDatas['route'],$httpMethod,$handler,$this->currentParentGroups,$this->currentPrefix,$routeDatas['parameters']);
        array_push($this->routes,$route);

        return $route;
        
    }

    public function getRoutes()
    {
        return $this->routes;
    }

}
