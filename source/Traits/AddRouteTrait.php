<?php

namespace SimpleRoute\Traits;

trait AddRouteTrait
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
    public function any($route,$actionData)
    {
        $temp = $this->addRoute($_SERVER['REQUEST_METHOD'],$route,$actionData);

        return $temp;
    }    
}
