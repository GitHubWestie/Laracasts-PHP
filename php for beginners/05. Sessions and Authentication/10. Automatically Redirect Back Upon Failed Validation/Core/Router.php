<?php

namespace Core;
use Core\Middleware\Middleware;

class Router {
    protected $routes = [];

    public function add($uri, $controller, $method)
    {
        $this->routes[] = [
            'uri' => $uri,
            'controller' => $controller,
            'method' => $method,
            'middleware' => null, // Added this line
        ];

        return $this;
    }

    public function get($uri, $controller)
    {
        return $this->add($uri, $controller, 'GET');
    }

    public function post($uri, $controller)
    {
        return $this->add($uri, $controller, 'POST');
    }

    public function put($uri, $controller)
    {
        return $this->add($uri, $controller, 'PUT');
    }

    public function patch($uri, $controller)
    {
        return $this->add($uri, $controller, 'PATCH');
    }

    public function delete($uri, $controller)
    {
        return $this->add($uri, $controller, 'DELETE');
    }

    public function only($key)
    {
        $this->routes[array_key_last($this->routes)]['middleware'] = $key;

        return $this;
    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        require(base_path("views/{$code}.php"));
        
        die();
    }

    public function previousUrl() {
        return $_SERVER['HTTP_REFERER'];
    }

    public function route($uri, $method)
    {
        foreach ($this->routes as $route) {
            if($route['uri'] === $uri && $route['method'] === strtoupper($method)) {
                
                Middleware::resolve($route['middleware']);

                return require(base_path('Http/Controllers/' . $route['controller']));
            }
        }

        $this->abort();
    }
}
