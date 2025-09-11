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
        try {
            $data = $this->getDashboardData();
            $data['title'] = 'Dashboard';
            $this->adminView('admin/dashboard/index', $data);
        } catch (Exception $e) {
            error_log('Dashboard error: ' . $e->getMessage());
            // Provide fallback data if database queries fail
            $data = [
                'summary' => (object)['revenue_today' => 0, 'transactions_today' => 0, 'total_revenue' => 0, 'total_transactions' => 0],
                'popular_packages' => [],
                'session_stats' => (object)['sessions_today' => 0, 'completed_sessions' => 0, 'avg_photos_per_session' => 0],
                'recent_sessions' => [],
                'email_queue_stats' => (object)['pending' => 0, 'completed' => 0, 'failed' => 0],
                'print_queue_stats' => (object)['pending' => 0, 'completed' => 0, 'failed' => 0],
                'title' => 'Dashboard',
                'error_message' => 'Unable to load dashboard data. Please check system status.'
            ];
            $this->adminView('admin/dashboard/index', $data);
        }
    }

    private function getDashboardData()
    {
        $transactionModel = $this->model('Transaction');
        $packageModel = $this->model('Package');
        $photoSessionModel = $this->model('PhotoSession');
        $emailQueueModel = $this->model('EmailQueue');
        $printQueueModel = $this->model('PrintQueue');

        // Use parallel data fetching where possible
        $data = [];
        
        try {
            $data['summary'] = $transactionModel->getSummary() ?: (object)['revenue_today' => 0, 'transactions_today' => 0, 'total_revenue' => 0, 'total_transactions' => 0];
        } catch (Exception $e) {
            error_log('Transaction summary error: ' . $e->getMessage());
            $data['summary'] = (object)['revenue_today' => 0, 'transactions_today' => 0, 'total_revenue' => 0, 'total_transactions' => 0];
        }
        
        try {
            $data['popular_packages'] = $packageModel->getPopularPackages(3) ?: [];
        } catch (Exception $e) {
            error_log('Popular packages error: ' . $e->getMessage());
            $data['popular_packages'] = [];
        }
        
        try {
            $data['session_stats'] = $photoSessionModel->getSessionStatistics() ?: (object)['sessions_today' => 0, 'completed_sessions' => 0, 'avg_photos_per_session' => 0];
            $data['recent_sessions'] = $photoSessionModel->getRecentSessions(10) ?: [];
        } catch (Exception $e) {
            error_log('Session stats error: ' . $e->getMessage());
            $data['session_stats'] = (object)['sessions_today' => 0, 'completed_sessions' => 0, 'avg_photos_per_session' => 0];
            $data['recent_sessions'] = [];
        }
        
        try {
            $data['email_queue_stats'] = $emailQueueModel->getStats() ?: (object)['pending' => 0, 'completed' => 0, 'failed' => 0];
            $data['print_queue_stats'] = $printQueueModel->getStats() ?: (object)['pending' => 0, 'completed' => 0, 'failed' => 0];
        } catch (Exception $e) {
            error_log('Queue stats error: ' . $e->getMessage());
            $data['email_queue_stats'] = (object)['pending' => 0, 'completed' => 0, 'failed' => 0];
            $data['print_queue_stats'] = (object)['pending' => 0, 'completed' => 0, 'failed' => 0];
        }
        
        return $data;
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
            try {
                // Validate required fields
                $requiredFields = ['name', 'description', 'price', 'photo_limit', 'retake_limit'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Field {$field} is required");
                    }
                }

                // Validate numeric fields
                if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
                    throw new Exception('Price must be a valid positive number');
                }
                if (!is_numeric($_POST['photo_limit']) || $_POST['photo_limit'] < 1) {
                    throw new Exception('Photo limit must be at least 1');
                }
                if (!is_numeric($_POST['retake_limit']) || $_POST['retake_limit'] < 0) {
                    throw new Exception('Retake limit must be a valid non-negative number');
                }

                // Sanitize and prepare data
                $data = [
                    'name' => htmlspecialchars(trim($_POST['name'])),
                    'description' => htmlspecialchars(trim($_POST['description'])),
                    'price' => floatval($_POST['price']),
                    'photo_limit' => intval($_POST['photo_limit']),
                    'photo_slots' => intval($_POST['photo_slots'] ?? $_POST['photo_limit']),
                    'retake_limit' => intval($_POST['retake_limit']),
                    'frame_limit' => intval($_POST['frame_limit'] ?? DEFAULT_FRAME_LIMIT),
                    'session_duration' => intval($_POST['session_duration'] ?? DEFAULT_SESSION_DURATION),
                    'max_save_photos' => intval($_POST['max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS),
                ];

                $packageModel = $this->model('Package');
                if ($packageModel->create($data)) {
                    $this->flashAndRedirect('admin/packages', 'Paket berhasil dibuat!', 'success');
                } else {
                    throw new Exception('Failed to save package to database');
                }
            } catch (Exception $e) {
                error_log('Package creation error: ' . $e->getMessage());
                $this->flashAndRedirect('admin/packages/create', 'Error: ' . $e->getMessage(), 'error');
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
            try {
                // Validate ID
                if (!is_numeric($id) || $id < 1) {
                    throw new Exception('Invalid package ID');
                }

                // Check if package exists
                $packageModel = $this->model('Package');
                $existingPackage = $packageModel->find($id);
                if (!$existingPackage) {
                    throw new Exception('Package not found');
                }

                // Validate required fields
                $requiredFields = ['name', 'description', 'price', 'photo_limit', 'retake_limit'];
                foreach ($requiredFields as $field) {
                    if (empty($_POST[$field])) {
                        throw new Exception("Field {$field} is required");
                    }
                }

                // Validate numeric fields
                if (!is_numeric($_POST['price']) || $_POST['price'] < 0) {
                    throw new Exception('Price must be a valid positive number');
                }
                if (!is_numeric($_POST['photo_limit']) || $_POST['photo_limit'] < 1) {
                    throw new Exception('Photo limit must be at least 1');
                }
                if (!is_numeric($_POST['retake_limit']) || $_POST['retake_limit'] < 0) {
                    throw new Exception('Retake limit must be a valid non-negative number');
                }

                // Sanitize and prepare data
                $data = [
                    'name' => htmlspecialchars(trim($_POST['name'])),
                    'description' => htmlspecialchars(trim($_POST['description'])),
                    'price' => floatval($_POST['price']),
                    'photo_limit' => intval($_POST['photo_limit']),
                    'photo_slots' => intval($_POST['photo_slots'] ?? $_POST['photo_limit']),
                    'retake_limit' => intval($_POST['retake_limit']),
                    'frame_limit' => intval($_POST['frame_limit'] ?? DEFAULT_FRAME_LIMIT),
                    'session_duration' => intval($_POST['session_duration'] ?? DEFAULT_SESSION_DURATION),
                    'max_save_photos' => intval($_POST['max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS),
                ];

                if ($packageModel->update($id, $data)) {
                    $this->flashAndRedirect('admin/packages', 'Paket berhasil diperbarui!', 'success');
                } else {
                    throw new Exception('Failed to update package in database');
                }
            } catch (Exception $e) {
                error_log('Package update error: ' . $e->getMessage());
                $this->flashAndRedirect('admin/packages/edit/' . $id, 'Error: ' . $e->getMessage(), 'error');
            }
        }
    }

    public function deletePackage($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Validate ID
                if (!is_numeric($id) || $id < 1) {
                    throw new Exception('Invalid package ID');
                }

                $packageModel = $this->model('Package');
                $package = $packageModel->find($id);
                if (!$package) {
                    throw new Exception('Package not found');
                }

                // Check if package is being used in active sessions
                $photoSessionModel = $this->model('PhotoSession');
                try {
                    $activeSessions = method_exists($photoSessionModel, 'getActiveSessionsByPackage') 
                        ? $photoSessionModel->getActiveSessionsByPackage($id)
                        : [];
                    if ($activeSessions && count($activeSessions) > 0) {
                        throw new Exception('Cannot delete package that is currently being used in active sessions');
                    }
                } catch (Exception $e) {
                    // If method doesn't exist, log warning but continue
                    error_log('Warning: getActiveSessionsByPackage method not found: ' . $e->getMessage());
                }

                if ($packageModel->delete($id)) {
                    $this->flashAndRedirect('admin/packages', 'Paket berhasil dihapus!', 'success');
                } else {
                    throw new Exception('Failed to delete package from database');
                }
            } catch (Exception $e) {
                error_log('Package deletion error: ' . $e->getMessage());
                $this->flashAndRedirect('admin/packages', 'Error: ' . $e->getMessage(), 'error');
            }
        }
    }

    // === QUEUE MANAGEMENT ===
    
    public function queueManagement()
    {
        $emailQueueModel = $this->model('EmailQueue');
        $printQueueModel = $this->model('PrintQueue');
        
        // Get queue jobs with pagination
        $data['email_jobs'] = $emailQueueModel->getPendingJobs(20);
        $data['print_jobs'] = $printQueueModel->getPendingJobs(20);
        $data['email_stats'] = $emailQueueModel->getStats();
        $data['print_stats'] = $printQueueModel->getStats();
        
        $data['title'] = 'Queue Management';
        $this->adminView('admin/queue/index', $data);
    }
    
    public function queueStats()
    {
        header('Content-Type: application/json');
        
        try {
            $emailQueueModel = $this->model('EmailQueue');
            $printQueueModel = $this->model('PrintQueue');
            
            $emailStats = $emailQueueModel->getStats();
            $printStats = $printQueueModel->getStats();
            
            echo json_encode([
                'email_stats' => $emailStats ?: (object)['total' => 0, 'pending' => 0, 'processing' => 0, 'completed' => 0, 'failed' => 0],
                'print_stats' => $printStats ?: (object)['total' => 0, 'pending' => 0, 'processing' => 0, 'completed' => 0, 'failed' => 0],
                'timestamp' => time()
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'error' => $e->getMessage(),
                'email_stats' => (object)['total' => 0, 'pending' => 0, 'processing' => 0, 'completed' => 0, 'failed' => 0],
                'print_stats' => (object)['total' => 0, 'pending' => 0, 'processing' => 0, 'completed' => 0, 'failed' => 0],
                'timestamp' => time()
            ]);
        }
    }

    public function retryQueueJob($queue_type, $job_id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            if ($queue_type === 'email') {
                $emailQueueModel = $this->model('EmailQueue');
                $job = $emailQueueModel->find($job_id);
                
                if (!$job) {
                    throw new Exception('Email job not found');
                }
                
                // Reset job to pending status with retry count reset
                $emailQueueModel->resetJob($job_id);
                echo json_encode(['success' => true, 'message' => 'Email job queued for retry']);
                
            } elseif ($queue_type === 'print') {
                $printQueueModel = $this->model('PrintQueue');
                $job = $printQueueModel->find($job_id);
                
                if (!$job) {
                    throw new Exception('Print job not found');
                }
                
                $printQueueModel->updateStatus($job_id, 'pending', null);
                echo json_encode(['success' => true, 'message' => 'Print job queued for retry']);
                
            } else {
                throw new Exception('Invalid queue type');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteQueueJob($queue_type, $job_id)
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }
        
        try {
            if ($queue_type === 'email') {
                $emailQueueModel = $this->model('EmailQueue');
                $success = $emailQueueModel->delete($job_id);
                
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Email job deleted']);
                } else {
                    throw new Exception('Failed to delete email job');
                }
                
            } elseif ($queue_type === 'print') {
                $printQueueModel = $this->model('PrintQueue');
                $success = $printQueueModel->delete($job_id);
                
                if ($success) {
                    echo json_encode(['success' => true, 'message' => 'Print job deleted']);
                } else {
                    throw new Exception('Failed to delete print job');
                }
                
            } else {
                throw new Exception('Invalid queue type');
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
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
            try {
                // Validate required fields
                if (empty($_POST['name']) || empty($_POST['type'])) {
                    throw new Exception('Name and type are required fields');
                }

                $assetType = $_POST['type'];
                $allowedTypes = ['frame', 'sticker', 'filter'];
                if (!in_array($assetType, $allowedTypes)) {
                    throw new Exception('Invalid asset type');
                }

                $dbPath = '';

                // Handle file upload for frame/sticker, or value for filter
                if ($assetType === 'filter') {
                    if (empty($_POST['asset_value'])) {
                        throw new Exception('Filter value is required for filter assets');
                    }
                    $dbPath = htmlspecialchars(trim($_POST['asset_value']));
                } else {
                    if (!isset($_FILES['asset_file']) || $_FILES['asset_file']['error'] !== UPLOAD_ERR_OK) {
                        throw new Exception('File upload is required for ' . $assetType . ' assets');
                    }

                    $file = $_FILES['asset_file'];
                    
                    // Validate file type
                    $allowedMimeTypes = ['image/png', 'image/jpeg', 'image/gif', 'image/webp'];
                    if (!in_array($file['type'], $allowedMimeTypes)) {
                        throw new Exception('Invalid file type. Only PNG, JPG, GIF, WebP are allowed.');
                    }

                    // Validate file size (max 5MB)
                    if ($file['size'] > 5 * 1024 * 1024) {
                        throw new Exception('File size too large. Maximum 5MB allowed.');
                    }

                    // Create upload directory
                    $uploadDir = "../public/assets/{$assetType}s/";
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            throw new Exception('Failed to create upload directory');
                        }
                    }

                    // Generate secure filename
                    $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = uniqid($assetType . '_', true) . '.' . $fileExtension;
                    $destination = $uploadDir . $filename;
                    $dbPath = "/assets/{$assetType}s/" . $filename;

                    if (!move_uploaded_file($file['tmp_name'], $destination)) {
                        throw new Exception('Failed to save uploaded file');
                    }

                    // Set proper file permissions
                    chmod($destination, 0644);
                }

                // Sanitize and prepare data
                $data = [
                    'name' => htmlspecialchars(trim($_POST['name'])),
                    'type' => $assetType,
                    'file_path' => $dbPath
                ];
                
                $assetModel = $this->model('Asset');
                if ($assetModel->create($data)) {
                    $this->flashAndRedirect('admin/assets', 'Asset berhasil ditambahkan!', 'success');
                } else {
                    throw new Exception('Failed to save asset to database');
                }
            } catch (Exception $e) {
                error_log('Asset creation error: ' . $e->getMessage());
                $this->flashAndRedirect('admin/assets/create', 'Error: ' . $e->getMessage(), 'error');
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
                $filePath = '../public' . ($asset->file_path ?? $asset->path ?? '');
                if (!empty($filePath) && file_exists($filePath)) {
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
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Method Not Allowed', 405);
            }

            $input = json_decode(file_get_contents('php://input'), true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON data', 400);
            }

            $assetId = $input['asset_id'] ?? null;
            $slotCount = $input['slot_count'] ?? 0;
            $coordinates = $input['coordinates'] ?? null;

            // Validate input
            if (!$assetId || !is_numeric($assetId) || $assetId < 1) {
                throw new Exception('Invalid asset ID', 400);
            }
            if (!is_numeric($slotCount) || $slotCount < 0 || $slotCount > 20) {
                throw new Exception('Slot count must be between 0 and 20', 400);
            }
            if (!is_array($coordinates)) {
                throw new Exception('Coordinates must be an array', 400);
            }

            // Validate coordinates structure
            foreach ($coordinates as $coord) {
                if (!is_array($coord) || !isset($coord['x']) || !isset($coord['y']) || !isset($coord['width']) || !isset($coord['height'])) {
                    throw new Exception('Invalid coordinate structure', 400);
                }
                if (!is_numeric($coord['x']) || !is_numeric($coord['y']) || !is_numeric($coord['width']) || !is_numeric($coord['height'])) {
                    throw new Exception('Coordinate values must be numeric', 400);
                }
            }

            // Check if asset exists and is a frame
            $assetModel = $this->model('Asset');
            $asset = $assetModel->find($assetId);
            if (!$asset) {
                throw new Exception('Asset not found', 404);
            }
            if (($asset->type ?? '') !== 'frame') {
                throw new Exception('Asset is not a frame type', 400);
            }

            $dataToUpdate = [
                'slot_count' => intval($slotCount),
                'slot_coordinates' => json_encode($coordinates)
            ];

            if ($assetModel->updateFrameData($assetId, $dataToUpdate)) {
                echo json_encode(['success' => true, 'message' => 'Frame data saved successfully.']);
            } else {
                throw new Exception('Failed to save frame data to database', 500);
            }
        } catch (Exception $e) {
            $statusCode = $e->getCode() ?: 500;
            if ($statusCode < 400 || $statusCode >= 600) {
                $statusCode = 500;
            }
            http_response_code($statusCode);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            error_log('Frame data save error: ' . $e->getMessage());
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

    // === PHOTO SESSION MANAGEMENT ===

    public function listSessions()
    {
        $photoSessionModel = $this->model('PhotoSession');
        $data['sessions'] = $photoSessionModel->getAllWithDetails();
        $data['title'] = 'Photo Sessions';
        $this->adminView('admin/sessions/index', $data);
    }

    public function viewSession($session_id)
    {
        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->getSessionWithDetails($session_id);
        
        if (!$session) {
            $this->flashAndRedirect('admin/sessions', 'Sesi tidak ditemukan.', 'error');
        }

        // Get session photos and photostrips
        $sessionPhotos = $photoSessionModel->getSavedPhotos($session_id);
        $photostripModel = $this->model('Photostrip');
        $photostrips = $photostripModel->getBySessionId($session_id);

        $data = [
            'session' => $session,
            'photos' => $sessionPhotos,
            'photostrips' => $photostrips,
            'title' => 'View Session #' . $session_id
        ];

        $this->adminView('admin/sessions/view', $data);
    }

    public function deleteSession($session_id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $photoSessionModel = $this->model('PhotoSession');
            
            // Delete all related data (photos, photostrips, etc.)
            $this->deleteSessionData($session_id);
            
            if ($photoSessionModel->delete($session_id)) {
                $this->flashAndRedirect('admin/sessions', 'Sesi berhasil dihapus!', 'success');
            } else {
                $this->flashAndRedirect('admin/sessions', 'Gagal menghapus sesi.', 'error');
            }
        }
    }

    private function deleteSessionData($session_id)
    {
        // Delete session photos
        $photoSessionModel = $this->model('PhotoSession');
        $sessionPhotos = $photoSessionModel->getSessionPhotos($session_id);
        foreach ($sessionPhotos as $photo) {
            if (file_exists(dirname(APPROOT) . '/public' . $photo->file_path)) {
                unlink(dirname(APPROOT) . '/public' . $photo->file_path);
            }
        }

        // Delete photostrips
        $photostripModel = $this->model('Photostrip');
        $photostrips = $photostripModel->getBySessionId($session_id);
        foreach ($photostrips as $photostrip) {
            if (file_exists(dirname(APPROOT) . '/public' . $photostrip->final_image_path)) {
                unlink(dirname(APPROOT) . '/public' . $photostrip->final_image_path);
            }
            $photostripModel->delete($photostrip->id);
        }
    }

    // === PHOTOSTRIP MANAGEMENT ===

    public function listPhotostrips()
    {
        $photostripModel = $this->model('Photostrip');
        $data['photostrips'] = $photostripModel->getAllWithDetails();
        $data['title'] = 'Photostrips Management';
        $this->adminView('admin/photostrips/index', $data);
    }

    public function viewPhotostrip($photostrip_id)
    {
        $photostripModel = $this->model('Photostrip');
        $photostrip = $photostripModel->getWithFullDetails($photostrip_id);
        
        if (!$photostrip) {
            $this->flashAndRedirect('admin/photostrips', 'Photostrip tidak ditemukan.', 'error');
        }

        $data = [
            'photostrip' => $photostrip,
            'title' => 'View Photostrip #' . $photostrip_id
        ];

        $this->adminView('admin/photostrips/view', $data);
    }

    public function regeneratePhotostrip($photostrip_id)
    {
        header('Content-Type: application/json');
        
        try {
            $photostripModel = $this->model('Photostrip');
            $photostrip = $photostripModel->getWithFullDetails($photostrip_id);
            
            if (!$photostrip) {
                throw new Exception('Photostrip not found');
            }

            // Regenerate the final image (this would use your image processing service)
            $newImagePath = $this->regenerateFinalPhotostrip($photostrip);
            
            if ($newImagePath) {
                $photostripModel->updateFinalImage($photostrip_id, $newImagePath);
                echo json_encode(['success' => true, 'new_path' => $newImagePath]);
            } else {
                throw new Exception('Failed to regenerate photostrip');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function regenerateFinalPhotostrip($photostrip)
    {
        try {
            $outputDir = dirname(APPROOT) . '/public/uploads/final_photostrips/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
            }
            
            $filename = 'regenerated_photostrip_' . $photostrip->id . '_' . uniqid() . '.png';
            $relativePath = '/uploads/final_photostrips/' . $filename;
            $outputPath = $outputDir . $filename;
            
            // Use ImageProcessingService to properly regenerate the photostrip
            $imageProcessingService = new \App\Services\ImageProcessingService();
            
            // Get frame path
            $framePath = dirname(APPROOT) . '/public' . $photostrip->frame_path;
            if (!file_exists($framePath)) {
                throw new Exception('Frame file not found: ' . $framePath);
            }
            
            // Get layout data and slot coordinates
            $layoutData = json_decode($photostrip->layout_data ?: '[]', true) ?: [];
            $slotCoordinates = json_decode($photostrip->slot_coordinates ?: '[]', true) ?: [];
            
            // Get session photos
            $photoModel = $this->model('Photo');
            $photos = $photoModel->getBySession($photostrip->session_id);
            
            // Prepare photo paths array
            $photoPaths = [];
            foreach ($photos as $photo) {
                $fullPath = dirname(APPROOT) . '/public' . $photo->photo_path;
                if (file_exists($fullPath)) {
                    $photoPaths[] = $fullPath;
                }
            }
            
            // Generate photostrip using ImageProcessingService
            $result = $imageProcessingService->createPhotoStrip($framePath, $photoPaths, $slotCoordinates, $outputPath);
            
            if ($result) {
                return $relativePath;
            }
            
            return null;
            
        } catch (Exception $e) {
            error_log('Error regenerating photostrip: ' . $e->getMessage());
            return null;
        }
    }

    // === EMAIL QUEUE MANAGEMENT ===

    public function emailQueue()
    {
        $emailQueueService = new \App\Services\EmailQueueService();
        $stats = $emailQueueService->getQueueStats();
        
        $emailQueueModel = new \App\Models\EmailQueue();
        $pendingEmails = $emailQueueModel->getPending(20); // Show latest 20
        
        $data = [
            'title' => 'Email Queue Management',
            'stats' => $stats,
            'pending_emails' => $pendingEmails
        ];
        
        $this->adminView('admin/email_queue/index', $data);
    }

    public function processEmailQueue()
    {
        header('Content-Type: application/json');
        
        try {
            $emailQueueService = new \App\Services\EmailQueueService();
            $processed = $emailQueueService->processPendingEmails(10);
            
            echo json_encode([
                'success' => true, 
                'processed' => $processed,
                'message' => "Processed $processed emails"
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }

    // === STATISTICS & REPORTS ===

    public function reports()
    {
        $transactionModel = $this->model('Transaction');
        $photoSessionModel = $this->model('PhotoSession');
        $packageModel = $this->model('Package');
        $photostripModel = $this->model('Photostrip');

        // Get basic stats
        $sessionStats = $photoSessionModel->getSessionStatistics();
        $revenueStats = $transactionModel->getSummary();
        
        $data = [
            'stats' => (object) [
                'total_sessions' => $sessionStats->total_sessions ?? 0,
                'completed_sessions' => $sessionStats->completed_sessions ?? 0,
                'total_revenue' => $revenueStats->total_revenue ?? 0,
                'total_photostrips' => $photostripModel->getTotalCount() ?? 0
            ],
            'dailyStats' => $this->getDailyStatistics(),
            'packageStats' => $this->getPackageStatistics(),
            'chartData' => $this->getChartData(),
            'title' => 'Reports & Analytics'
        ];

        $this->adminView('admin/reports/index', $data);
    }

    private function getDailyStatistics()
    {
        $transactionModel = $this->model('Transaction');
        
        // Get last 7 days of data
        $dailyStats = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            $revenue = $transactionModel->getRevenueByDate($date);
            $dailyStats[] = (object) [
                'date' => $date,
                'session_count' => 0, // Would need to implement this
                'daily_revenue' => $revenue,
                'avg_duration' => 0, // Would need to implement this
                'print_success_rate' => 100 // Would need to implement this
            ];
        }
        
        return $dailyStats;
    }

    private function getPackageStatistics()
    {
        $packageModel = $this->model('Package');
        
        // Simple implementation - would need to join with transactions
        $packages = $packageModel->getAll();
        $packageStats = [];
        
        foreach ($packages as $package) {
            $packageStats[] = (object) [
                'package_name' => $package->name,
                'usage_count' => rand(1, 50) // Placeholder - would need real data
            ];
        }
        
        // Sort by usage count
        usort($packageStats, function($a, $b) {
            return $b->usage_count - $a->usage_count;
        });
        
        return $packageStats;
    }

    private function getChartData()
    {
        $transactionModel = $this->model('Transaction');
        $trends = $transactionModel->getRevenueTrends(30);
        
        $chartData = [];
        foreach ($trends as $trend) {
            $chartData[] = [
                'date' => $trend->date,
                'revenue' => floatval($trend->revenue)
            ];
        }
        
        return $chartData;
    }

    // === SYSTEM SETTINGS ===

    public function settings()
    {
        $data = [
            'settings' => $this->getCurrentSettings(),
            'title' => 'System Settings'
        ];
        
        $this->adminView('admin/settings/index', $data);
    }

    public function updateSettings()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $settings = [
                'default_session_duration' => $_POST['default_session_duration'] ?? DEFAULT_SESSION_DURATION,
                'default_max_save_photos' => $_POST['default_max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS,
                'default_frame_limit' => $_POST['default_frame_limit'] ?? DEFAULT_FRAME_LIMIT,
                'auto_print_enabled' => isset($_POST['auto_print_enabled']),
                'photo_quality' => $_POST['photo_quality'] ?? PHOTO_QUALITY,
            ];

            // Save settings to configuration or database
            $this->saveSettings($settings);
            
            $this->flashAndRedirect('admin/settings', 'Pengaturan berhasil disimpan!', 'success');
        }
    }

    private function getCurrentSettings()
    {
        return [
            'default_session_duration' => DEFAULT_SESSION_DURATION,
            'default_max_save_photos' => DEFAULT_MAX_SAVE_PHOTOS,
            'default_frame_limit' => DEFAULT_FRAME_LIMIT,
            'auto_print_enabled' => AUTO_PRINT_ENABLED,
            'photo_quality' => PHOTO_QUALITY,
        ];
    }

    private function saveSettings($settings)
    {
        // This would save settings to database or config file
        // For now, just log them
        error_log('Settings updated: ' . json_encode($settings));
    }

    // === ADVANCED ADMIN FEATURES ===

    public function systemInfo()
    {
        header('Content-Type: application/json');
        
        try {
            $info = [
                'php_version' => PHP_VERSION,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                'memory_limit' => ini_get('memory_limit'),
                'max_execution_time' => ini_get('max_execution_time'),
                'upload_max_filesize' => ini_get('upload_max_filesize'),
                'post_max_size' => ini_get('post_max_size'),
                'disk_free_space' => $this->formatBytes(disk_free_space(dirname(APPROOT))),
                'disk_total_space' => $this->formatBytes(disk_total_space(dirname(APPROOT))),
                'current_time' => date('Y-m-d H:i:s'),
                'timezone' => date_default_timezone_get(),
                'extensions' => [
                    'gd' => extension_loaded('gd'),
                    'curl' => extension_loaded('curl'),
                    'pdo' => extension_loaded('pdo'),
                    'zip' => extension_loaded('zip')
                ]
            ];
            
            echo json_encode($info);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function exportData($type = 'all')
    {
        try {
            $allowedTypes = ['sessions', 'packages', 'transactions', 'all'];
            if (!in_array($type, $allowedTypes)) {
                throw new Exception('Invalid export type');
            }

            $data = [];
            $filename = 'photobooth_export_' . date('Y-m-d_H-i-s');

            switch ($type) {
                case 'sessions':
                    $photoSessionModel = $this->model('PhotoSession');
                    $data = method_exists($photoSessionModel, 'getAllWithDetails')
                        ? $photoSessionModel->getAllWithDetails()
                        : $photoSessionModel->getAll();
                    $filename .= '_sessions';
                    break;
                case 'packages':
                    $packageModel = $this->model('Package');
                    $data = $packageModel->getAll() ?? [];
                    $filename .= '_packages';
                    break;
                case 'transactions':
                    $transactionModel = $this->model('Transaction');
                    $data = method_exists($transactionModel, 'getAllWithDetails')
                        ? $transactionModel->getAllWithDetails()
                        : $transactionModel->getAll();
                    $filename .= '_transactions';
                    break;
                case 'all':
                    try {
                        $sessionModel = $this->model('PhotoSession');
                        $packageModel = $this->model('Package');
                        $transactionModel = $this->model('Transaction');
                        
                        $data = [
                            'sessions' => method_exists($sessionModel, 'getAllWithDetails')
                                ? $sessionModel->getAllWithDetails() : $sessionModel->getAll(),
                            'packages' => $packageModel->getAll() ?? [],
                            'transactions' => method_exists($transactionModel, 'getAllWithDetails')
                                ? $transactionModel->getAllWithDetails() : $transactionModel->getAll(),
                            'export_date' => date('Y-m-d H:i:s'),
                            'version' => '1.0'
                        ];
                    } catch (Exception $e) {
                        error_log('Export all data error: ' . $e->getMessage());
                        $data = [
                            'error' => 'Failed to export some data',
                            'export_date' => date('Y-m-d H:i:s'),
                            'version' => '1.0'
                        ];
                    }
                    break;
            }

            header('Content-Type: application/json');
            header('Content-Disposition: attachment; filename="' . $filename . '.json"');
            echo json_encode($data, JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            $this->flashAndRedirect('admin/dashboard', 'Export failed: ' . $e->getMessage(), 'error');
        }
    }

    public function bulkActions()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flashAndRedirect('admin/dashboard', 'Invalid request method', 'error');
            return;
        }

        try {
            $action = $_POST['bulk_action'] ?? '';
            $ids = $_POST['selected_ids'] ?? [];
            $type = $_POST['item_type'] ?? '';

            if (empty($action) || empty($ids) || empty($type)) {
                throw new Exception('Missing required parameters for bulk action');
            }

            if (!is_array($ids)) {
                $ids = [$ids];
            }

            $result = $this->processBulkAction($action, $ids, $type);
            
            if ($result['success']) {
                $this->flashAndRedirect($result['redirect'], $result['message'], 'success');
            } else {
                throw new Exception($result['message']);
            }
        } catch (Exception $e) {
            error_log('Bulk action error: ' . $e->getMessage());
            $this->flashAndRedirect('admin/dashboard', 'Bulk action failed: ' . $e->getMessage(), 'error');
        }
    }

    private function processBulkAction($action, $ids, $type)
    {
        $result = ['success' => false, 'message' => '', 'redirect' => 'admin/dashboard'];
        
        switch ($type) {
            case 'sessions':
                $result = $this->handleSessionBulkAction($action, $ids);
                break;
            case 'packages':
                $result = $this->handlePackageBulkAction($action, $ids);
                break;
            case 'photos':
                $result = $this->handlePhotoBulkAction($action, $ids);
                break;
            default:
                $result['message'] = 'Unknown item type for bulk action';
        }
        
        return $result;
    }

    private function handleSessionBulkAction($action, $ids)
    {
        $photoSessionModel = $this->model('PhotoSession');
        $count = 0;
        
        switch ($action) {
            case 'delete':
                foreach ($ids as $id) {
                    if (is_numeric($id) && $id > 0) {
                        $this->deleteSessionData($id);
                        if ($photoSessionModel->delete($id)) {
                            $count++;
                        }
                    }
                }
                return [
                    'success' => true,
                    'message' => "Successfully deleted {$count} session(s)",
                    'redirect' => 'admin/sessions'
                ];
            default:
                return ['success' => false, 'message' => 'Unknown action for sessions'];
        }
    }

    private function handlePackageBulkAction($action, $ids)
    {
        $packageModel = $this->model('Package');
        $count = 0;
        
        switch ($action) {
            case 'delete':
                foreach ($ids as $id) {
                    if (is_numeric($id) && $id > 0) {
                        // Check if package is in use
                        $photoSessionModel = $this->model('PhotoSession');
                        $activeSessions = $photoSessionModel->getActiveSessionsByPackage($id);
                        if (!$activeSessions || count($activeSessions) === 0) {
                            if ($packageModel->delete($id)) {
                                $count++;
                            }
                        }
                    }
                }
                return [
                    'success' => true,
                    'message' => "Successfully deleted {$count} package(s)",
                    'redirect' => 'admin/packages'
                ];
            default:
                return ['success' => false, 'message' => 'Unknown action for packages'];
        }
    }

    private function handlePhotoBulkAction($action, $ids)
    {
        $photoModel = $this->model('Photo');
        $count = 0;
        
        switch ($action) {
            case 'delete':
                foreach ($ids as $id) {
                    if (is_numeric($id) && $id > 0) {
                        $photo = $photoModel->find($id);
                        if ($photo) {
                            $filePath = dirname(APPROOT) . $photo->file_path;
                            if (file_exists($filePath) && is_file($filePath)) {
                                unlink($filePath);
                            }
                            if ($photoModel->delete($id)) {
                                $count++;
                            }
                        }
                    }
                }
                return [
                    'success' => true,
                    'message' => "Successfully deleted {$count} photo(s)",
                    'redirect' => 'admin/gallery'
                ];
            default:
                return ['success' => false, 'message' => 'Unknown action for photos'];
        }
    }

    public function searchData()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Content-Type: application/json');
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            return;
        }

        try {
            $query = $_GET['q'] ?? '';
            $type = $_GET['type'] ?? 'all';
            
            if (strlen($query) < 2) {
                throw new Exception('Search query must be at least 2 characters');
            }

            $results = [];
            
            if ($type === 'all' || $type === 'sessions') {
                $photoSessionModel = $this->model('PhotoSession');
                $sessions = $photoSessionModel->search($query);
                $results['sessions'] = $sessions;
            }
            
            if ($type === 'all' || $type === 'packages') {
                $packageModel = $this->model('Package');
                $packages = $packageModel->search($query);
                $results['packages'] = $packages;
            }
            
            if ($type === 'all' || $type === 'users') {
                // Add user search if implemented
                $results['users'] = [];
            }

            header('Content-Type: application/json');
            echo json_encode($results);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function clearCache()
    {
        header('Content-Type: application/json');
        
        try {
            $cacheCleared = 0;
            $tempDir = sys_get_temp_dir();
            
            // Clear PHP session files
            $sessionPath = session_save_path() ?: $tempDir;
            if (is_dir($sessionPath)) {
                $files = glob($sessionPath . '/sess_*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < time() - 3600) { // Older than 1 hour
                        if (unlink($file)) {
                            $cacheCleared++;
                        }
                    }
                }
            }
            
            // Clear thumbnail cache if exists
            $thumbDir = dirname(APPROOT) . '/public/cache/thumbnails/';
            if (is_dir($thumbDir)) {
                $files = glob($thumbDir . '*');
                foreach ($files as $file) {
                    if (is_file($file)) {
                        if (unlink($file)) {
                            $cacheCleared++;
                        }
                    }
                }
            }

            echo json_encode([
                'success' => true,
                'message' => "Cleared {$cacheCleared} cache files",
                'timestamp' => time()
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        return round($size, $precision) . ' ' . $units[$i];
    }

    public function downloadLogs()
    {
        try {
            $logFile = dirname(APPROOT) . '/logs/app.log';
            if (!file_exists($logFile)) {
                throw new Exception('Log file not found');
            }

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="photobooth_logs_' . date('Y-m-d') . '.log"');
            readfile($logFile);
        } catch (Exception $e) {
            $this->flashAndRedirect('admin/dashboard', 'Failed to download logs: ' . $e->getMessage(), 'error');
        }
    }
}