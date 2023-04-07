<?php
namespace Source;

use Source\Exception\BadRouteException;
use Source\Route;
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
        
        $this->routes[] = 
        [
            'httpMethod' => $httpMethod,
            'route' => $route,
            'class' => $class,
            'method' => $method,          
        ];

        return $this;
    }

    public function dispatch()
    {
        foreach ($this->routes as $route) {

            $this->exception = '';

            $r = new Route($route);

            $this->exception = $r->match();
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
        $this->addRoute('GET',$route,$actionData);
    }

    public function post($route,$actionData)
    {
        $this->addRoute('POST',$route,$actionData);
    }

    public function put($route,$actionData)
    {
        $this->addRoute('PUT',$route,$actionData);
    }

    public function delete($route,$actionData)
    {
        $this->addRoute('DELETE',$route,$actionData);
    }

    public function getInfo($key1 = null,$key2 = null)
    {
        if($key1 == null && $key2 == null)
        {
            return $this->routes;
        }
        else if($key1 != null && $key2 != null)
        {
            return $this->routes[$key1][$key2];
        }
        else if($key1 != null)
        {
            return $this->routes[$key1];
        }
        
    }
}
?> 