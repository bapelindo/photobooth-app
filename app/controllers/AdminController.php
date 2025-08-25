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
            }
            die('Gagal menyimpan paket.');
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
            die('Paket tidak ditemukan.');
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
            }
            die('Gagal memperbarui paket.');
        }
    }

    public function deletePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $packageModel = $this->model('Package');
            if ($packageModel->delete($id)) {
                $this->redirect('admin/packages');
            }
            die('Gagal menghapus paket.');
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
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['asset_file'])) {
            $file = $_FILES['asset_file'];

            // Validasi sederhana
            if ($file['error'] !== UPLOAD_ERR_OK) {
                die('File upload error!');
            }
            $allowedTypes = ['image/png', 'image/jpeg', 'image/gif'];
            if (!in_array($file['type'], $allowedTypes)) {
                die('Invalid file type. Only PNG, JPG, GIF are allowed.');
            }

            $assetType = $_POST['type']; // 'frame' or 'sticker'
            $uploadDir = "../public/assets/{$assetType}s/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = uniqid() . '-' . basename($file['name']);
            $destination = $uploadDir . $filename;
            $dbPath = "/assets/{$assetType}s/" . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $data = [
                    'name' => $_POST['name'],
                    'type' => $assetType,
                    'file_path' => $dbPath
                ];
                $assetModel = $this->model('Asset');
                if ($assetModel->create($data)) {
                    $this->redirect('admin/assets');
                }
            }
            die('Failed to save asset.');
        }
    }

    public function deleteAsset($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $assetModel = $this->model('Asset');
            $asset = $assetModel->find($id);

            if ($asset) {
                // Hapus file dari server
                $filePath = '../public' . $asset->file_path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                // Hapus record dari DB
                $assetModel->delete($id);
            }
            $this->redirect('admin/assets');
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
}