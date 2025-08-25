<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function __construct() {
        // No models needed for a simple redirect
    }

    public function index() {
        // Redirect to the new package selection flow
        header('location: ' . URLROOT . '/packages');
        exit();
    }
}