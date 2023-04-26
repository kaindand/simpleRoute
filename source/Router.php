<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\AddRouteTrait;
use SimpleRoute\Route;
use SimpleRoute\Group;

class Router{

    use AddRouteTrait;
    
    private $routes = [];

    private $currentPrefix = [];

    private $currentParentGroups = [];

    private $exception = '';

    public function __construct($routes = [],$currentPrefix = '',$currentParentGroups = [],$exception=''){
        $this->routes             = $routes;
        $this->currentPrefix             = $currentPrefix;
        $this->currentParentGroups = $currentParentGroups;
        $this->exception          = $exception;
    }
    
    public function addGroup($prefix,$callback)
    {
        $group = new Group($prefix,$this->currentParentGroups);

        array_push($this->currentParentGroups,$group);
        $this->currentPrefix .= $prefix;

        $callback($this);
    }
    public function addRoute($httpMethod,$route,$actionData)
    {       
        $class = $actionData[0];
        $method = $actionData[1];

        $route = $this->currentPrefix.$route;

        if(substr($route,0,1) != '/')
        {
            $route = '/'.$route;
        }

        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)',$route);             
        $route = '/' . $route. '/';  

        $parameters = $this->setParameters($route);

        $temp = new Route($route,$httpMethod,$class,$method,$this->currentParentGroups,$this->currentPrefix,$parameters);
        array_push($this->routes,$temp);

        return $temp;
    }

    public function dispatch()
    {
        foreach ($this->routes as $route) 
        {
            $this->exception = '';
            
            $this->exception = $route->match();
        }
        $this->handlerException(); 
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
    private function handlerException()
    {
        if($this->exception != '')
        {
            throw new BadRouteException($this->exception);
        }
    }
}
?> 