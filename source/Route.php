<?php

namespace SimpleRoute;


use SimpleRoute\Exception\ParametersException;

class Route
{
    private $parentGroups = [];
    private $route;

    private $httpMethod;

    private $class;

    private $method;
    
    private $parameters = [];

    public function __construct($route,$httpMethod,$class,$method,$parentGroups = [],$parameters = [])
    {
        $this->route      = $route;
        $this->httpMethod = $httpMethod;
        $this->class      = $class;
        $this->method     = $method;
        $this->parameters = $parameters;
        $this->parentGroups = $parentGroups;
    }

    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];

        if (preg_match_all($this->route, $uri)) {  
            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod)
            {   
                $filePath = 'source/Test'.'.php';

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
    public function getParentGroup()
    {
        return $this->parentGroup;
    }
    
    private function setPrefix()
    {
        if($this->parentGroup->getParentGroup())
        {

            $this->setPrefix();
        }
        return null;
    }
}
