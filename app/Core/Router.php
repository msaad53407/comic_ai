<?php

namespace App\Core;

class Router
{
    private $routes = [];

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function resolve()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];

        // Try exact match first
        $callback = $this->routes[$method][$path] ?? false;

        // If no exact match, try pattern matching for parameters
        if ($callback === false) {
            foreach ($this->routes[$method] ?? [] as $route => $routeCallback) {
                $pattern = preg_replace('/:[a-zA-Z0-9_]+/', '([a-zA-Z0-9_]+)', $route);
                $pattern = '#^' . $pattern . '$#';

                if (preg_match($pattern, $path, $matches)) {
                    array_shift($matches); // Remove full match
                    $callback = $routeCallback;
                    $params = $matches;
                    break;
                }
            }
        }

        if ($callback === false) {
            http_response_code(404);
            echo "404 - Not Found. Path: " . $path . " Method: " . $method;
            return;
        }

        if (is_array($callback)) {
            $controller = new $callback[0]();
            $action = $callback[1];
            return call_user_func([$controller, $action]);
        }

        return call_user_func_array($callback, $params ?? []);
    }
}
