<?php

namespace SimpleRoute;

use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Exception\MethodNotAllowedException;

class Dispatcher
{
    private $exception;
    private $routes;

    public function __construct(RouteCollector $routes, $exception = '')
    {
        $this->exception = $exception;
        $this->routes    = $routes;
    }
    /**
     *  
     */
    public function dispatch()
    {
        foreach ($this->routes->getRoutes() as $route) {
            $this->exception = '';

            $result = $route->match();

            $this->exception = $result;
        }
        $this->handlerException();

        return $result;
    }
    /**
     * 
     */
    public function generate(string $name, array $parameters = [])
    {
        foreach($this->routes->getRoutes() as $route)
        {
            return $route->generate($name,$parameters);
        }       
    }
    /**
     * 
     */
    private function handlerException()
    {
        if($this->exception != '') {
            if($this->exception == 'methodNotAllowed'){
                throw new MethodNotAllowedException($this->exception);
            }
            throw new BadRouteException($this->exception);
        }
    }
}
?> 