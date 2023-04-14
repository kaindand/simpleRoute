<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Route;

class Router{

    private $routes = [];
    private $exception = "";

    public function __construct($routes = [],$exception=''){
        $this->routes = $routes;
        $this->exception = $exception;
    }

    public function addRoute($httpMethod,$route,$actionData)
    {        
        $class = $actionData[0];
        $method = $actionData[1];

        
        $parameters = $this->setParameters($route);
        
        $temp = new Route($route,$httpMethod,$class,$method,$parameters);
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

    private function handlerException()
    {
        if($this->exception != '')
        {
            throw new BadRouteException($this->exception);
        }
    }

    private function setParameters($route)
    {
        $pattern = preg_replace('/\//', '\\/', $route);
        $pattern = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)', $pattern);             
        $pattern = '/^' . $pattern . '$/';  

        if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {  
            foreach ($matches as $key => $value) {
                if (is_string($key)) {
                    $parameters[$key] =  $value;
                    
                    return $parameters;
                }
            }
        }
    }

    public function get($route,$actionData)
    {
        $temp = $this->addRoute('GET',$route,$actionData);

        return $temp;
    }

    public function post($route,$actionData)
    {
        $temp = $this->addRoute('POST',$route,$actionData);

        return $temp;
    }
}
?> 