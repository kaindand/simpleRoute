<?php

namespace SimpleRoute;

use SimpleRoute\Exception\ParametersException;
use SimpleRoute\Exception\BadRouteException;
use SimpleRoute\Traits\GetInfoRouteTrait;

class Route
{
    private $prefix;

    private $route;

    private $httpMethod;

    private $handler;

    private $tokens;

    private $name;

    public function __construct($route, $httpMethod, $handler, string $prefix = '', array $tokens = null, string $name = '')
    {
        $this->route        = $route;
        $this->httpMethod   = $httpMethod;
        $this->handler      = $handler;
        $this->tokens       = $tokens;
        $this->prefix       = $prefix;
        $this->name         = $name;
    }

    public function match()
    {
        $uri = $_SERVER['REQUEST_URI'];

        $pattern = preg_replace('/\//', '\\/', $this->route);

        $pattern = preg_replace_callback('/\{([a-z]+)?\}/', function ($matches) {
            $argument = $matches[1];
            $replace = $this->tokens[$argument] ?? '[^}]+';
            return '(?P<'.$argument.'>'.$replace.')';
        }, $pattern);

        $pattern = '/^'.$pattern.'$/';

        if (preg_match($pattern, $uri, $matches)) {

            if($_SERVER['REQUEST_METHOD'] == $this->httpMethod) {

                if(is_array($this->handler)) {
                    $filePath = 'source/Aboba'.'.php';

                    if(file_exists($filePath)) {
                        include_once $filePath;

                        if(class_exists($this->handler[0], false)) {
                            if(method_exists($this->handler[0], $this->handler[1])) {
                                foreach ($matches as $key => $value) {
                                    if (is_string($key)) {
                                        $parameters[$key] =  $value;
                                    }
                                }
                                
                                $object = new $this->handler[0]();

                                call_user_func_array([$object,$this->handler[1]], $parameters);

                                exit;
                            }else {
                                return "methodNotFound";
                            }
                        }else {
                            return "classNotFound";
                        }
                    }else {
                        return "pathNotFound";
                  }
                }else {
                    call_user_func($this->handler);         
                }
            } else {
                return "httpMethodNotAllowed";
            }
        } else {
            return "routeNotFound";
        }
        exit;
    }

    public function generate($name, $parameters)
    {
        $parameters = array_filter($parameters);
        
        if($name != $this->name) {
            return null;
        } else {
            $url = preg_replace_callback('/\{([a-z]+)?\}/', function ($matches) use ($parameters) {
                $argument = $matches[1];

                return $parameters[$argument];
            }, $this->route);
            return $url;
        }
    }

}
