# Make a PHP Router

Creat a router.php file at the root level and import this into the index.php file.

Get the current uri and set to `$uri`
```php
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];
```
*`parse_url()` makes sure any query strings are removed from the uri so that the routes match correctly*

Create a mapping of routes using an assoc array
```php
$routes = [
    '/' => 'Controllers/index.php',
    '/about' => 'Controllers/about.php',
    '/contact' => 'Controllers/contact.php',
];
```

Use `array_key_exists()` to match the current $uri to the a route and fetch the appropriate controller. This is tucked inside a function that is called at the end of the file.
```php
function routeToController($uri, $routes) {
    if(array_key_exists($uri, $routes)) {
        require($routes[$uri]);
    } else {
        abort();
    }
}
```

Create a `404` response and page for cases where a uri doesnt exist. The `$code` parameter is set to a default of `404` but will be overridden should any value be passed to the function.
```php
function abort($code = 404) {
    http_response_code($code);

    require("views/{$code}.php");

    die();
}
```

And finally call the `routeToController()` function
```php
routeToController($uri, $routes);
```

