<?php
namespace Source;

use Source\Exception\BadRouteException;

class Router{
    private static $routes = [];
    private static $routeNotFound = false;
    private static $methodNotFound = false;
    private static $classNotFound = false;
    private static $pathNotFound = false;
    private static $httpMethodNotAllowed = false;

    public static function addRoute($httpMethod,$route,$actionData)
    {        
        $class = $actionData[0];
        $method = $actionData[1];
        
        self::$routes[] = 
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
        $result = null;
        foreach (self::$routes as $route) {

            if($_SERVER['REQUEST_METHOD'] == $route['httpMethod'])
            {
                if($route['route'] == '')
                {
                    $route['route'] = '/'.$route['route'];
                }

                self::$httpMethodNotAllowed = false;

                $pattern = preg_replace('/\//', '\\/', $route['route']);
                $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);             
                $pattern = '/^' . $pattern . '$/';
                
                if (preg_match($pattern, $uri, $matches)) {
                    self::$routeNotFound = false;

                    $filePath = $route['class'].'.php';
                    
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }
                    
                    if(file_exists($filePath))
                    {
                        self::$pathNotFound = false;

                        include $filePath;

                        if(class_exists($route['class'],false))
                        {
                            self::$classNotFound = false;
                            if(method_exists($route['class'],$route['method']))
                            {
                                self::$methodNotFound = false;
                                $object = new $route['class'];

                                call_user_func_array([$object,$route['method']],$params);

                                return true;
                            }else{                      
                                self::$methodNotFound = true;
                            }
                        }else{
                            self::$classNotFound = true;
                        }
                    }else{
                        self::$pathNotFound = true;
                    }                
                }
                else{
                    self::$routeNotFound = true;                 
                }
            }
            else{
                self::$httpMethodNotAllowed = true;
                
            }
        }
        $this->handlerException();
    }
    private function handlerException()
    {
        if(self::$httpMethodNotAllowed === true)
        {
            throw new BadRouteException('HttpMethod not Allowed!');
        }
        if(self::$routeNotFound === true)
        {
            throw new BadRouteException('Route not found!');
        }
        if(self::$pathNotFound === true)
        {
            throw new BadRouteException('file not found!');
        }
        if(self::$classNotFound === true)
        {
            throw new BadRouteException('class not found!');
        }
        if(self::$methodNotFound === true)
        {
            throw new BadRouteException('Method not found!');
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