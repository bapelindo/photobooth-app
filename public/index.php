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
$router->post('photo/submit-frame-selection', 'App\Controllers\PhotoController@submitFrameSelection');
$router->get('photo/session/{session_id}', 'App\Controllers\PhotoController@photoSession');
$router->post('photo/save-session-photo', 'App\Controllers\PhotoController@saveSessionPhoto');
$router->post('photo/deleteSessionPhoto', 'App\Controllers\PhotoController@deleteSessionPhoto');
$router->post('photo/complete-session', 'App\Controllers\PhotoController@completeSession');
$router->get('photo/layout/{session_id}', 'App\Controllers\PhotoController@layoutEditor');
$router->post('photo/save-layouts', 'App\Controllers\PhotoController@saveLayouts');
$router->get('photo/decoration/{session_id}', 'App\Controllers\PhotoController@decorationEditor');
$router->post('photo/save-decorations', 'App\Controllers\PhotoController@saveDecorations');
$router->get('photo/finalize/{session_id}', 'App\Controllers\PhotoController@finalizeSession');
$router->post('photo/print-photostrip', 'App\Controllers\PhotoController@printPhotostrip');
$router->post('photo/send-session-email', 'App\Controllers\PhotoController@sendSessionEmail');
$router->get('photo/check-print-status/{session_id}', 'App\Controllers\PhotoController@checkPrintStatus');
// Removed legacy capture route - using new session workflow
// Removed legacy editor route - using new session workflow
// Removed duplicate route - using finalizeSession instead
// Removed legacy ajax_save_captured_photos route - using new session workflow
// Removed legacy ajax_save_final_photostrip route - using new session workflow
$router->get('payment/process/{package_id}', 'App\Controllers\PaymentController@process');
$router->get('payment/get-snap-token/{package_id}', 'App\Controllers\PaymentController@getSnapToken');
$router->get('payment/get-transaction-by-order/{order_id}', 'App\Controllers\PaymentController@getTransactionByOrder');
$router->get('payment/test-endpoint', 'App\Controllers\PaymentController@testEndpoint');
$router->get('payment/test-midtrans', 'App\Controllers\PaymentController@testMidtrans');
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

// New admin routes for enhanced workflow
$router->get('admin/sessions', 'App\Controllers\AdminController@listSessions');
$router->get('admin/sessions/view/{session_id}', 'App\Controllers\AdminController@viewSession');
$router->post('admin/sessions/delete/{session_id}', 'App\Controllers\AdminController@deleteSession');
$router->get('admin/photostrips', 'App\Controllers\AdminController@listPhotostrips');
$router->get('admin/photostrips/view/{photostrip_id}', 'App\Controllers\AdminController@viewPhotostrip');
$router->post('admin/photostrips/regenerate/{photostrip_id}', 'App\Controllers\AdminController@regeneratePhotostrip');
$router->get('admin/reports', 'App\Controllers\AdminController@reports');
$router->get('admin/settings', 'App\Controllers\AdminController@settings');
$router->post('admin/settings/update', 'App\Controllers\AdminController@updateSettings');


// Rute POST (untuk AJAX)
$router->post('photo/ajax_save_photo', 'App\\Controllers\\PhotoController@ajax_save_photo');
// Removed legacy ajax_print_photo route - using printPhotostrip for new session workflow
$router->post('photo/ajax_capture_dslr', 'App\\Controllers\\PhotoController@ajax_capture_dslr');
// Removed legacy send_email route - using sendSessionEmail for new session workflow

// Removed duplicate route - already defined as select_frame above

$router->dispatch();