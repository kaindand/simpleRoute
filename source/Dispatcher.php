<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Route;

class Dispatcher{

    private $exception;
    private $routes;
    
    public function __construct(RouteCollector $routes,$exception = '')
    {
        $this->exception = $exception;
        $this->routes    = $routes;
    }

    public function dispatch()
    {
        foreach ($this->routes->getRoutes() as $route) 
        {   
            $this->exception = '';

            $this->exception = $route->match();      
        }
        $this->handlerException();
    }
    public function generate()
    {
        
    }
    private function handlerException()
    {
        if($this->exception != ''){
            throw new BadRouteException($this->exception);
        }
    }
}
?> 