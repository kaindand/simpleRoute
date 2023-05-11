<?php

namespace SimpleRoute;

use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;
use SimpleRoute\RouteParser;

class RouteCollector
{
    use RouteTrait;

    private $routes = [];

    private $currentPrefix;

    private $currentName;

    public function __construct(array $routes = [], string $currentPrefix = '', string $currentName = '')
    {
        $this->routes              = $routes;
        $this->currentPrefix       = $currentPrefix;
        $this->$currentName        = $currentName;
    }

    public function group($callback, string $prefix = '', string $name = '')
    {
        if(substr($prefix, -1) != '/') {
            $prefix = $prefix.'/';
        }

        $this->currentPrefix .= $prefix;
        
        $this->currentName .= $name;

        $callback($this);
    }

    public function addRoute($httpMethod, $route, $handler, array $regex = [], string $name = '')
    {
        $args = func_get_args();
        
        foreach($args as $arg)
        {
            if(is_array($arg)){
                if($arg == $handler)
                {
                    
                }else if($arg == $regex){

                }else{
                    foreach($arg as $key => $value){
                        $args[$key] = $value;
                    }
                }
            }
        }

        $route = $this->currentPrefix.$route;

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($route, $httpMethod, $handler, $this->currentPrefix, $regex, $name);
        array_push($this->routes, $route);

        return $route;
    } 

    public function getRoutes()
    {
        return $this->routes;
    }

}
