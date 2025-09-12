<?php

namespace App\Core;

use Exception;
use App\Core\Session; // Add this line to import Session class

class Controller {

    public function __construct() {
        Session::start(); // Ensure session is started for all controllers

        // Define pages that do not require an active workflow session
        $public_pages = [
            '/packages', // The packages page itself
            '/login', // Login page
            '/admin/login', // Allow admin login to be accessed without workflow_step
            
            '/payment/process', // Allow payment process to initiate workflow
            '/photo/send_email', // Allow send_email to be accessed without workflow_step
            '/photo/ajax_print_photo', // Allow ajax_print_photo to be accessed without workflow_step
            '/photo/select_frame' // Allow frame selection to be accessed directly
        ];

        // Get the current request URI
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);

        // Normalize path: remove URLROOT and /public/ if present
        $url_root_path = parse_url(URLROOT, PHP_URL_PATH);
        if (strpos($path, $url_root_path) === 0) {
            $path = substr($path, strlen($url_root_path));
        }
        if (strpos($path, '/public') === 0) {
            $path = substr($path, strlen('/public'));
        }
        // Ensure path starts with a single slash
        $path = '/' . ltrim($path, '/');

        error_log('Controller __construct: Current path: ' . $path);

        $is_public_page = in_array($path, $public_pages);
        $is_payment_process = strpos($path, '/payment/process') === 0;
        $is_frame_selection = strpos($path, '/photo/select_frame') === 0;

        error_log('Controller __construct: is_public_page: ' . ($is_public_page ? 'true' : 'false'));
        error_log('Controller __construct: is_payment_process: ' . ($is_payment_process ? 'true' : 'false'));
        error_log('Controller __construct: is_frame_selection: ' . ($is_frame_selection ? 'true' : 'false'));

        // Check if the current page is a public page, payment initiation page, or frame selection
        if ($is_public_page || $is_payment_process || $is_frame_selection) {
            error_log('Controller __construct: Path is public, payment process, or frame selection, returning.');
            return; // No session check needed for public pages, payment initiation, or frame selection
        }

        // If workflow_step is not set, redirect to packages
        if (ENABLE_SESSION_REFRESH_BACK && !Session::get('workflow_step')) {
            error_log('Controller __construct: workflow_step not set, redirecting to packages.');
            header('Location: /photobooth-app/public/packages');
            exit();
        }
    }
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
            throw new \Exception("View {$view} not found.");
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
            throw new \Exception("View {$view} not found.");
        }
    }

    protected function redirect($url) {
        header('Location: ' . \URLROOT . '/' . $url);
        exit();
    }
    
    /**
     * Menyimpan pesan flash ke sesi dan mengalihkan pengguna.
     * @param string $url Tujuan redirect.
     * @param string $message Pesan yang akan ditampilkan.
     */
    protected function flashAndRedirect($url, $message) {
        Session::set('flash_message', $message);
        $this->redirect($url);
    }
}