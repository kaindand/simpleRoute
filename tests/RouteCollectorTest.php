<?php
namespace SimpleRoute\Tests;

use PHPUnit\Framework\TestCase;
use SimpleRoute\Route;
use SimpleRoute\Tests\DummyRouteCollector;

class RouteCollectorTest extends TestCase
{
    
    public function testAddRoute(string $prefix = '', string $name = ''){
        $r = new DummyRouteCollector();

        $r->get('get',[Test::class,'test']);
        $r->post('post',[Test::class,'test']);
        $r->put('put',[Test::class,'test']);
        $r->patch('patch',[Test::class,'test']);
        $r->delete('delete',[Test::class,'test']);
        $r->any('any',[Test::class,'test']);

        $excepted = [
            0 => new Route('GET','get',[Test::class,'test']),
            1 => new Route('POST','post',[Test::class,'test']),
            2 => new Route('PUT','put',[Test::class,'test']),
            3 => new Route('PATCH','patch',[Test::class,'test']),
            4 => new Route('DELETE','delete',[Test::class,'test']),
            5 => new Route($_SERVER['REQUEST_METHOD'],'any',[Test::class,'test']),
        ];

        $this->assertEquals($excepted,$r->getRoutes());
    }

    public function testAddGroup(){
        $r = new DummyRouteCollector();

        $r->group(function(DummyRouteCollector $r){
            $r->get('get',[Test::class,'test']);
            $r->post('post',[Test::class,'test']);
            $r->put('put',[Test::class,'test']);
            $r->patch('patch',[Test::class,'test']);
            $r->delete('delete',[Test::class,'test']);
            $r->any('any',[Test::class,'test']);

            $r->group(function(DummyRouteCollector $r){
                $r->get('get',[Test::class,'test']);
                $r->post('post',[Test::class,'test']);
                $r->put('put',[Test::class,'test']);
                $r->patch('patch',[Test::class,'test']);
                $r->delete('delete',[Test::class,'test']);
                $r->any('any',[Test::class,'test']);
            },['prefix' => 'prefix-two/','name' => '.name-two']);
        },['prefix' => 'prefix-one/','name' => 'name-one']);

        $excepted = [
            0 => new Route('GET','prefix-one/get',[Test::class,'test'],'name-one'),
            1 => new Route('POST','prefix-one/post',[Test::class,'test'],'name-one'),
            2 => new Route('PUT','prefix-one/put',[Test::class,'test'],'name-one'),
            3 => new Route('PATCH','prefix-one/patch',[Test::class,'test'],'name-one'),
            4 => new Route('DELETE','prefix-one/delete',[Test::class,'test'],'name-one'),
            5 => new Route($_SERVER['REQUEST_METHOD'],'prefix-one/any',[Test::class,'test'],'name-one'),
            6 => new Route('GET','prefix-one/prefix-two/get',[Test::class,'test'],'name-one.name-two'),
            7 => new Route('POST','prefix-one/prefix-two/post',[Test::class,'test'],'name-one.name-two'),
            8 => new Route('PUT','prefix-one/prefix-two/put',[Test::class,'test'],'name-one.name-two'),
            9 => new Route('PATCH','prefix-one/prefix-two/patch',[Test::class,'test'],'name-one.name-two'),
            10 => new Route('DELETE','prefix-one/prefix-two/delete',[Test::class,'test'],'name-one.name-two'),
            11 => new Route($_SERVER['REQUEST_METHOD'],'prefix-one/prefix-two/any',[Test::class,'test'],'name-one.name-two'),
        ];

        $this->assertEquals($excepted,$r->getRoutes());
    }
}
