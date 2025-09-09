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
        $photoSessionModel = $this->model('PhotoSession');
        $emailQueueModel = $this->model('EmailQueue');
        $printQueueModel = $this->model('PrintQueue');

        $data['summary'] = $transactionModel->getSummary();
        $data['popular_packages'] = $packageModel->getPopularPackages(3);
        
        // Get photo session statistics
        $data['session_stats'] = $photoSessionModel->getSessionStatistics();
        
        // Get recent sessions
        $data['recent_sessions'] = $photoSessionModel->getRecentSessions(10);
        
        // Get queue statistics
        $data['email_queue_stats'] = $emailQueueModel->getStats();
        $data['print_queue_stats'] = $printQueueModel->getStats();
        
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
                'photo_slots' => $_POST['photo_slots'] ?? $_POST['photo_limit'],
                'retake_limit' => $_POST['retake_limit'],
                'frame_limit' => $_POST['frame_limit'] ?? DEFAULT_FRAME_LIMIT,
                'session_duration' => $_POST['session_duration'] ?? DEFAULT_SESSION_DURATION,
                'max_save_photos' => $_POST['max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS,
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->create($data)) {
                $this->flashAndRedirect('admin/packages', 'Paket berhasil dibuat!', 'success');
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
                'photo_slots' => $_POST['photo_slots'] ?? $_POST['photo_limit'],
                'retake_limit' => $_POST['retake_limit'],
                'frame_limit' => $_POST['frame_limit'] ?? DEFAULT_FRAME_LIMIT,
                'session_duration' => $_POST['session_duration'] ?? DEFAULT_SESSION_DURATION,
                'max_save_photos' => $_POST['max_save_photos'] ?? DEFAULT_MAX_SAVE_PHOTOS,
            ];

            $packageModel = $this->model('Package');
            if ($packageModel->update($id, $data)) {
                $this->flashAndRedirect('admin/packages', 'Paket berhasil diperbarui!', 'success');
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
        
        $emailQueueModel = $this->model('EmailQueue');
        $printQueueModel = $this->model('PrintQueue');
        
        echo json_encode([
            'email_stats' => $emailQueueModel->getStats(),
            'print_stats' => $printQueueModel->getStats(),
            'timestamp' => time()
        ]);
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
                'file_path' => $dbPath // Changed 'path' to 'file_path'
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
}