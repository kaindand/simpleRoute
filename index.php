<?php
declare(strict_types=1);
define('ROOT', str_replace('\\', '/', __DIR__));

include_once ROOT .'/vendor/autoload.php';

use SimpleRoute\Aboba;
use SimpleRoute\RouteCollector;
use SimpleRoute\Dispatcher;

$dispatcher;

$routes = new RouteCollector();

$routes->group(function($routes){
    $routes->addRoute('GET','a/{a}/{b}',[Aboba::class,'hru'],['a'=>'[0-9]'],"as");
}, 'as');

$dispatcher = new Dispatcher($routes);

$dispatcher->dispatch();

?>
