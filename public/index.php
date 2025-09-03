<?php

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Lax'
]);

session_start();

require_once '../config/config.php';
require_once '../vendor/autoload.php';

use App\Core\Router;

$router = new Router();

// --- Rute Pengguna (Photobooth Workflow) ---
$router->get('packages', 'App\Controllers\PackageController@index');
$router->get('payment/process/{package_id}', 'App\Controllers\PaymentController@process');
$router->get('payment/finish', 'App\Controllers\PaymentController@finish');
$router->post('payment/callback', 'App\Controllers\PaymentController@callback');
$router->get('photo/selectFrame/{transaction_id}', 'App\Controllers\PhotoController@selectFrame');

// REVISI: Rute untuk capture sekarang menangani POST (dari pemilihan frame) dan GET (untuk halaman sesi)
$router->post('photo/capture/{transaction_id}', 'App\Controllers\PhotoController@capture');
$router->get('photo/capture/{transaction_id}', 'App\Controllers\PhotoController@capture');

// Rute Baru untuk Editor
$router->get('photo/layoutEditor/{transaction_id}', 'App\Controllers\PhotoController@layoutEditor');
$router->get('photo/editor/{transaction_id}', 'App\Controllers\PhotoController@editor');
$router->get('photo/finalize/{transaction_id}', 'App\Controllers\PhotoController@finalize');


// --- Rute AJAX ---
$router->post('photo/ajax_save_raw_photos', 'App\Controllers\PhotoController@ajax_save_raw_photos');
$router->post('photo/ajax_process_layout', 'App\Controllers\PhotoController@ajax_process_layout');
$router->post('photo/ajax_save_final_photostrip', 'App\Controllers\PhotoController@ajax_save_final_photostrip');
$router->post('photo/send_email', 'App\Controllers\PhotoController@send_email');
$router->post('photo/ajax_print_photo', 'App\Controllers\PhotoController@ajax_print_photo');


// --- Rute Admin ---
$router->get('login', 'App\Controllers\AuthController@login');
$router->post('login', 'App\Controllers\AuthController@attemptLogin');
$router->get('logout', 'App\Controllers\AuthController@logout');

$router->get('admin', 'App\Controllers\AdminController@dashboard');
$router->get('admin/dashboard', 'App\Controllers\AdminController@dashboard');
$router->get('admin/packages', 'App\Controllers\AdminController@listPackages');
$router->get('admin/packages/create', 'App\Controllers\AdminController@createPackage');
$router->post('admin/packages/store', 'App\Controllers\AdminController@storePackage');
$router->get('admin/packages/edit/{id}', 'App\Controllers\AdminController@editPackage');
$router->post('admin/packages/update/{id}', 'App\Controllers\AdminController@updatePackage');
$router->post('admin/packages/delete/{id}', 'App\Controllers\AdminController@deletePackage');
$router->get('admin/assets', 'App\Controllers\AdminController@listAssets');
$router->get('admin/assets/create', 'App\Controllers\AdminController@createAsset');
$router->post('admin/assets/store', 'App\Controllers\AdminController@storeAsset');
$router->post('admin/assets/delete/{id}', 'App\Controllers\AdminController@deleteAsset');
$router->get('admin/assets/editFrame/{id}', 'App\Controllers\AdminController@editFrame');
$router->post('admin/assets/ajax_save_frame_data', 'App\Controllers\AdminController@ajax_save_frame_data');
$router->get('admin/gallery', 'App\Controllers\AdminController@showGallery');
$router->post('admin/gallery/delete/{id}', 'App\Controllers\AdminController@deletePhoto');
$router->get('admin/camera', 'App\Controllers\AdminController@cameraControl');


// Jalankan Router
$router->dispatch();