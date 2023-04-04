<?php
namespace Source;

use Source\Exception\BadRouteException;

class Router{
    private $routes = [];
    private $exception = "";

    public function __construct($routes = [],$exception=''){
        $this->routes = $routes;
        $this->exception = $exception;
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
    public function run()
    {
        $uri = $this->getURI();
        $params = [];

        foreach ($this->routes as $route) {

            $this->exception = '';

            if($_SERVER['REQUEST_METHOD'] == $route['httpMethod'])
            {
                if(substr($route['route'],0,1) != '/')
                {
                    $route['route'] = '/'.$route['route'];
                }


                $pattern = preg_replace('/\//', '\\/', $route['route']);
                $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);             
                $pattern = '/^' . $pattern . '$/';
                
                if (preg_match($pattern, $uri, $matches)) {

                    $filePath = $route['class'].'.php';
                    
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }
                    
                    if(file_exists($filePath))
                    {
                        include $filePath;

                        if(class_exists($route['class'],false))
                        {
                            if(method_exists($route['class'],$route['method']))
                            {
                                $object = new $route['class'];

                                call_user_func_array([$object,$route['method']],$params);

                                return;
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
        }
        $this->handlerException();
    }
    private function handlerException()
    {
        if($this->exception != '')
        {
            throw new BadRouteException($this->exception);
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