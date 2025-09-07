<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\ImageProcessingService; // Pastikan service ini ada dan benar
use Exception;
use Throwable;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PhotoController extends Controller
{
    public function selectFrame($transaction_id)
    {
        Session::start();
        
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentTransactionId = Session::get('current_transaction_id');

            $condition1 = ($sessionWorkflowStep !== 'frame_selection_unlocked');
            $condition2 = ($sessionCurrentTransactionId != $transaction_id);

            if ($condition1 || $condition2) {
                $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
            }
        }

        // Get transaction and package data
        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($transaction_id);
        if (!$transaction) {
            $this->flashAndRedirect('packages', 'Transaksi tidak ditemukan.');
        }
        
        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);
        if (!$package) {
            $this->flashAndRedirect('packages', 'Paket tidak ditemukan.');
        }

        // Get available frames
        $assetModel = $this->model('Asset');
        $data['frames'] = $assetModel->getAssetsByType('frame');
        $data['transaction_id'] = $transaction_id;
        $data['package'] = $package;
        $data['frame_limit'] = $package->frame_limit ?? 2;
        
        $this->view('photo/select_frame', $data);
    }

    public function submitFrameSelection()
    {
        Session::start();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->flashAndRedirect('packages', 'Metode request tidak valid.');
        }

        $transaction_id = $_POST['transaction_id'] ?? null;
        $selected_frames = $_POST['selected_frames'] ?? [];

        if (!$transaction_id || empty($selected_frames)) {
            $this->flashAndRedirect('packages', 'Data tidak lengkap.');
        }

        // Create photo session
        $photoSessionModel = $this->model('PhotoSession');
        $session_id = $photoSessionModel->create([
            'transaction_id' => $transaction_id,
            'selected_frames' => json_encode($selected_frames),
            'session_status' => 'started'
        ]);

        Session::set('workflow_step', 'photo_session_active');
        Session::set('current_session_id', $session_id);

        header('Location: ' . URLROOT . '/photo/session/' . $session_id);
        exit();
    }

    public function photoSession($session_id)
    {
        Session::start();

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        
        if (!$session) {
            $this->flashAndRedirect('packages', 'Sesi foto tidak ditemukan.');
        }

        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($session->transaction_id);
        
        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);

        $assetModel = $this->model('Asset');
        $selectedFrames = json_decode($session->selected_frames, true);
        $frames = [];
        foreach ($selectedFrames as $frame_id) {
            $frames[] = $assetModel->find($frame_id);
        }

        // Load filters from database
        $filters = $assetModel->getAssetsByType('filter');
        
        $data = [
            'session' => $session,
            'package' => $package,
            'frames' => $frames,
            'filters' => $filters,
            'session_duration' => $package->session_duration ?? 300,
            'max_save_photos' => $package->max_save_photos ?? 20
        ];

        $this->view('photo/session', $data);
    }

    public function saveSessionPhoto()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $session_id = $_POST['session_id'] ?? null;
        $photoFile = $_FILES['photo'] ?? null;

        if (!$session_id || !$photoFile) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        try {
            // Validate session
            $photoSessionModel = $this->model('PhotoSession');
            $session = $photoSessionModel->find($session_id);
            if (!$session) {
                throw new Exception('Session not found');
            }

            // Create upload directory
            $uploadDir = dirname(APPROOT) . '/public/uploads/session_photos/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
            }

            // Generate unique filename
            $filename = 'session_' . $session_id . '_' . uniqid() . '.png';
            $filePath = $uploadDir . $filename;
            $relativeFilePath = '/uploads/session_photos/' . $filename;

            // Move uploaded file
            if (!move_uploaded_file($photoFile['tmp_name'], $filePath)) {
                throw new Exception('Failed to save file');
            }

            // Save to database
            $photoSessionPhotoModel = $this->model('PhotoSessionPhoto');
            $photo_id = $photoSessionPhotoModel->create([
                'session_id' => $session_id,
                'file_path' => $relativeFilePath,
                'is_saved' => 1
            ]);

            echo json_encode([
                'success' => true,
                'photo' => [
                    'id' => $photo_id,
                    'file_path' => $relativeFilePath
                ]
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteSessionPhoto()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $photo_id = $input['photo_id'] ?? null;
        $session_id = $input['session_id'] ?? null; // Good practice to validate session ownership

        if (!$photo_id || !$session_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing photo_id or session_id']);
            return;
        }

        try {
            $photoSessionPhotoModel = $this->model('PhotoSessionPhoto');
            
            // Optional: Verify photo belongs to the session
            $photo = $photoSessionPhotoModel->find($photo_id);
            if (!$photo || $photo->session_id != $session_id) {
                http_response_code(403);
                echo json_encode(['success' => false, 'message' => 'Forbidden: Photo does not belong to this session.']);
                return;
            }

            $success = $photoSessionPhotoModel->deletePhoto($photo_id);

            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Photo deleted successfully.']);
            } else {
                throw new Exception('Failed to delete photo from database.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function completeSession()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;

        if (!$session_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing session ID']);
            return;
        }

        try {
            $photoSessionModel = $this->model('PhotoSession');
            $success = $photoSessionModel->completeSession($session_id);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to complete session');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function layoutEditor($session_id)
    {
        Session::start();

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        
        if (!$session || $session->session_status !== 'completed') {
            $this->flashAndRedirect('packages', 'Sesi tidak valid atau belum selesai.');
        }

        $photos = $photoSessionModel->getSavedPhotos($session_id);
        $all_session_photos = $photoSessionModel->getSessionPhotos($session_id); // Get all photos for debugging

        $selectedFrames = json_decode($session->selected_frames, true);
        
        $assetModel = $this->model('Asset');
        $frames = [];
        if (is_array($selectedFrames)) {
            foreach ($selectedFrames as $frame_id) {
                $frame = $assetModel->find($frame_id);
                if ($frame) {
                    $frames[] = $frame;
                }
            }
        }

        $data = [
            'session' => $session,
            'photos' => $photos,
            'frames' => $frames,
            'debug_info' => [
                'saved_photos_count' => count($photos),
                'total_session_photos_count' => count($all_session_photos),
                'session_id_checked' => $session_id
            ]
        ];

        $this->view('photo/layout_editor', $data);
    }

    public function saveLayouts()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $final_images = $input['final_images'] ?? [];
        $frame_data = $input['frame_data'] ?? [];

        if (!$session_id || empty($final_images) || empty($frame_data) || count($final_images) !== count($frame_data)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing or mismatched data']);
            return;
        }

        try {
            $photostripModel = $this->model('Photostrip');
            $imageProcessingService = new ImageProcessingService();

            foreach ($final_images as $index => $imageData) {
                $frame_id = $frame_data[$index]['frame_id'];

                // Save the image
                $filePath = $imageProcessingService->saveBase64Image(
                    $imageData,
                    'photostrip_layout_' . $session_id . '_' . $frame_id,
                    'uploads/photostrips' // example directory
                );

                // Create photostrip record
                $photostripModel->create([
                    'session_id' => $session_id,
                    'frame_id' => $frame_id,
                    'layout_data' => json_encode($frame_data[$index]['photos'] ?? []),
                    'final_image_path' => $filePath 
                ]);
            }

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function decorationEditor($session_id)
    {
        Session::start();

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        
        if (!$session) {
            $this->flashAndRedirect('packages', 'Sesi tidak valid.');
        }

        // Get photostrips created in layout stage
        $photostripModel = $this->model('Photostrip');
        $photostrips = $photostripModel->getBySessionId($session_id);

        if (empty($photostrips)) {
            $this->flashAndRedirect('packages', 'Belum ada layout yang disimpan.');
        }

        // Get stickers for decoration
        $assetModel = $this->model('Asset');
        $stickers = $assetModel->getAssetsByType('sticker');

        $data = [
            'session' => $session,
            'photostrips' => $photostrips,
            'stickers' => $stickers
        ];

        $this->view('photo/decoration_editor', $data);
    }

    public function saveDecorations()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $decorations = $input['decorations'] ?? [];

        if (!$session_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing session ID']);
            return;
        }

        try {
            $photostripModel = $this->model('Photostrip');
            
            // Save decorations for each photostrip
            foreach ($decorations as $photostrip_id => $decoration_data) {
                $photostripModel->updateDecorationData($photostrip_id, json_encode($decoration_data));
            }

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function finalizeSession($session_id)
    {
        Session::start();

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        
        if (!$session) {
            $this->flashAndRedirect('packages', 'Sesi tidak valid.');
        }

        // Get transaction and package info
        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($session->transaction_id);
        
        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);

        // Get completed photostrips
        $photostripModel = $this->model('Photostrip');
        $photostrips = $photostripModel->getBySessionId($session_id);

        if (empty($photostrips)) {
            $this->flashAndRedirect('packages', 'Tidak ada photostrip yang dibuat.');
        }

        // Generate final images for each photostrip
        $finalPhotostrips = [];
        foreach ($photostrips as $photostrip) {
            $finalImagePath = $this->generateFinalPhotostrip($photostrip);
            if ($finalImagePath) {
                $photostripModel->updateFinalImage($photostrip->id, $finalImagePath);
                $photostrip->final_image_path = $finalImagePath;
            }
            $finalPhotostrips[] = $photostrip;
        }

        // Get all saved session photos for ZIP
        $sessionPhotos = $photoSessionModel->getSavedPhotos($session_id);

        $data = [
            'session' => $session,
            'package' => $package,
            'transaction' => $transaction,
            'photostrips' => $finalPhotostrips,
            'session_photos' => $sessionPhotos
        ];

        $this->view('photo/finalize_session', $data);
    }

    private function generateFinalPhotostrip($photostrip)
    {
        try {
            $outputDir = dirname(APPROOT) . '/public/uploads/final_photostrips/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
            }
            
            $filename = 'final_photostrip_' . $photostrip->id . '_' . uniqid() . '.png';
            $relativePath = '/uploads/final_photostrips/' . $filename;
            $outputPath = $outputDir . $filename;
            
            // Get frame path
            $framePath = dirname(APPROOT) . '/public' . $photostrip->frame_path;
            if (!file_exists($framePath)) {
                error_log('Frame file not found: ' . $framePath);
                return null;
            }
            
            // Get layout data and slot coordinates
            $layoutData = json_decode($photostrip->layout_data ?: '[]', true) ?: [];
            $slotCoordinates = json_decode($photostrip->slot_coordinates ?: '[]', true) ?: [];
            
            // Prepare photo paths array
            $photoPaths = [];
            if (!empty($layoutData)) {
                foreach ($layoutData as $slotIndex => $photo) {
                    if (!is_array($photo)) {
                        continue; // Skip non-array items
                    }
                    
                    // Try different possible key names for the photo path
                    $photoPathKey = null;
                    $possibleKeys = ['photoPath', 'path', 'file_path', 'photo_path'];
                    
                    foreach ($possibleKeys as $key) {
                        if (isset($photo[$key])) {
                            $photoPathKey = $key;
                            break;
                        }
                    }
                    
                    if ($photoPathKey) {
                        $photoPath = dirname(APPROOT) . '/public' . $photo[$photoPathKey];
                        if (file_exists($photoPath)) {
                            $photoPaths[(int)($photo['slot'] ?? $slotIndex)] = $photoPath;
                        }
                    }
                }
            }
            
            // Use ImageProcessingService to create the photostrip
            $imageService = new \App\Services\ImageProcessingService();
            $success = $imageService->createPhotoStrip(
                $photoPaths,
                $framePath,
                $outputPath,
                $slotCoordinates,
                'none'
            );
            
            if (!$success) {
                error_log('Failed to create photostrip using ImageProcessingService');
                return null;
            }
            
            // Add decorations if any
            $decorationData = json_decode($photostrip->decoration_data ?: '[]', true) ?: [];
            if (!empty($decorationData)) {
                $stickers = [];
                foreach ($decorationData as $decoration) {
                    if (!is_array($decoration)) {
                        continue;
                    }
                    
                    // Try different possible key names for the sticker path
                    $stickerPathKey = null;
                    $possibleStickerKeys = ['stickerPath', 'path', 'file_path'];
                    
                    foreach ($possibleStickerKeys as $key) {
                        if (isset($decoration[$key])) {
                            $stickerPathKey = $key;
                            break;
                        }
                    }
                    
                    if ($stickerPathKey) {
                        $stickerPath = dirname(APPROOT) . '/public' . $decoration[$stickerPathKey];
                        if (file_exists($stickerPath)) {
                            $stickers[] = [
                                'path' => $stickerPath,
                                'x' => (int)($decoration['x'] ?? 0),
                                'y' => (int)($decoration['y'] ?? 0),
                                'width' => (int)($decoration['width'] ?? 50),
                                'height' => (int)($decoration['height'] ?? 50)
                            ];
                        }
                    }
                }
                
                if (!empty($stickers)) {
                    // Create temporary path for decorated image
                    $tempPath = $outputDir . 'temp_' . $filename;
                    $success = $imageService->applyOverlays(
                        $outputPath,
                        null, // No additional frame overlay
                        $stickers,
                        $tempPath
                    );
                    
                    if ($success && file_exists($tempPath)) {
                        rename($tempPath, $outputPath);
                    }
                }
            }
            
            return $relativePath;
            
        } catch (Exception $e) {
            error_log('Error generating final photostrip: ' . $e->getMessage());
            return null;
        }
    }

    public function printPhotostrip()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $photostrip_id = $input['photostrip_id'] ?? null;

        if (!$photostrip_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing photostrip ID']);
            return;
        }

        try {
            $photostripModel = $this->model('Photostrip');
            $photostrip = $photostripModel->find($photostrip_id);
            
            if (!$photostrip) {
                throw new Exception('Photostrip not found');
            }

            if (!$photostrip->final_image_path) {
                throw new Exception('Final image not available');
            }

            // Execute print command (similar to existing print functionality)
            $basePath = dirname(APPROOT);
            $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $photostrip->final_image_path);
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';

            if (!file_exists($photoPath)) {
                throw new Exception('Photostrip file not found: ' . $photoPath);
            }

            if (!file_exists($scriptPath)) {
                throw new Exception('Print script not found');
            }

            $pythonPath = 'python';
            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$photoPath\"");
            $output = shell_exec("$command 2>&1");

            if (strpos(strtolower($output), 'error') !== false) {
                throw new Exception('Print failed: ' . $output);
            }

            // Mark as printed
            $photostripModel->markAsPrinted($photostrip_id);

            echo json_encode([
                'success' => true, 
                'message' => 'Photostrip sent to printer successfully!',
                'debug_output' => $output
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function sendSessionEmail()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $email = $input['email'] ?? null;

        if (!$session_id || !$email) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid email address']);
            return;
        }

        try {
            $photoSessionModel = $this->model('PhotoSession');
            $session = $photoSessionModel->find($session_id);
            
            if (!$session) {
                throw new Exception('Session not found');
            }

            // Get photostrips and session photos
            $photostripModel = $this->model('Photostrip');
            $photostrips = $photostripModel->getBySessionId($session_id);
            $sessionPhotos = $photoSessionModel->getSavedPhotos($session_id);

            // Create ZIP file with all photos
            $zipPath = $this->createSessionZip($session_id, $sessionPhotos);

            // Send email with photostrips and ZIP attachment
            $emailService = new \App\Services\EmailService();
            
            $attachments = [];
            
            // Add photostrip images
            foreach ($photostrips as $photostrip) {
                if ($photostrip->final_image_path) {
                    $fullPath = dirname(APPROOT) . '/public' . $photostrip->final_image_path;
                    if (file_exists($fullPath)) {
                        $attachments[] = [
                            'path' => $fullPath,
                            'name' => 'photostrip_' . $photostrip->frame_name . '.png'
                        ];
                    }
                }
            }

            // Add ZIP file
            if ($zipPath && file_exists($zipPath)) {
                $attachments[] = [
                    'path' => $zipPath,
                    'name' => 'session_photos_' . $session_id . '.zip'
                ];
            }

            $success = $emailService->sendSessionPhotos($email, $attachments);

            if ($success) {
                // Clean up ZIP file
                if ($zipPath && file_exists($zipPath)) {
                    @unlink($zipPath);
                }
                
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Failed to send email');
            }

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function createSessionZip($session_id, $sessionPhotos)
    {
        if (empty($sessionPhotos)) {
            return null;
        }

        $zipPath = dirname(APPROOT) . '/public/uploads/temp/session_' . $session_id . '.zip';
        $zipDir = dirname($zipPath);
        
        if (!is_dir($zipDir)) {
            mkdir($zipDir, 0775, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE) !== TRUE) {
            return null;
        }

        foreach ($sessionPhotos as $index => $photo) {
            $photoPath = dirname(APPROOT) . '/public' . $photo->file_path;
            if (file_exists($photoPath)) {
                $zip->addFile($photoPath, sprintf('photo_%03d.png', $index + 1));
            }
        }

        $zip->close();
        
        return file_exists($zipPath) ? $zipPath : null;
    }

    public function checkPrintStatus($session_id)
    {
        header('Content-Type: application/json');

        try {
            $photostripModel = $this->model('Photostrip');
            $photostrips = $photostripModel->getBySessionId($session_id);

            $updates = [];
            foreach ($photostrips as $photostrip) {
                $updates[] = [
                    'id' => $photostrip->id,
                    'is_printed' => (bool)$photostrip->is_printed
                ];
            }

            echo json_encode([
                'success' => true,
                'updates' => $updates
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Removed legacy capture function - using new session workflow instead

    // Removed legacy editor function - using new session workflow instead
    
    // Removed duplicate finalize function - using finalizeSession instead for the new workflow

    // Removed legacy ajax_save_captured_photos - using new session workflow instead

    // Removed legacy ajax_save_final_photostrip - using new session workflow instead

    // Removed legacy send_email function - using sendSessionEmail for new session workflow
    
    // Removed legacy ajax_print_photo function - using printPhotostrip for new session workflow

    public function ajax_capture_dslr()
    {
        header('Content-Type: application/json');

        try {
            $basePath = dirname(APPROOT, 2) . DIRECTORY_SEPARATOR . 'photobooth-app';
            $sdkDebugPath = $basePath . DIRECTORY_SEPARATOR . 'Debug';
            $outputDir = $basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'captures';
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'capture_sony.py';

            if (!is_dir($outputDir)) mkdir($outputDir, 0775, true);
            if (!file_exists($scriptPath)) throw new Exception('Skrip capture_sony.py tidak ditemukan.');

            $filename = 'capture_' . uniqid() . '.png';
            $pythonPath = 'python';
            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$outputDir\" \"$filename\" \"$sdkDebugPath\"");
            $output = shell_exec("$command 2>&1");
            
            $relativePath = trim($output);

            if (strpos($relativePath, '/uploads/captures/') === 0 && file_exists($outputDir . DIRECTORY_SEPARATOR . $filename)) {
                echo json_encode([
                    'success' => true, 
                    'photoUrl' => URLROOT . $relativePath
                ]);
            } else {
                throw new Exception('Gagal mengambil foto dari kamera: ' . $output);
            }

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function ajax_save_photo()
    {
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $imageData = $input['image'];
            $photo_id = $input['photo_id'];

            $photoModel = $this->model('Photo');
            $photo = $photoModel->find($photo_id);

            if ($photo) {
                $filePath = dirname(APPROOT) . '/public' . $photo->file_path;
                
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $decodedImage = base64_decode($imageData);

                if (file_put_contents($filePath, $decodedImage)) {
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        exec('icacls "' . $filePath . '" /grant Users:F');
                    } else {
                        @chmod($filePath, 0644);
                    }
                    echo json_encode(['success' => true]);
                } else {
                    throw new Exception('Gagal menyimpan file foto.');
                }
            } else {
                throw new Exception('Data foto tidak ditemukan di database.');
            }
        } catch (Throwable $e) {
            http_response_code(500);
             echo json_encode([
                'success' => false, 
                'message' => 'Terjadi error saat menyimpan foto: ' . $e->getMessage()
            ]);
        }
    }
}