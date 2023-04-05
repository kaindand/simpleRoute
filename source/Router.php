<?php
namespace Source;

use Source\Exception\BadRouteException;

class Router{
    private $routes = [];
    private $exception = "";
    private $params = [];

    public function __construct($routes = [],$params = [],$exception=''){
        $this->routes = $routes;
        $this->exception = $exception;
        $this->params = $params;
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
    }
    public function dispatch()
    {
        foreach ($this->routes as $route) {

            $this->exception = '';

            if(substr($route['route'],0,1) != '/')
            {
                $route['route'] = '/'.$route['route'];
            }


            $pattern = preg_replace('/\//', '\\/', $route['route']);
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);             
            $pattern = '/^' . $pattern . '$/';

            $this->execution($route,$pattern);
        }
        $this->handlerException();
    }
    private function execution($route,$pattern)
    {
        $uri = $this->getURI();
        
        if($_SERVER['REQUEST_METHOD'] == $route['httpMethod'])
        {          
            if (preg_match($pattern, $uri, $matches)) {

                $filePath = $route['class'].'.php';
                
                $this->addParameters($matches);

                if(file_exists($filePath))
                {
                    include $filePath;

                    if(class_exists($route['class'],false))
                    {
                        if(method_exists($route['class'],$route['method']))
                        {
                            $object = new $route['class'];

                            call_user_func_array([$object,$route['method']],$this->params);

                            exit;
                        }else{                      
                            $this->exception="methodNotFound";
                        }
                    }else{
                        $this->exception="classNotFound";
                    }
                }else{
                    $this->exception="pathNotFound";
                }                
            }
            else{     
                $this->exception="routeNotFound";           
            }
        }
        else{
            $this->exception="httpMethodNotAllowed";
        }
        return;
    }
    private function handlerException()
    {
        if($this->exception != '')
        {
            throw new BadRouteException($this->exception);
        }
    }
    public function addParameters($matches)
    {
        foreach ($matches as $key => $value) {
            if (is_string($key)) {
                $this->params[$key] =  $value;
            }
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
?>