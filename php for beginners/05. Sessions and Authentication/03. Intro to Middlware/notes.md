# Introduction to Middleware

Currently in the app there are routes that need to be restricted depending on the users authentication status. For example the notes section should really be resrtricted to authenticated users whereas the register route should be restricted to anonymous users.

This *could* be achieved by adding a check in the controller, for example:

```php
if (!$_SESSION['user']) {
    header('location: /login');
}
```

but that would mean duplicating that code for every controller that requires some sort of check.

A better way is by using middleware on the routes to check conditions and only allow those routes to be visited if the conditions are met.

## Modify the Router
As the middleware will act directly on the routes the router seems a logical place to add the methods. But if we add the middleware method to the router and chain it on to the route, it will throw an error.

```php
// routes.php
$router->get('/register', 'controllers/registration/register.php')->only('guest');

// Router.php
public function only($key)
{
    dd($key);
}
```

This is because when chaining methods the next method in the chain expects to act on whatever is returned from the last, but the `get()` method doesn't return anything so the `only()` method is trying to act on `NULL`.

## Return to Sender
To overcome this problem the router object needs to be returned from the route methods so that the next method (in this case `only()`) has something to chain onto. 

The middleware key is also added with a default value of `null` so that it can be updated by the `only()` method. Technically, this doesnt *need* to be added here, as the `only()` function will add this key to the array anyway but if we need to refer to it later adding it here can help avoid `undefined index` warnings.

```php
public function add($uri, $controller, $method)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null,
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add($uri, $controller, 'GET');
    }
```
*The `get()` method is fired which fires the `add()` method. `add()` returns an object inside `get()` so `get()` also `returns` the object, making it available to the `only()` function.*

## Add it to the Router
To actually use the middleware it needs to be checked for when routing the user to an endpoint. To do this, add conditionals to the `route()` method in the router, that look for the middleware and respond accordingly.

```php
    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                // Check for middleware
                if ($route['middleware'] === 'guest') {
                    if ($_SESSION['user'] ?? false) {
                        header('location: /');
                        exit();
                    }
                }
                return require(base_path($route['controller']));
            }
        }

        $this->abort();
    }
```

## A Little Class
As usual this can be extracted into a class of it's own, and it makes sense to do so. Routers can easily end up dealing with many routes protected by various middleware and it would quickly get bloated if they were all added directly to the router as in the previous example. 

The middleware code that was just added can be broken down into a couple of distinct sections.
```php
if ($route['middleware'] === 'guest') { // This line checks the middleware key
    if ($_SESSION['user'] ?? false) { // This line checks for a user in $_SESSION
        header('location: /');
        exit();
    }
}
```

These can be split into separate classes and then the conditional can be simplified to fetch the correct middleware based on the middleware key present in `$route`.

In Core create a Middleware directory and inside that create three files:
- Guest.php
- Auth.php
- Middleware.php

The first two will be a straightfoward extraction of the existing logic in the Route() conditional.

```php
<?php

namespace Core\Middleware;

class Guest() {
    public function handle()
    {
        if ($_SESSION['user'] ?? false) {
            header('location: /');
            exit();
        }
    }
}
```
*Do this for `Auth.php` too*

Then a lookup table can be created that will accept the middleware key and use that to find the appropriate middleware for the current route. That is what the third file, `Middleware.php`, will be.

**Middleware.php**
```php
<?php

namespace Core\Middleware;

class Middleware {
    const MAP = [
        'guest' => Guest::class,
        'auth' => Auth::class,
    ];
}
```

With this in place the checks in the router can be simplified, like so:

**Router.php**
```php
public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                if ($route['middleware']) {
                    $middleware = Middleware::MAP[$route['middleware']];

                    (new $middleware)->handle(); // Beware the new syntax on this line
                }

                return require(base_path($route['controller']));
            }
        }

        $this->abort();
    }
```

This can be refined even futher by extracting this functionality into `Middleware.php`.

```php
    public static function resolve($key)
    {
        if (!$key) {
            return;
        }

        $middleware = static::MAP[$key] ?? false;

        if (!$middleware) {
            throw new \Exception('No matching middleware found for ' . $key . '.');
        }

        (new $middleware)->handle();
    }
```

Then in the router the call for middleware becomes even simpler:

```php
public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                
                Middleware::resolve($route['middleware']);

                return require(base_path($route['controller']));
            }
        }

        $this->abort();
    }
```