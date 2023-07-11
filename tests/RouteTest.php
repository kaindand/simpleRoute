<?php

namespace SimpleRoute\Tests;

use PHPUnit\Framework\TestCase;
use SimpleRoute\Route;

class RouteTest extends TestCase
{
    public function testMatchReturnsRouteNotFound()
    {
        $route = new Route('GET', '/path', 'Handler');
        $_SERVER['REQUEST_URI'] = '/wrong-path';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $result = $route->match();

        $this->assertEquals('routeNotFound', $result);
    }

    public function testMatchReturnsHttpMethodNotAllowed()
    {
        $route = new Route('GET', '/path', 'Handler');
        $_SERVER['REQUEST_URI'] = '/path';
        $_SERVER['REQUEST_METHOD'] = 'POST';

        $result = $route->match();

        $this->assertEquals('httpMethodNotAllowed', $result);
    }

    public function testMatchReturnsClassNotFound()
    {
        $route = new Route('GET', '/path', ['NonExistingClass', 'method']);
        $_SERVER['REQUEST_URI'] = '/path';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $result = $route->match();

        $this->assertEquals('classNotFound', $result);
    }

    public function testMatchReturnsMethodNotFound()
    {
        $route = new Route('GET', '/path', ['ExistingClass', 'nonExistingMethod']);
        $_SERVER['REQUEST_URI'] = '/path';
        $_SERVER['REQUEST_METHOD'] = 'GET';

        $result = $route->match();

        $this->assertEquals('methodNotFound', $result);
    }
}
