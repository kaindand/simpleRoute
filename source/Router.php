<?php
namespace Source;

class Router{
    private static $routes =[];
    public function addRoute($route,$actionData)
    {        
        $class = $actionData[0];
        $method = $actionData[1];

        self::$routes[] = 
        [
            'route' => $route,
            'class' => $class,
            'method' => $method
        ];
    }

    public function run()
    {
        $uri = $this->getURI();
        $params = [];
        foreach (self::$routes as $route) {
            $pattern = preg_replace('/\//', '\\/', $route['route']);
            $pattern = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[^\/]+)', $pattern);
            $pattern = '/^' . $pattern . '$/';

            if (preg_match($pattern, $uri, $matches)) {

                $filePath = $route['class'].'.php';
                
                if($matches != null)
                {
                    foreach ($matches as $key => $value) {
                        if (is_string($key)) {
                            $params[$key] = $value;
                        }
                    }
                }
                
                $this->include($filePath,$route['class'],$route['method'],$params);
            }
        }
    }
    private function include($filePath,$class,$method,$params)
    {
        if(file_exists($filePath))
        {
            include $filePath;

            if(class_exists($class,false))
            {
                if(method_exists($class,$method))
                {
                    $object = new $class;

                    call_user_func_array([$object, $method],$params);
                }else{                      
                    echo "Метод не обнаружен!";
                }
            }else{
                echo "Класс не обнаружен!";
            }
        }else{
            echo "Файл не обнаружен!";
        }
    }
    private function getURI()
    {
        if (!empty($_SERVER['REQUEST_URI']))
        {
            return trim($_SERVER['REQUEST_URI'], '/');
        }
    }
}
?>