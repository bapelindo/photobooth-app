<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;

class AdminController extends Controller {
    public function __construct()
    {
        // Middleware: Cek apakah admin sudah login
        if (!Session::has('admin_id')) {
            $this->redirect('login');
        }
    }

    public function dashboard()

    {
        $transactionModel = $this->model('Transaction');
        $packageModel = $this->model('Package');

        $data['summary'] = $transactionModel->getSummary();
        $data['popular_packages'] = $packageModel->getPopularPackages(3);
        $data['title'] = 'Dashboard';

        $this->adminView('admin/dashboard/index', $data);
    }

    // === PACKAGE MANAGEMENT ===

    public function listPackages()
    {
        $packageModel = $this->model('Package');
        $data['packages'] = $packageModel->getAll();
        $data['title'] = 'Manage Packages';
        $this->adminView('admin/packages/index', $data);
    }

    public function createPackage()
    {
        $data['title'] = 'Create New Package';
        $this->adminView('admin/packages/create', $data);
    }

    public function storePackage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'photo_limit' => $_POST['photo_limit'],
                'photo_slots' => $_POST['photo_limit'],
                'retake_limit' => $_POST['retake_limit'],
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->create($data)) {
                $this->redirect('admin/packages');
            } else {
                $this->flashAndRedirect('admin/packages', 'Gagal menyimpan paket.', 'error');
            }
        }
    }

    public function editPackage($id)
    {
        $packageModel = $this->model('Package');
        $package = $packageModel->find($id);

        if ($package) {
            $data['package'] = $package;
            $data['title'] = 'Edit Package';
            $this->adminView('admin/packages/edit', $data);
        } else {
            $this->flashAndRedirect('admin/packages', 'Paket tidak ditemukan.', 'error');
        }
    }

    public function updatePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'photo_limit' => $_POST['photo_limit'],
                'photo_slots' => $_POST['photo_limit'],
                'retake_limit' => $_POST['retake_limit'],
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->update($id, $data)) {
                $this->redirect('admin/packages');
            } else {
                $this->flashAndRedirect('admin/packages', 'Gagal memperbarui paket.', 'error');
            }
        }
    }

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

    // === ASSET MANAGEMENT ===

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

            // Handle file upload for frame/sticker, or value for filter
            if ($assetType === 'filter') {
                $dbPath = $_POST['asset_value']; // Get CSS value from new text field
            } else if (isset($_FILES['asset_file'])) {
                $file = $_FILES['asset_file'];
                if ($file['error'] !== UPLOAD_ERR_OK) {
                    $this->flashAndRedirect('admin/assets', 'File upload error!', 'error');
                }
                $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
                if (!in_array($file['type'], $allowedTypes)) {
                    $this->flashAndRedirect('admin/assets', 'Invalid file type. Only PNG, JPG, GIF are allowed.', 'error');
                }
                
                $uploadDir = "../public/assets/{$assetType}s/";
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                $filename = uniqid() . '-' . basename($file['name']);
                $destination = $uploadDir . $filename;
                $dbPath = "/assets/{$assetType}s/" . $filename;

                if (!move_uploaded_file($file['tmp_name'], $destination)) {
                     $this->flashAndRedirect('admin/assets', 'Failed to save asset file.', 'error');
                }
            } else {
                $this->flashAndRedirect('admin/assets', 'No file or value provided for asset.', 'error');
            }

            $data = [
                'name' => $_POST['name'],
                'type' => $assetType,
                'path' => $dbPath
            ];
            
            $assetModel = $this->model('Asset');
            if ($assetModel->create($data)) {
                $this->redirect('admin/assets');
            } else {
                $this->flashAndRedirect('admin/assets', 'Failed to save asset to database.', 'error');
            }
        }
    }


    public function deleteAsset($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $assetModel = $this->model('Asset');
            $asset = $assetModel->find($id);

            if ($asset && $asset->type !== 'filter') { // Don't delete file for filters
                // Hapus file dari server
                $filePath = '../public' . $asset->path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            // Hapus record dari DB
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
            $this->flashAndRedirect('admin/assets', 'Frame asset not found.', 'error');
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
            echo json_encode(['success' => false, 'message' => 'Invalid input.']);
            return;
        }

        $dataToUpdate = [
            'slot_count' => $slotCount,
            'slot_coordinates' => json_encode($coordinates)
        ];

        $assetModel = $this->model('Asset');
        if ($assetModel->updateFrameData($assetId, $dataToUpdate)) {
            echo json_encode(['success' => true, 'message' => 'Frame data saved successfully.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to save frame data to database.']);
        }
    }


    // === GALLERY ===

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
        // Kirim URL WebSocket ke view admin
        $data['live_view_websocket_url'] = LIVE_VIEW_WEBSOCKET_URL;
        $this->adminView('admin/camera', $data);
    }

    public function deletePhoto($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $photoModel = $this->model('Photo');
            $photo = $photoModel->find($id);

            if ($photo) {
                // Hapus file dari server
                // Assuming file_path in DB is like /public/uploads/photo/filename.png
                $filePath = dirname(APPROOT) . $photo->file_path; 
                
                // Check if the file exists and is a file (not a directory) before unlinking
                if (file_exists($filePath) && is_file($filePath)) {
                    unlink($filePath);
                }
                // Hapus record dari DB
                $photoModel->delete($id);
            }
            $this->redirect('admin/gallery');
        }
    }
}