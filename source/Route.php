<?php

namespace Source;

class Route
{
    private $route = [];
    private $parameters = [];

    public function __construct($route = [],$parameters = [])
    {
        $this->route = $route;
        $this->parameters = $parameters;
    }
    public function match()
    {
        $uri = $this->getURI();

        if(substr($this->route['route'],0,1) != '/')
        {
            $this->route['route'] = '/'.$this->route['route'];
        }


        $pattern = preg_replace('/\//', '\\/', $this->route['route']);
        $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);             
        $pattern = '/^' . $pattern . '$/';  

        if($_SERVER['REQUEST_METHOD'] == $this->route['httpMethod'])
        {          
            if (preg_match($pattern, $uri, $matches)) {

                $filePath = $this->route['class'].'.php';

                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $this->parameters[$key] =  $value;
                    }
                }

                if(file_exists($filePath))
                {
                    include_once $filePath;

                    if(class_exists($this->route['class'],false))
                    {
                        if(method_exists($this->route['class'], $this->route['method']))
                        {
                            $object = new $this->route['class'];

                            call_user_func_array([$object,$this->route['method']],$this->parameters);

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
                return "routeNotFound";           
            }
        }
        else
        {
            return "httpMethodNotAllowed";
        }
    }
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        {
            return $_SERVER['REQUEST_URI'];
        }
    }
    
}
