<?php
// Pastikan helper ini di-load di public/index.php
// require_once '../app/helpers/session_helper.php';

function isLoggedIn() {
    return isset($_SESSION['admin_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('location: ' . URLROOT . '/admin/login');
        exit();
    }
}