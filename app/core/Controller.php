<?php

namespace App\Core;

class Controller {
    protected function model($model) {
        $model = 'App\\Models\\' . $model;
        return new $model();
    }

    protected function view($view, $data = []) {
        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            // Ekstrak data agar bisa diakses sebagai variabel di view
            extract($data);
            
            require_once $viewFile;
        } else {
            throw new Exception("View {$view} not found.");
        }
    }

    // Metode baru untuk view admin dengan layout
    protected function adminView($view, $data = []) {
        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            extract($data);
            
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
            
            require_once '../app/views/admin/layout.php';
        } else {
            throw new Exception("View {$view} not found.");
        }
    }

    protected function redirect($url) {
        header('Location: ' . \URLROOT . '/' . $url);
        exit();
    }
}
