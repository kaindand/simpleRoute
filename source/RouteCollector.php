<?php

namespace SimpleRoute;

use SimpleRoute\Traits\AddRouteTrait;
use SimpleRoute\Route;
use SimpleRoute\Group;

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
        $route = $this->currentPrefix.$route;

        if(substr($route,0,1) != '/')
        {
            $route = '/'.$route;
        }

        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)',$route);             
        $route = '/' . $route. '/';  

        $parameters = $this->setParameters($route);

        $route = new Route($route,$httpMethod,$handler,$this->currentParentGroups,$this->currentPrefix,$parameters);
        array_push($this->routes,$route);

        return $route;
        
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    private function setParameters($route)
    {
        if (preg_match($route, $_SERVER['REQUEST_URI'], $matches)) {  
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $parameters[$key] =  $value;
                    
                    return $parameters;
                }
            }
        }
        return [];
    }
}
