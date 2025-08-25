<?php

namespace App\Core;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch() {
        $uri = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'packages';
        $method = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes[$method] as $route => $action) {
            // Ubah rute menjadi pola regex
            $pattern = "@^" . preg_replace('/\{\w+\}/', '([\w-]+)', $route) . "$@";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Hapus full match
                list($controller, $methodName) = explode('@', $action);
                $this->callAction($controller, $methodName, $matches);
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found - Route: {$uri}";
    }

    protected function callAction($controller, $method, $params = []) {
        if (!class_exists($controller)) {
            throw new Exception("Controller {$controller} does not exist.");
        }

        $controllerInstance = new $controller;

        if (!method_exists($controllerInstance, $method) || !is_callable([$controllerInstance, $method])) {
            throw new Exception("Method {$method} does not exist in controller {$controller}.");
        }

        call_user_func_array([$controllerInstance, $method], $params);
    }
}