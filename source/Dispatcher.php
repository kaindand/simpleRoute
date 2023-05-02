<?php
namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;

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

            $result = $route->match();  
            
            $this->exception = $result;
        }
        $this->handlerException();

        return $result;
    }
    private function handlerException()
    {
        if($this->exception != ''){
            throw new BadRouteException($this->exception);
        }
    }
}
?> 