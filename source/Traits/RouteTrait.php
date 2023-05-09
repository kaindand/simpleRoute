<?php

namespace SimpleRoute\Traits;

trait RouteTrait
{
    public function get($httpMethod, $route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute('GET', $route, $handler, $regex, $name);
    }

    public function post($route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute('POST', $route, $handler, $regex, $name);
    }

    public function put($route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute('PUT', $route, $handler, $regex, $name);
    }

    public function patch($route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute('PATCH', $route, $handler, $regex, $name);
    }

    public function delete($route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute('DELETE', $route, $handler, $regex, $name);
    }

    public function any($route, $handler, array $regex = [], string $name = '')
    {
        $this->addRoute($_SERVER['REQUEST_METHOD'], $route, $handler, $regex, $name);
    }
}
