# Build a Better Router

At the moment the controller responsible for displaying a note is looking a bit messy. This is mostly because it's trying to handle multiple request types. A better router could handle some of this and allows for using a specific controller for each request type.

## The Current Setup
Currently the way `router.php` works is by requiring the array of routes from `routes.php`, parsing the current `$uri` and checking against the array of routes and if the `$uri` is found, matches it to the appropriate controller. This functionality can be extended by also checking for the request type. When setup correctly a route will look something like this:
```php
$router->get('/', 'controllers/index.php');
$router->delete('/note', 'controllers/notes/destroy.php');
```

## Router Class
There are a couple of different ways of appproaching this but in this instance router.php is converted into a class `Router.php`. As the router needs to be able to respond to each request type (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`) the class needs a method for each of these.

```php
namespace Core;

class Router {
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => 'GET',
        ];
    }
    // Repeat for each request type...
}
```

Each method will accept a `uri` and a `controller path` (similar to the previous setup) and the method will be hardcoded to match the request type. As each request comes in it will be cached by adding it to the $routes array.

`index.php` needs to be updated to create a new instance of the Router class instead of requiring the old router.
```php
$router = new Core\Router();
```

Then `routes.php` needs to be required. Because the `Router` class instance has already been declared this gets access to the contents of `routes.php`.

```php
$router = new Core\Router();

$routes = require(base_path("routes.php"));
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$router->route($uri, $routes);
```

The routes available in `routes.php` then populate the `$routes` array in the `Router` class instance.

## Defining the Request Type
Currently, forms are only able to send POST or GET requests so there needs to be a way to send PUT, PATCH and DELETE requests. A hidden field can be used within the form to achieve this.

```html
  <form class="mt-6" method="POST">
    <input type="hidden" name="_method" value="DELETE"> <--
    <input type="hidden" name="id" value="<?= $note['id'] ?>">
    <button class="text-sm text-red-500">Delete</button>
  </form>
```
*The name `_method` is intended to be unique so that it doesnt interfere with any other possible form attributes. This is a common convention when using this approach*

Now `_method` can be looked for in the `$_POST object` and if it exists it can be passed to the router to find the correct controller to handle the request.

**index.php**
```php
// Gets the _method if it exists. Otherwise, gets request method form server superglobal
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
$router->route($uri, $method);
```

**Router.php**
```php
/* 
 * Receives $uri and $method from index.php and loops over request methods until it finds a matching $uri and $method
 * in routes.php
 * Remember the Router class has access to routes.php because it is required after the class is instantiated in index.php
 */

public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return require(base_path($route['controller']));
            }
        }
        $this->abort();
    }
```

## Summary
A lot to digest in this one so here is a summary. Essentially what's created is something that is much more akin to a framework like Laravel.

- A request is sent from the browser
- Router checks against all of the available routes and methods until it finds a match
- When a match is found the router gets the required controller
- Controller executes necessary logic

**views/notes/show.view.php**
```html
<!--  -->

<main class="mx-auto max-w-7xl my-6 px-4 py-1 sm:px-6 lg:px-8">
  <div >
    <p><?= htmlspecialchars($note['body']); ?></p>
  </div>

  <div class="mt-6">
    <a href="/notes" class="text-blue-500 underline">Back to all notes...</a>
  </div>
  
  <form class="mt-6" method="POST">
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" name="id" value="<?= $note['id'] ?>">
    <button class="text-sm text-red-500">Delete</button>
  </form>
</main>

<!--  -->
```
**index.php**
```php
// 
$router = new Core\Router();

$routes = require(base_path("routes.php"));

$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];

$router->route($uri, $method);
```
**Router.php**
```php
<?php
namespace Core;

class Router {
    protected $routes = [];

    public function get($uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => 'GET',
        ];
    }

    // Repeat for each request type...

    public function delete($uri, $controller)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => 'DELETE',
        ];
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require(base_path("views/{$code}.php"));
        
        die();
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                return require(base_path($route['controller']));
            }
        }

        $this->abort();
    }
}
```
**routes.php**
```php
<?php

$router->get('/', 'controllers/index.php');
$router->get('/about', 'controllers/about.php');
$router->get('/contact', 'controllers/contact.php');

$router->get('/notes', 'controllers/notes/index.php');
$router->get('/note', 'controllers/notes/show.php');
$router->get('/note/create', 'controllers/notes/create.php');
```
