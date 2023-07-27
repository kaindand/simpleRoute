<?php

namespace SimpleRoute\Traits;

trait RouteTrait
{
    public function get(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute('GET', $route, $handler, $data);

        return $route;
    }

    public function post(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute('POST', $route, $handler, $data);

        return $route;
    }

    public function put(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute('PUT', $route, $handler, $data);

        return $route;
    }

    public function patch(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute('PATCH', $route, $handler, $data);

        return $route;
    }

    public function delete(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute('DELETE', $route, $handler, $data);

        return $route;
    }

    public function any(string $route, $handler, array $data = [])
    {
        $route = $this->addRoute($_SERVER['REQUEST_METHOD'], $route, $handler, $data);

        return $route;
    }
}
