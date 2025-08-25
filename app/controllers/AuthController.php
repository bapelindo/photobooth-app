<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AuthController extends Controller
{
    public function login()
    {
        // Jika sudah login, arahkan ke dashboard
        if (Session::has('admin_id')) {
            $this->redirect('admin/dashboard');
        }
        $this->view('auth/login');
    }

    public function attemptLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $adminModel = $this->model('Admin');
            $admin = $adminModel->findByUsername($username);

            if ($admin && password_verify($password, $admin->password)) {
                // Login berhasil, simpan data ke session
                Session::set('admin_id', $admin->id);
                Session::set('admin_username', $admin->username);
                $this->redirect('admin/dashboard');
            }

            // Login gagal
            Session::set('error_message', 'Invalid username or password');
            $this->redirect('login');
        }
    }

    public function logout()
    {
        Session::destroy();
        $this->redirect('login');
    }
}