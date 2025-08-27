<?php

namespace App\Core;

use Exception;

class Router {
    protected $routes = [
        'GET' => [],
        'POST' => []
    ];

    public function __construct() {
        $this->registerAdminPackageRoutes();
        $this->registerAdminAssetRoutes();
        $this->registerPhotoRoutes();
    }

    public function get($uri, $action) {
        $this->routes['GET'][$uri] = $action;
    }

    public function post($uri, $action) {
        $this->routes['POST'][$uri] = $action;
    }

    protected function registerAdminPackageRoutes() {
        $this->get('admin/packages', 'App\Controllers\AdminController@listPackages');
        $this->get('admin/packages/create', 'App\Controllers\AdminController@createPackage');
        $this->post('admin/packages/store', 'App\Controllers\AdminController@storePackage');
        $this->get('admin/packages/edit/{id}', 'App\Controllers\AdminController@editPackage');
        $this->post('admin/packages/update/{id}', 'App\Controllers\AdminController@updatePackage');
        $this->post('admin/packages/delete/{id}', 'App\Controllers\AdminController@deletePackage');
    }

    protected function registerAdminAssetRoutes() {
        $this->get('admin/assets', 'App\Controllers\AdminController@listAssets');
        $this->get('admin/assets/create', 'App\Controllers\AdminController@createAsset');
        $this->post('admin/assets/store', 'App\Controllers\AdminController@storeAsset');
        $this->post('admin/assets/delete/{id}', 'App\Controllers\AdminController@deleteAsset');
        $this->get('admin/assets/editFrame/{id}', 'App\Controllers\AdminController@editFrame');
        $this->post('admin/assets/ajax_save_frame_data', 'App\Controllers\AdminController@ajax_save_frame_data');
    }

    protected function registerPhotoRoutes() {
        $this->get('photo/select_frame/{transaction_id}', 'App\Controllers\PhotoController@selectFrame');
        $this->get('photo/capture/{transaction_id}/{frame_id}', 'App\Controllers\PhotoController@capture');
        $this->get('photo/editor', 'App\Controllers\PhotoController@editor');
        $this->get('photo/finalize/{photo_id}', 'App\Controllers\PhotoController@finalize');
        $this->post('photo/ajax_save_captured_photos', 'App\Controllers\PhotoController@ajax_save_captured_photos');
        $this->post('photo/ajax_save_final_photostrip', 'App\Controllers\PhotoController@ajax_save_final_photostrip');
    }

    public function dispatch() {
        $uri = isset($_GET['url']) ? rtrim($_GET['url'], '/') : 'packages';
        $method = $_SERVER['REQUEST_METHOD'];

        if (array_key_exists($uri, $this->routes[$method])) {
            list($controller, $methodName) = explode('@', $this->routes[$method][$uri]);
            $this->callAction($controller, $methodName);
            return;
        }

        foreach ($this->routes[$method] as $route => $action) {
            // Corrected the regex pattern
            $pattern = "@^" . preg_replace('/\{\\w+\}/', '([\\w-]+)', $route) . "$@";

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
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
