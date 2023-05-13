<?php

namespace SimpleRoute\Traits;

trait RouteTrait
{
    public function get(string $route, $handler, string $name = '')
    {
        $this->addRoute('GET', $route, $handler, $name);
    }

    public function post(string $route, $handler, string $name = '')
    {
        $this->addRoute('POST', $route, $handler, $name);
    }

    public function put(string $route, $handler, string $name = '')
    {
        $this->addRoute('PUT', $route, $handler, $name);
    }

    public function patch(string $route, $handler, string $name = '')
    {
        $this->addRoute('PATCH', $route, $handler, $name);
    }

    public function delete(string $route, $handler, string $name = '')
    {
        $this->addRoute('DELETE', $route, $handler, $name);
    }

    public function any(string $route, $handler, string $name = '')
    {
        $this->addRoute($_SERVER['REQUEST_METHOD'], $route, $handler, $name);
    }
}
