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
        $temp = new Route($route,$httpMethod,$class,$method);
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

    public function get($route,$actionData)
    {
        $term = $this->addRoute('GET',$route,$actionData);

        return $term;
    }

    public function post($route,$actionData)
    {
        $term = $this->addRoute('POST',$route,$actionData);

        return $term;
    }
}
?> 