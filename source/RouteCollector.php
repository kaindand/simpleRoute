<?php

namespace SimpleRoute;

use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;

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
    /**
     *  Adds a prefix to the $this->currentPrefix
     */
    public function group($callback, string $prefix = '', string $name = '')
    {
        if($prefix){
            if(substr($prefix, -1) != '/') {
                $prefix = $prefix.'/';
            }
    
            $this->currentPrefix .= $prefix;
        }
        
        $this->currentName .= $name;

        $callback($this);
    }
    /**
     *  Adds a route to the collection $this->routes
     * 
     * @param mixed httpMethod 
     * @param string route 
     * @param mixed handler
     * @param string name
     * 
     *  @return Route $route
     */
    public function addRoute($httpMethod, string $route, $handler, string $name = '')
    {
        $route = $this->currentPrefix.$route;

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($route, $httpMethod, $handler, $this->currentPrefix, [], $name);
        array_push($this->routes, $route);

        return $route;
    } 
    /**
     *  Returns the collected route data
     * 
     *  @return array $this->routes
     */
    public function getRoutes()
    {
        return $this->routes;
    }

}
