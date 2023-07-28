<?php

namespace SimpleRoute;

use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;
use SimpleRoute\ResourceRegister;

class RouteCollector
{
    use RouteTrait;

    private $routes = [];

    private $currentMiddleware = [];

    private $currentPrefix;

    private $currentName;

    public function __construct(array $routes = [], $currentMiddleware = [], string $currentPrefix = '', string $currentName = '')
    {
        $this->routes              = $routes;
        $this->currentMiddleware   = $currentMiddleware;
        $this->currentPrefix       = $currentPrefix;
        $this->$currentName        = $currentName;
    }
    /**
     *  Adds a prefix to the $this->currentPrefix
     */
    public function group($callback, array $options = [])
    {
        if(isset($options['prefix'])){
            $prefix = $options['prefix'];
            if(substr($options['prefix'], -1) != '/') {
                $prefix = $options['prefix'].'/';
            }
    
            $this->currentPrefix .= $prefix;
        }

        if(isset($options['middleware']))
        {
            $this->currentMiddleware = $options['middleware'];
        }

        if(isset($options['name']))
        {
            $this->currentName .= $options['name'];
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
    public function addRoute($httpMethod, string $route, $handler, array $options = [])
    {
        $middlewares = $this->currentMiddleware;

        $route = $this->currentPrefix.$route;

        $name = '';

        if(isset($options['name']))
        {
            $name = $this->currentName.$options['name'];
        }
        
        if(isset($options['middleware']))
        {
            foreach($options['middleware'] as $m)
            {
                array_push($middlewares, $m);
            }
        }

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($httpMethod, $route, $handler, $middlewares, $this->currentPrefix, [], $name);
        array_push($this->routes, $route);
        
        return $route;
    } 
    public function resource($name, $handler, array $options = [])
    {
        $resourceRegister = new ResourceRegister($this);
        $resourceRegister->register($name, $handler, $options);
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
    /**
     *  Returns beautifully collected routes information
     * 
     *  @return array $this->routes
     */
    public function getRoutesInfo()
    {
        $routesInfo = [];

        for ($i=0; $i < count($this->routes); $i++) 
        { 
            $routesInfo[$i] = $this->routes[$i]->getRouteInfo()."<hr>";
        }

        return $routesInfo;
    }
}
