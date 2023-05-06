<?php

namespace SimpleRoute;

class RouteParser
{
    public function parse($route, $prefix = '')
    {
        $route = $prefix.$route;

        if(substr($route, 0, 1) != '/') {
            $route = '/'.$route;
        }

        $pattern = preg_replace('/\//', '\\/', $route);

        $pattern = preg_replace_callback('/\{([a-z]+)?\}/', function ($matches) {
            $argument = $matches[1];
            $replace = '[^}]+';
            return '(?P<'.$argument.'>'.$replace.')';
        }, $pattern);

        $pattern = '/'.$pattern.'/';

        $dataRoute['route'] = $pattern;

        $parameters = [];

        if (preg_match($pattern, $_SERVER['REQUEST_URI'], $matches)) {
            foreach ($matches as $key => $value) {

                if (is_string($key)) {
                    $parameters[$key] =  $value;
                }
            }
        }

        $dataRoute['parameters'] = $parameters;

        return $dataRoute;
    }

}
