<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller {
    public function __construct() {
        // Tidak ada model yang dibutuhkan untuk controller ini
    }

    public function index() {
        // Arahkan ke halaman pemilihan paket
        header('location: ' . URLROOT . '/packages');
        exit();
    }
    
    // --- TAMBAHKAN METODE BARU INI ---
    public function thankyou() {
        $this->view('home/thankyou');
    }
}