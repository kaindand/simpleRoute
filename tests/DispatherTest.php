<?php

namespace SimpleRoute\Tests;

use PHPUnit\Framework\TestCase;
use SimpleRoute\Tests\DummyRouteCollector;
use SimpleRoute\Dispatcher;

class DispatherTest extends TestCase
{
    public function testDispatchThrowsBadRouteException()
    {
        $routes = new DummyRouteCollector();
        $dispatcher = new Dispatcher($routes);

        $this->expectException(BadRouteException::class);

        $dispatcher->dispatch();
    }

    
    public function testDispatchThrowsMethodNotAllowedException()
    {
        $routes = new DummyRouteCollector();
        
        $routes->addRoute('GET', '/path', 'Handler');
        $_SERVER['REQUEST_URI'] = '/path';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $dispatcher = new Dispatcher($routes);

        $this->expectException(MethodNotAllowedException::class);

        $dispatcher->dispatch();
    }

    public function testGenerateReturnsCorrectUrl()
    {
        $routes = new DummyRouteCollector();
        $routes->addRoute('GET', '/path/{id}', 'Handler', '', null, 'routeName');

        $dispatcher = new Dispatcher($routes);

        $url = $dispatcher->generate('routeName', ['id' => 123]);

        $this->assertEquals('/path/123', $url);
    }
}
