<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\RouteTrait;
use SimpleRoute\Route;
use SimpleRoute\Group;

class Router{

    use RouteTrait;
    
    private $routes = [];

    private $groups = [];

    private $currentParentGroup;

    private $exception = '';

    public function __construct($routes = [],$groups = [],$currentParentGroup = '',$exception=''){
        $this->routes             = $routes;
        $this->groups             = $groups;
        $this->currentParentGroup = $currentParentGroup;
        $this->exception          = $exception;
    }
    
    public function addGroup($callback)
    {
        $group = new Group($this->currentParentGroup);

        $this->currentParentGroup = $group;

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

        $temp = new Route($route,$httpMethod,$class,$method,$this->currentParentGroup,$parameters);
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