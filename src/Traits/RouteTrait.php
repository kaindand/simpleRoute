<?php

namespace SimpleRoute\Traits;

trait RouteTrait
{
    public function get(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute('GET', $route, $handler, $name);

        return $route;
    }

    public function post(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute('POST', $route, $handler, $name);

        return $route;
    }

    public function put(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute('PUT', $route, $handler, $name);

        return $route;
    }

    public function patch(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute('PATCH', $route, $handler, $name);

        return $route;
    }

    public function delete(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute('DELETE', $route, $handler, $name);

        return $route;
    }

    public function any(string $route, $handler, string $name = '')
    {
        $route = $this->addRoute($_SERVER['REQUEST_METHOD'], $route, $handler, $name);

        return $route;
    }
}
