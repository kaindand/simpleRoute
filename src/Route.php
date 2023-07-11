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

    public function __construct($httpMethod, $route, $handler, string $prefix = '', array $tokens = null, string $name = '')
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

        $pattern = preg_replace_callback('/\{([^}]+)?\}/', function ($matches) {
            $this->tokens = explode(':',$matches[1]);
            
            $replace = $this->tokens[1] ?? '[^}]+';
            
            return '(?P<'.$this->tokens[0].'>'.$replace.')';
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
                                $parameters = [];
                                
                                foreach ($matches as $key => $value) {
                                    if (is_string($key)) {
                                        $parameters[$key] =  $value;
                                    }
                                }
                                
                                $object = new $this->handler[0]();
                               
                                call_user_func_array([$object,$this->handler[1]], $parameters);
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

    public function generate(string $name, array $parameters = [])
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
    /**
     *  returns the route
     * 
     *  @return string $this->route
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     *  returns the name
     * 
     *  @return string $this->name
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     *  returns the HttpMethod
     * 
     *  @return string $this->httpMethod
     */
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    /**
     *  returns the handler
     * 
     *  @return mixed $this->handler
     */
    public function getHandler()
    {
        return $this->handler;
    }
    /**
     *  returns the tokens
     * 
     *  @return array $this->tokens
     */
    public function getTokens()
    {
        return $this->tokens;
    }
    /**
     *  returns the prefix
     * 
     *  @return string $this->prefix
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

}
