<?php

namespace SimpleRoute;

class Route
{
    private $route;

    private $httpMethod;

    private $class;

    private $method;
    
    private $parameters = [];

    public function __construct($route,$httpMethod,$class,$method,$name = '',$parameters = [])
    {
        $this->route      = $route;
        $this->httpMethod = $httpMethod;
        $this->class      = $class;
        $this->method     = $method;
        $this->parameters = $parameters;
    }

    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];
        if(substr($this->route,0,1) != '/')
        {
            $this->route = '/'.$this->route;
        }

        $pattern = preg_replace('/\//', '\\/', $this->route);
        $pattern = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)', $pattern);             
        $pattern = '/^' . $pattern . '$/';  
        echo $pattern;
        if (preg_match($pattern, $uri, $matches)) {  
            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod)
            { 
                $this->setParameters($matches);
                
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

    private function setParameters($matches)
    {
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $this->parameters[$key] =  $value;
            }
        }
    }

    public function getRoute()
    {
        return $this->route;
    }
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    public function getClass()
    {
        return $this->class;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getParameters()
    {
        return $this->parameters;
    }
    
}
