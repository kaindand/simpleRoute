<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;

class Router{

    use RouteTrait;
    
    private $routes = [];
    private $currentPreffix = '';
    private $exception = '';

    public function __construct($routes = [],$exception='', $currentPreffix = ''){
        $this->currentPreffix = $currentPreffix;
        $this->routes         = $routes;
        $this->exception      = $exception;
    }
    
    public function group($prefix,$callback)
    {
        $this->currentPreffix = $prefix;

        $callback($this);
    }
    public function addRoute($httpMethod,$route,$actionData)
    {       
        $class = $actionData[0];
        $method = $actionData[1];

  
        if(substr($route,0,1) != '/')
        {
            $route = '/'.$route;
        }

        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)',$route);             
        $route = '/' . $route. '/';  

        $parameters = $this->setParameters($route);

        $temp = new Route($route,$httpMethod,$class,$method,$this->currentPreffix,$parameters);
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