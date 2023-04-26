<?php

namespace SimpleRoute;

use SimpleRoute\Exception\ParametersException;
use SimpleRoute\Traits\GetInfoRouteTrait;

class Route
{
    use GetInfoRouteTrait;

    private $parentGroups = [];
    
    private $prefix;

    private $route;

    private $httpMethod;

    private $class;

    private $method;
    
    private $parameters = [];

    public function __construct($route,$httpMethod,$class,$method,$parentGroups = [],$prefix = '',$parameters = [])
    {
        $this->route      = $route;
        $this->httpMethod = $httpMethod;
        $this->class      = $class;
        $this->method     = $method;
        $this->parameters = $parameters;
        $this->prefix = $prefix;
        $this->parentGroups = $parentGroups;
    }

    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (preg_match_all($this->route, $uri)) {  
            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod)
            {   
                $filePath = 'source/Aboba'.'.php';

                if(file_exists($filePath))
                {
                    include_once $filePath;

                    if(class_exists($this->class,false))
                    {
                        if(method_exists($this->class, $this->method))
                        {
                            $object = new $this->class;

                            call_user_func_array([$object,$this->method],$this->parameters);

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
    }
}
