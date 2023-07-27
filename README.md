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
$r->addRoute($httpMethod, $routePattern, $handler, $options);
```
By default, the route parameters correspond to the regex `[^/]+`, but you can use your own regex. After the parameter name, write a separator `:` and a regex. Some examples:
```php
$r->get('user/{id:[0-9]}', [User::class,'show']);
$r->get('user/{name:[a-z]}', [User::class,'show']);
```
`$handler` The parameter does not necessarily have to be a callback, it could also be a controller class name or any other kind of data you wish to associate with the route. 
`$options` The parameter, you can specify the name and middlewares of the route

```php
$r->get('user/{id:[0-9]}', [User::class,'show'], ['name' => 'user', 'middleware' => [Auth::class]]);
```

#### Route Groups

Additionally, you can specify routes inside of a group. All routes defined inside a group will have a common prefix or name or middlewares.

For example, defining your routes as:

```php
$r->group(function (RouteCollector $r) {
    $r->addRoute('GET', '/do-something', 'handler');
    $r->addRoute('GET', '/do-another-thing', 'handler');
    $r->addRoute('GET', '/do-something-else', 'handler');
}, ['prefix' => 'prefix', 'name' => 'name', 'middleware' => [Middleare::class]);
```
#### Resource routes
The `resource()` method creates routes corresponding to CRUD.

```php
$r->resource('tests',Test::class);
```

| method  | action | uri | name |
| ------------- | ------------- | ------------- | ------------- |
| GET  | index  | tests  | tests.index  |
| GET  | create | tests/create  | tests.create  |
| POST | store  | tests/store  | tests.store  |
| GET  | show   | tests/{id}  | tests.show  |
| GET  | edit   | tests/{id}/edit  | tests.edit  |
| PUT/PATCH  | update  | tests/{id}/update  | tests.update  |
| DELETE  | destroy  | tests/{id}/destroy  | tests.destroy  |

You can also except methods or leave only those methods that should be used

```php
$r->resource('tests', Test::class, ['only' => ['index', 'show']]);
$r->resource('tests', Test::class, ['except' => ['create', 'store', 'update', 'destroy']]);
```
Adding middleware
```php
$r->resource('tests', Test::class, ['only' => ['index', 'show'], 'middleware' => [Middleware::class]);
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
