<?php

namespace SimpleRoute;

use SimpleRoute\RouteCollector;

class ResourceRegister
{
    protected $router;

    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy'];

    public function __construct(RouteCollector $router)
    {
        $this->router = $router;
    }

    public function register($name, $handler,array $options = [])
    {
        $middleware = [];

        $resourceMethods = $this->getResourceMethods($this->resourceDefaults, $options);

        if(isset($options['middleware']))
        {
            $middleware = $options['middleware'];
        }

        foreach ($resourceMethods as $method) {
            $this->{'addResource'.ucfirst($method)}(
                $name, $handler, $middleware
            );
        }
    }

    private function addResourceIndex($name, $handler, array $middleware = [])
    {
        $this->router->get($name,[$handler,'index'],['name' => $name,'middleware' => $middleware]);
    }

    private function addResourceCreate($name, $handler, array $middleware = [])
    {
        $this->router->get($name.'/create',[$handler,'create'],['name' => $name.'.create', 'middleware' => $middleware]);
    }

    private function addResourceStore($name, $handler, array $middleware = [])
    {
        $this->router->post($name.'/store',[$handler,'store'],['name' => $name.'.store', 'middleware' => $middleware]);
    }

    private function addResourceShow($name, $handler, array $middleware = [])
    {
        $this->router->get($name.'/{id}',[$handler,'show'],['name' => $name.'.show', 'middleware' => $middleware]);
    }

    private function addResourceEdit($name, $handler, array $middleware = [])
    {
        $this->router->get($name.'/{id}/edit',[$handler,'edit'],['name' => $name.'.edit','middleware' => $middleware]);
    }

    private function addResourceUpdate($name, $handler, array $middleware = [])
    {
        $this->router->addRoute('POST',$name.'/{id}/update',[$handler,'update'],['name' => $name.'.update','middleware' => $middleware]);
    }

    private function addResourceDestroy($name, $handler, array $middleware = [])
    {
        $this->router->delete($name.'/{id}/delete',[$handler,'destroy'],['name' => $name.'.destroy','middleware' => $middleware]);
    }

    private function getResourceMethods($defaults, $options)
    {
        $methods = $defaults;

        if (isset($options['only'])) {
            $methods = array_intersect($methods, (array) $options['only']);
        }

        if (isset($options['except'])) {
            $methods = array_diff($methods, (array) $options['except']);
        }

        return array_values($methods);
    }
}
