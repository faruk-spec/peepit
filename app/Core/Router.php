<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $notFoundCallback;

    public function add($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'callback' => $callback
        ];
    }

    public function get($path, $callback)
    {
        $this->add('GET', $path, $callback);
    }

    public function post($path, $callback)
    {
        $this->add('POST', $path, $callback);
    }

    public function setNotFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function dispatch($url, $method)
    {
        // Parse and normalize URL
        $url = parse_url($url, PHP_URL_PATH);
        $url = '/' . trim($url, '/');
        $url = ($url === '/') ? '/' : rtrim($url, '/');

        foreach ($this->routes as $route) {
            // Normalize route path
            $routePath = '/' . trim($route['path'], '/');
            $routePath = ($routePath === '/') ? '/' : rtrim($routePath, '/');
            
            $pattern = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $routePath);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === strtoupper($method) && preg_match($pattern, $url, $matches)) {
                array_shift($matches);
                return call_user_func_array($route['callback'], $matches);
            }
        }

        if ($this->notFoundCallback) {
            return call_user_func($this->notFoundCallback);
        }

        http_response_code(404);
        echo '404 - Page Not Found';
    }
}
