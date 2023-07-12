SimpleRoute - SimpleRoute router for PHP
=======================================

This library provides a simple implementation of a regular expression based router. 

Install
-------

To install with composer:

```sh
composer require simple-route/simple-route
```

Requires PHP 7.4 or newer.

Usage
-----

Here's a basic usage example:

```php
<?php

require '/path/to/vendor/autoload.php';

$r = new RouteCollector();

$r->addRoute('GET', 'home', function(){
    echo "Hello World";
});
$r->get('users', [User::class,'index']);
$r->get('user/{id:[0-9]}', [User::class,'show']);

$dispatcher = new Dispatcher($r);

try {
  $dispatcher->dispatch();
} catch (BadRouteException $e) {
  // ... 404 Not Found
} catch (MethodNotAllowedException $e) {
  // ... 405 Method Not Allowed
}
```

### Add routes

To add a route,first you need to create an instance of the class `SimpleRoute\RouteCollector` 
```php
$r = new RouteCollector();
```
The routes are added by calling addRoute()
```php
$r->addRoute($httpMethod, $routePattern, $handler, $name = '');
```
By default the $routePattern uses a syntax where `{foo}` specifies a placeholder with name foo and matching the regex `[^/]+`. To adjust the pattern the placeholder matches, you can specify a custom pattern by writing `{bar:[0-9]+}`. Some examples:
```php
$r->get('user/{id:[0-9]}', [User::class,'show']);
$r->get('user/{name:[a-z]}', [User::class,'show']);
```
`$handler` The parameter does not necessarily have to be a callback, it could also be a controller class name or any other kind of data you wish to associate with the route. 
`$name` this is an optional parameter

#### Route Groups

Additionally, you can specify routes inside of a group. All routes defined inside a group will have a common prefix or name.

For example, defining your routes as:

```php
$r->group(function (RouteCollector $r) {
    $r->addRoute('GET', '/do-something', 'handler');
    $r->addRoute('GET', '/do-another-thing', 'handler');
    $r->addRoute('GET', '/do-something-else', 'handler');
}, ['prefix' => 'prefix', 'name' => 'name']);
```
### Dispatching routes
To start processing routes, you need to create an instance of the dispatcher class and pass it a list of routes, and then call the dispacth method
```php
$dispatcher = new Dispatcher($r);

try {
  $dispatcher->dispatch();
} catch (BadRouteException $e) {
  // ... 404 Not Found
} catch (MethodNotAllowedException $e) {
  // ... 405 Method Not Allowed
}
```
