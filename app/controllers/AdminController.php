<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AdminController extends Controller {

    /**
     * Middleware: Memastikan hanya admin yang sudah login yang bisa mengakses.
     */
    public function __construct()
    {
        Session::start();
        if (!Session::has('admin_id')) {
            $this->redirect('login');
        }
    }

    /**
     * Menampilkan halaman dashboard admin.
     */
    public function dashboard()
    {
        $transactionModel = $this->model('Transaction');
        $packageModel = $this->model('Package');

        $data['summary'] = $transactionModel->getSummary();
        $data['popular_packages'] = $packageModel->getPopularPackages(3);
        $data['title'] = 'Dashboard';

        $this->adminView('admin/dashboard/index', $data);
    }

    // === PACKAGE MANAGEMENT (DIREVISI) ===

    /**
     * Menampilkan daftar semua paket.
     */
    public function listPackages()
    {
        $packageModel = $this->model('Package');
        $data['packages'] = $packageModel->getAll();
        $data['title'] = 'Manage Packages';
        $this->adminView('admin/packages/index', $data);
    }

    /**
     * Menampilkan form untuk membuat paket baru.
     */
    public function createPackage()
    {
        $data['title'] = 'Create New Package';
        // Menggunakan view 'create' untuk membuat paket baru
        $this->adminView('admin/packages/create', $data);
    }

    /**
     * Menyimpan data paket baru ke database.
     */
    public function storePackage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'photo_limit' => $_POST['photo_limit'],
                'frame_count' => $_POST['frame_count'],
                'session_time_limit' => $_POST['session_time_limit'],
                'photo_shot_limit' => $_POST['photo_shot_limit'],
                'retake_limit' => $_POST['retake_limit'] ?? 0,
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->create($data)) {
                $this->redirect('admin/packages');
            } else {
                $this->flashAndRedirect('admin/packages', 'Gagal menyimpan paket.', 'error');
            }
        }
    }

    /**
     * Menampilkan form untuk mengedit paket yang ada.
     */
    public function editPackage($id)
    {
        $packageModel = $this->model('Package');
        $package = $packageModel->find($id);

        if ($package) {
            $data['package'] = $package;
            $data['title'] = 'Edit Package';
            // REVISI: Menggunakan view 'create' yang sama untuk mengedit, dengan melewatkan data paket
            $this->adminView('admin/packages/create', $data);
        } else {
            $this->flashAndRedirect('admin/packages', 'Paket tidak ditemukan.', 'error');
        }
    }

    /**
     * Memperbarui data paket di database.
     */
    public function updatePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'photo_limit' => $_POST['photo_limit'],
                'frame_count' => $_POST['frame_count'],
                'session_time_limit' => $_POST['session_time_limit'],
                'photo_shot_limit' => $_POST['photo_shot_limit'],
                'retake_limit' => $_POST['retake_limit'] ?? 0,
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->update($id, $data)) {
                $this->redirect('admin/packages');
            } else {
                $this->flashAndRedirect('admin/packages', 'Gagal memperbarui paket.', 'error');
            }
        }
    }

    /**
     * Menghapus paket dari database.
     */
    public function deletePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $packageModel = $this->model('Package');
            if ($packageModel->delete($id)) {
                $this->redirect('admin/packages');
            } else {
                $this->flashAndRedirect('admin/packages', 'Gagal menghapus paket.', 'error');
            }
        }
    }

    // === ASSET MANAGEMENT (TETAP SAMA) ===

    public function listAssets()
    {
        $assetModel = $this->model('Asset');
        $data['assets'] = $assetModel->getAll();
        $data['title'] = 'Manage Assets';
        $this->adminView('admin/assets/index', $data);
    }

    public function createAsset()
    {
        $data['title'] = 'Upload New Asset';
        $this->adminView('admin/assets/create', $data);
    }

    public function storeAsset()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $assetType = $_POST['type'];
            $dbPath = '';

            // Handle file upload untuk frame/sticker, atau value untuk filter
            if ($assetType === 'filter') {
                $dbPath = $_POST['asset_value']; // Get CSS value
            } else if (isset($_FILES['asset_file']) && $_FILES['asset_file']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['asset_file'];
                $uploadDir = "../public/assets/{$assetType}s/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = uniqid() . '-' . basename($file['name']);
                $destination = $uploadDir . $filename;
                $dbPath = "/assets/{$assetType}s/" . $filename;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                     $this->flashAndRedirect('admin/assets', 'Gagal menyimpan file asset.', 'error');
                }
            } else {
                $this->flashAndRedirect('admin/assets', 'Tidak ada file atau nilai yang diberikan untuk asset.', 'error');
            }

            $data = [
                'name' => $_POST['name'],
                'type' => $assetType,
                'file_path' => $dbPath
            ];
            
            $assetModel = $this->model('Asset');
            if ($assetModel->create($data)) {
                $this->redirect('admin/assets');
            } else {
                $this->flashAndRedirect('admin/assets', 'Gagal menyimpan asset ke database.', 'error');
            }
        }
    }

    public function deleteAsset($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $assetModel = $this->model('Asset');
            $asset = $assetModel->find($id);

            if ($asset && $asset->type !== 'filter') { // Jangan hapus file untuk filter
                $filePath = '../public' . $asset->path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            if ($asset) {
                $assetModel->delete($id);
            }
            $this->redirect('admin/assets');
        }
    }

    public function editFrame($id)
    {
        $assetModel = $this->model('Asset');
        $asset = $assetModel->find($id);

        if (!$asset || $asset->type !== 'frame') {
            $this->flashAndRedirect('admin/assets', 'Asset frame tidak ditemukan.', 'error');
        }

        $data['asset'] = $asset;
        $data['title'] = 'Edit Frame Slots';
        $this->adminView('admin/assets/edit_frame', $data);
    }

    public function ajax_save_frame_data()
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $assetId = $input['asset_id'] ?? null;
        $slotCount = $input['slot_count'] ?? 0;
        $coordinates = $input['coordinates'] ?? null;

        if (!$assetId || !is_array($coordinates)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Input tidak valid.']);
            return;
        }

        $dataToUpdate = [
            'slot_count' => $slotCount,
            'slot_coordinates' => json_encode($coordinates)
        ];

        $assetModel = $this->model('Asset');
        if ($assetModel->updateFrameData($assetId, $dataToUpdate)) {
            echo json_encode(['success' => true, 'message' => 'Data frame berhasil disimpan.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data ke database.']);
        }
    }

    // === GALLERY & CAMERA (TETAP SAMA) ===

    public function showGallery()
    {
        $photoModel = $this->model('Photo');
        $data['photos'] = $photoModel->getAll();
        $data['title'] = 'Photo Gallery';
        $this->adminView('admin/gallery/index', $data);
    }

    public function cameraControl()
    {
        $data['title'] = 'Live Camera Control';
        $data['live_view_websocket_url'] = LIVE_VIEW_WEBSOCKET_URL;
        $this->adminView('admin/camera', $data);
    }

    public function deletePhoto($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $photoModel = $this->model('Photo');
            $photo = $photoModel->find($id);

            if ($photo) {
                $filePath = dirname(APPROOT) . '/public' . $photo->file_path; 
                
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
                $photoModel->delete($id);
            }
            $this->redirect('admin/gallery');
        }
    }
}