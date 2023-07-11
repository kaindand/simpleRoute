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
    public function group($callback, array $data = [])
    {
        if(isset($data['prefix'])){
            $prefix = $data['prefix'];
            if(substr($data['prefix'], -1) != '/') {
                $prefix = $data['prefix'].'/';
            }
    
            $this->currentPrefix .= $prefix;
        }

        if(isset($data['name']))
        {
            $this->currentName .= $data['name'];
        }

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
        $name = $this->currentName.$name;

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($httpMethod, $route, $handler, $this->currentPrefix, [], $name);
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
