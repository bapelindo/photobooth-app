<?php

namespace App\Core;

use Exception;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    // --- TAMBAHAN: Konstruktor untuk mendaftarkan rute ---
    public function __construct() {
        // Mendaftarkan rute-rute yang hilang untuk admin packages
        $this->registerAdminPackageRoutes();
    }

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    // --- TAMBAHAN: Method untuk mendaftarkan rute admin ---
    protected function registerAdminPackageRoutes() {
        // Rute untuk menampilkan semua package (sudah ada kemungkinan)
        $this->get('admin/packages', 'App\Controllers\AdminController@listPackages');
        
        // Rute untuk menampilkan form pembuatan package baru
        $this->get('admin/packages/create', 'App\Controllers\AdminController@createPackage');
        
        // Rute untuk menyimpan package baru dari form (method POST)
        $this->post('admin/packages/store', 'App\Controllers\AdminController@storePackage');
        
        // RUTE YANG HILANG: Menampilkan form edit package
        $this->get('admin/packages/edit/{id}', 'App\Controllers\AdminController@editPackage');
        
        // Rute untuk mengupdate data package (method POST)
        $this->post('admin/packages/update/{id}', 'App\Controllers\AdminController@updatePackage');
        
        // Rute untuk menghapus package (method POST)
        $this->post('admin/packages/delete/{id}', 'App\Controllers\AdminController@deletePackage');
    }

    public function dispatch() {
        $uri = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'packages';
        $method = $_SERVER['REQUEST_METHOD'];

        // Cek apakah ada rute yang cocok
        if (array_key_exists($uri, $this->routes[$method])) {
            list($controller, $methodName) = explode('@', $this->routes[$method][$uri]);
            $this->callAction($controller, $methodName);
            return;
        }

        // Cek rute dengan parameter dinamis (seperti {id})
        foreach ($this->routes[$method] as $route => $action) {
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
        $controller = str_replace('/', '\\', $controller);
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