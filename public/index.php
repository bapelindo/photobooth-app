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

// --- TAMBAHKAN RUTE BARU DI SINI ---
$router->get('home/thankyou', 'App\Controllers\HomeController@thankyou');

// Definisikan route Anda yang lain
// Rute GET
$router->get('packages', 'App\Controllers\PackageController@index');
$router->get('photo/select_frame/{transaction_id}', 'App\Controllers\PhotoController@selectFrame');
$router->get('photo/capture/{transaction_id}/{frame_id}', 'App\Controllers\PhotoController@capture');
$router->get('photo/editor', 'App\Controllers\PhotoController@editor');
$router->get('photo/finalize/{photo_id}', 'App\Controllers\PhotoController@finalize');
$router->post('photo/ajax_save_captured_photos', 'App\Controllers\PhotoController@ajax_save_captured_photos');
$router->post('photo/ajax_save_final_photostrip', 'App\Controllers\PhotoController@ajax_save_final_photostrip');
$router->get('payment/process/{package_id}', 'App\Controllers\PaymentController@process');
$router->get('payment/finish', 'App\Controllers\PaymentController@finish');

// Rute POST (untuk callback dari Payment Gateway)
$router->post('payment/callback', 'App\Controllers\PaymentController@callback');

// Rute Autentikasi
$router->get('login', 'App\Controllers\AuthController@login');
$router->post('login', 'App\Controllers\AuthController@attemptLogin');
$router->get('logout', 'App\Controllers\AuthController@logout');


// Rute Admin
$router->get('admin', 'App\Controllers\AdminController@dashboard');
$router->get('admin/dashboard', 'App\Controllers\AdminController@dashboard');
$router->get('admin/camera', 'App\Controllers\AdminController@cameraControl');
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


// Rute POST (untuk AJAX)
$router->post('photo/ajax_save_photo', 'App\\Controllers\\PhotoController@ajax_save_photo');
$router->post('photo/ajax_print_photo', 'App\\Controllers\\PhotoController@ajax_print_photo');
$router->post('photo/ajax_capture_dslr', 'App\\Controllers\\PhotoController@ajax_capture_dslr');
$router->post('photo/send_email', 'App\\Controllers\\PhotoController@send_email');

$router->get('photo/selectFrame/{transaction_id}', 'App\Controllers\PhotoController@selectFrame');

$router->dispatch();