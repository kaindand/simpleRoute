<?php

namespace SimpleRoute;

class RouteParser
{
    private $dataRoute;

    public function __construct(array $dataRoute = [])
    {
        $this->dataRoute = $dataRoute;
    }
    public function parse($route,$prefix = '')
    {
        $route = $prefix.$route;

        if(substr($route,0,1) != '/')
        {
            $route = '/'.$route;
        }

        $route = preg_replace('/\//', '\\/', $route);
        $route = preg_replace('/\{([a-z]+)?\}/', '(?P<\1>[^\/]+)',$route);             
        $route = '/' . $route. '/';  

        $dataRoute['route'] = $route;

        $parameters = $this->setParameters($route);

        $dataRoute['parameters'] = $parameters;

        return $dataRoute;
    }

    private function setParameters($route)
    {
        if (preg_match($route, $_SERVER['REQUEST_URI'], $matches)) {   
            foreach ($matches as $key => $value) {
                
                if (is_string($key)) {
                    $parameters[$key] =  $value;
                }
            }
            return $parameters;
        }
        return null;
    }
}
