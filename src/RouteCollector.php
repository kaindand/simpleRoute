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
    public function group($callback, array $data = [])
    {
        if(isset($data['prefix'])){
            $prefix = $data['prefix'];
            if(substr($data['prefix'], -1) != '/') {
                $prefix = $data['prefix'].'/';
            }
    
            $this->currentPrefix .= $prefix;
        }

        if(isset($data['middleware']))
        {
            $this->currentMiddleware = $data['middleware'];
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
    public function addRoute($httpMethod, string $route, $handler, array $data = [])
    {
        $route = $this->currentPrefix.$route;

        $name = '';

        if(isset($data['name']))
        {
            $name = $this->currentName.$data['name'];
        }
        
        if(isset($data['middleware']))
        {
            foreach($data['middleware'] as $m)
            {
                array_push($this->currentMiddleware, $m);
            }
        }

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $route = new Route($httpMethod, $route, $handler, $this->currentMiddleware, $this->currentPrefix, [], $name);
        array_push($this->routes, $route);
        
        return $route;
    } 
    public function resource($name, $handler, array $middleware = [])
    {
        $resourceRegister = new ResourceRegister($this);
        $resourceRegister->register($name, $handler, $middleware);
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
