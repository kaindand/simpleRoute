<?php

namespace SimpleRoute\Traits;

trait RouteTrait
{
    public function get($route,$actionData)
    {
        $temp = $this->addRoute('GET',$route,$actionData);

        return $temp;
    }

    public function post($route,$actionData)
    {
        $temp = $this->addRoute('POST',$route,$actionData);

        return $temp;
    }

    public function put($route,$actionData)
    {
        $temp = $this->addRoute('PUT',$route,$actionData);

        return $temp;
    }

    public function patch($route,$actionData)
    {
        $temp = $this->addRoute('PATCH',$route,$actionData);

        return $temp;
    }

    public function delete($route,$actionData)
    {
        $temp = $this->addRoute('DELETE',$route,$actionData);

        return $temp;
    }
    
    public function any($route,$actionData)
    {
        $temp = $this->addRoute($_SERVER['REQUEST_METHOD'],$route,$actionData);

        return $temp;
    }    
}
