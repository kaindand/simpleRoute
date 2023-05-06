<?php

namespace SimpleRoute;

use SimpleRoute\Exception\ParametersException;
use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\GetInfoRouteTrait;

class Route
{
    use GetInfoRouteTrait;
    
    private $prefix;

    private $route;

    private $httpMethod;

    private $handler;
    
    private $parameters;

    public function __construct($route,$httpMethod,$handler,string $prefix = '', array $parameters = null)
    {
        $this->route        = $route;
        $this->httpMethod   = $httpMethod;
        $this->handler      = $handler;
        $this->parameters   = $parameters;
        $this->prefix       = $prefix;
    }
    
    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (preg_match_all($this->route, $uri)) {  
            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod)
            {   
                if(is_array($this->handler))
                {
                    $filePath = 'source/Aboba'.'.php';

                    if(file_exists($filePath))
                    {
                        include_once $filePath;
                    
                        if(class_exists($this->handler[0],false))
                        {
                            if(method_exists($this->handler[0], $this->handler[1]))
                            {
                                $object = new $this->handler[0];
    
                                call_user_func_array([$object,$this->handler[1]],$this->parameters);
    
                                exit;
                            }
                            else
                            {            
                                return "methodNotFound";          
                            }
                        }
                        else
                        {
                            return "classNotFound";
                        }
                    }
                    else
                    {
                        return "pathNotFound";
                    }  
                }
                else{
                    $action = $this->handler;
                    
                    return $action();
                }
            }
            else
            {     
                return "httpMethodNotAllowed";        
            }
        }
        else
        {
            return "routeNotFound";
        }
    }
}
