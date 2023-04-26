<?php

namespace SimpleRoute\Traits;

trait GetInfoRouteTrait
{
    public function getRoute()
    {
        return $this->route;
    }
    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    public function getClass()
    {
        return $this->class;
    }
    public function getMethod()
    {
        return $this->method;
    }
    public function getParameters()
    {
        return $this->parameters;
    }
    public function getParentGroups()
    {
        return $this->parentGroups;
    }
    public function getPrefix()
    {
        return $this->prefix;
    }
}
