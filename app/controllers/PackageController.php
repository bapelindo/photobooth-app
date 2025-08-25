<?php

namespace App\Controllers;

use App\Core\Controller;

class PackageController extends Controller {
    
    public function index() {
        $packageModel = $this->model('Package');
        $data['packages'] = $packageModel->getAll();
        
        $this->view('packages/show', $data);
    }
}