<?php

namespace SimpleRoute;

use SimpleRoute\Exception\ParametersException;
use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\GetInfoRouteTrait;

class Route
{
    use GetInfoRouteTrait;

    private $parentGroups = [];
    
    private $prefix;

    private $route;

    private $httpMethod;

    private $handler;
    
    private $parameters = [];

    public function __construct($route,$httpMethod,$handler,array $parentGroups = [],string $prefix = '', array $parameters = [])
    {
        $this->route        = $route;
        $this->httpMethod   = $httpMethod;
        $this->handler      = $handler;
        $this->parameters   = $parameters;
        $this->prefix       = $prefix;
        $this->parentGroups = $parentGroups;
    }
    
    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (preg_match_all($this->route, $uri)) {  
            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod)
            {   
   
                    // $filePath = 'source/Aboba'.'.php';

                    // if(file_exists($filePath))
                    // {
                    //     include_once $filePath;
    
                    //     if(class_exists($this->$handler[0],false))
                    //     {
                    //         if(method_exists($this->$handler[0], $this->$this->$handler[1]))
                    //         {
                    //             $object = new $$this->$handler[0];
    
                    //             call_user_func_array([$object,$this->$handler[1]],$this->parameters);
    
                    //             exit;
                    //         }
                    //         else
                    //         {            
                    //             return "methodNotFound";          
                    //         }
                    //     }
                    //     else
                    //     {
                    //         return "classNotFound";
                    //     }
                    // }
                    // else
                    // {
                    //     return "pathNotFound";
                    // }  
                    $action = $this->handler;
             
                    return $action();
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
    public function where($regexes)
    {
        if($this->parameters != null)
        {
            foreach($regexes as $parameter => $regex)
            {
                if($this->parameters[$parameter])
                {
                    if(!preg_match('/'.$regex.'/',$this->parameters[$parameter]))
                    {
                        throw new ParametersException('regexNotAllowed');
                    }
                }
                else
                {
                    throw new ParametersException('parameterNotFound');
                }
            }
        }
        return $this;
    }
}
