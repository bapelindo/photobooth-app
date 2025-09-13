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
            $frameSelectionToken = Session::get('frame_selection_token');

            // Only allow access with valid frame selection token (prevents refresh/back/direct access)
            if ($sessionWorkflowStep !== 'frame_selection_unlocked' ||
                $sessionCurrentTransactionId != $transaction_id ||
                empty($frameSelectionToken)) {
                $this->flashAndRedirect('packages', 'Sesi tidak valid atau telah berakhir. Silakan mulai lagi.');
            }

            // Consume the token (prevents refresh)
            Session::set('frame_selection_token', null);
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
        
        // Set one-time access token for photo session (strict mode only)
        if (ENABLE_SESSION_REFRESH_BACK) {
            Session::set('photo_session_token', uniqid('session_', true));
        }

        header('Location: ' . URLROOT . '/photo/session/' . $session_id);
        exit();
    }

    public function photoSession($session_id)
    {
        Session::start();

        // Session refresh/back protection
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentSessionId = Session::get('current_session_id');
            $photoSessionToken = Session::get('photo_session_token');

            // Only allow access with valid photo session token (prevents refresh/back/direct access)
            if ($sessionWorkflowStep !== 'photo_session_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($photoSessionToken)) {
                $this->flashAndRedirect('packages', 'Sesi tidak valid atau telah berakhir. Silakan mulai lagi.');
            }
            
            // Consume the token (prevents refresh)
            Session::set('photo_session_token', null);
        }
        // When ENABLE_SESSION_REFRESH_BACK = false, allow free navigation (no restrictions)

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        
        if (!$session) {
            $this->flashAndRedirect('packages', 'Sesi foto tidak ditemukan.');
        }

        // Check if user is returning to photo session (has existing photos and possibly photostrips)
        // Only clear data when in strict mode (ENABLE_SESSION_REFRESH_BACK = true)
        if (ENABLE_SESSION_REFRESH_BACK) {
            $photoSessionPhotoModel = $this->model('PhotoSessionPhoto');
            $existingPhotos = $photoSessionPhotoModel->getBySessionId($session_id);
            
            $photostripModel = $this->model('Photostrip');
            $existingPhotostrips = $photostripModel->getBySessionId($session_id);
            
            // If user is returning and has existing data, clear it for a fresh start
            if (!empty($existingPhotos) || !empty($existingPhotostrips)) {
                // Clear existing photos
                $photoSessionPhotoModel->clearSessionPhotos($session_id);
                
                // Reset photostrip data but keep the records for frame structure
                foreach ($existingPhotostrips as $photostrip) {
                    $photostripModel->update($photostrip->id, [
                        'layout_data' => null,
                        'decoration_data' => null,
                        'final_image_path' => null,
                        'is_printed' => 0
                    ]);
                }
                
                // Reset session status
                $photoSessionModel->updateStatus($session_id, 'started');
            }
        }
        // When ENABLE_SESSION_REFRESH_BACK = false, preserve existing photos and data

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
                // Generate token for layout editor access (strict mode only)
                if (ENABLE_SESSION_REFRESH_BACK) {
                    Session::start();
                    Session::set('layout_editor_token', uniqid('layout_', true));
                }
                
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

        // Session refresh/back protection
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentSessionId = Session::get('current_session_id');
            $layoutEditorToken = Session::get('layout_editor_token');

            // Only allow access with valid layout editor token (prevents refresh/back/direct access)
            if ($sessionWorkflowStep !== 'photo_session_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($layoutEditorToken)) {
                $this->flashAndRedirect('packages', 'Sesi tidak valid atau telah berakhir. Silakan mulai lagi.');
            }
            
            // Update to current step and consume token
            Session::set('workflow_step', 'layout_editor_active');
            Session::set('layout_editor_token', null);
        }
        // When ENABLE_SESSION_REFRESH_BACK = false, allow free navigation (no restrictions)

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

                // Check if photostrip already exists for this session and frame
                $existingPhotostrip = $photostripModel->findBySessionAndFrame($session_id, $frame_id);
                
                if ($existingPhotostrip) {
                    // Update existing photostrip
                    $photostripModel->update($existingPhotostrip->id, [
                        'layout_data' => json_encode($frame_data[$index]['photos'] ?? []),
                        'final_image_path' => $filePath,
                        'decoration_data' => null, // Reset decorations when layout changes
                        'is_printed' => 0 // Reset print status
                    ]);
                } else {
                    // Create new photostrip record
                    $photostripModel->create([
                        'session_id' => $session_id,
                        'frame_id' => $frame_id,
                        'layout_data' => json_encode($frame_data[$index]['photos'] ?? []),
                        'final_image_path' => $filePath 
                    ]);
                }
            }

            // Generate token for decoration editor access (strict mode only)
            if (ENABLE_SESSION_REFRESH_BACK) {
                Session::start();
                Session::set('decoration_editor_token', uniqid('decoration_', true));
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

        // Session refresh/back protection  
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentSessionId = Session::get('current_session_id');
            $decorationEditorToken = Session::get('decoration_editor_token');

            // Only allow access with valid decoration editor token (prevents refresh/back/direct access)
            if ($sessionWorkflowStep !== 'layout_editor_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($decorationEditorToken)) {
                $this->flashAndRedirect('packages', 'Sesi tidak valid atau telah berakhir. Silakan mulai lagi.');
            }
            
            // Update to current step and consume token
            Session::set('workflow_step', 'decoration_editor_active');
            Session::set('decoration_editor_token', null);
        }
        // When ENABLE_SESSION_REFRESH_BACK = false, allow free navigation (no restrictions)

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

        // [DEBUG] Log data mentah yang diterima dari frontend
        error_log("--- DEBUG saveDecorations (Session #{$session_id}) ---");
        error_log("RAW DATA RECEIVED: " . print_r($decorations, true));
        error_log("--- END DEBUG ---");

        if (!$session_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing session ID']);
            return;
        }

        try {
            $photostripModel = $this->model('Photostrip');
            
            foreach ($decorations as $photostrip_id => $decoration_data) {
                $photostripModel->updateDecorationData($photostrip_id, json_encode($decoration_data));
            }

            // Generate token for finalize session access (strict mode only)
            if (ENABLE_SESSION_REFRESH_BACK) {
                Session::start();
                Session::set('finalize_session_token', uniqid('finalize_', true));
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

        // Session refresh/back protection
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentSessionId = Session::get('current_session_id');
            $finalizeSessionToken = Session::get('finalize_session_token');

            // Only allow access with valid finalize session token (prevents refresh/back/direct access)
            if ($sessionWorkflowStep !== 'decoration_editor_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($finalizeSessionToken)) {
                $this->flashAndRedirect('packages', 'Sesi tidak valid atau telah berakhir. Silakan mulai lagi.');
            }
            
            // Update to current step and consume token
            Session::set('workflow_step', 'finalize_session_active');
            Session::set('finalize_session_token', null);
        }
        // When ENABLE_SESSION_REFRESH_BACK = false, allow free navigation (no restrictions)

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
        $allPhotostrips = $photostripModel->getBySessionId($session_id);

        if (empty($allPhotostrips)) {
            $this->flashAndRedirect('packages', 'Tidak ada photostrip yang dibuat.');
        }

        // Limit photostrips to the package frame_limit to prevent duplication issues
        $frameLimit = $package->frame_limit ?? 2;
        $photostrips = array_slice($allPhotostrips, 0, $frameLimit);

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

// [KALIBRASI FINAL] Sesuaikan nilai ini jika di masa depan ada pergeseran lagi.
    // Tambah nilai untuk menggeser ke KANAN/BAWAH.
    // Kurangi nilai untuk menggeser ke KIRI/ATAS.
    private const FINAL_OFFSET_X = 10; // Koreksi pergeseran ke kiri (dalam piksel)
    private const FINAL_OFFSET_Y = 10; // Koreksi pergeseran ke atas (dalam piksel)

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
            
            // --- Bagian Pembuatan Gambar Dasar (Tidak perlu diubah) ---
            $layoutData = json_decode($photostrip->layout_data ?: '[]', true) ?: [];
            $slotCoordinates = json_decode($photostrip->slot_coordinates ?: '[]', true) ?: [];
            $photosData = [];
            if (!empty($layoutData)) {
                foreach ($layoutData as $slotIndex => $photo) {
                    if (!is_array($photo)) continue;
                    $photoPathKey = null;
                    $possibleKeys = ['photoPath', 'path', 'file_path', 'photo_path'];
                    foreach ($possibleKeys as $key) {
                        if (isset($photo[$key])) { $photoPathKey = $key; break; }
                    }
                    if ($photoPathKey) {
                        $photoPath = dirname(APPROOT) . '/public' . $photo[$photoPathKey];
                        if (file_exists($photoPath)) {
                            $photosData[(int)($photo['slot'] ?? $slotIndex)] = ['path' => $photoPath, 'panX' => $photo['panX'] ?? 0.5, 'panY' => $photo['panY'] ?? 0.5];
                        }
                    }
                }
            }
            $framePath = dirname(APPROOT) . '/public' . $photostrip->frame_path;
            if (!file_exists($framePath)) return null;
            $imageService = new \App\Services\ImageProcessingService();
            $imageService->createPhotoStrip($photosData, $framePath, $outputPath, $slotCoordinates, 'none');
            // --- Akhir Bagian Pembuatan Gambar Dasar ---

            // [LOGIKA FINAL DENGAN KALIBRASI]
            $decorationPayload = json_decode($photostrip->decoration_data ?: '[]', true) ?: [];
            
            if (isset($decorationPayload['canvas_context']) && isset($decorationPayload['stickers'])) {
                $context = $decorationPayload['canvas_context'];
                $decorationData = $decorationPayload['stickers'];
                $stickers = [];

                if (!empty($decorationData)) {
                    $original_w = $context['width'];
                    $original_h = $context['height'];
                    $final_w = 600;
                    $final_h = 1800;

                    $scaleX = ($original_w > 0) ? $final_w / $original_w : 1;
                    $scaleY = ($original_h > 0) ? $final_h / $original_h : 1;

                    foreach ($decorationData as $decoration) {
                        $stickerPath = dirname(APPROOT) . '/public' . $decoration['stickerPath'];
                        if (file_exists($stickerPath)) {
                            $stickers[] = [
                                'path' => $stickerPath,
                                // Terapkan offset setelah penskalaan
                                'x' => (int)($decoration['x'] * $scaleX) + self::FINAL_OFFSET_X,
                                'y' => (int)($decoration['y'] * $scaleY) + self::FINAL_OFFSET_Y,
                                'width' => (int)($decoration['width'] * $scaleX),
                                'height' => (int)($decoration['height'] * $scaleY)
                            ];
                        }
                    }
                
                    if (!empty($stickers)) {
                        $tempPath = $outputDir . 'temp_' . $filename;
                        $imageService->applyOverlays($outputPath, null, $stickers, $tempPath);
                        if (file_exists($tempPath)) {
                            rename($tempPath, $outputPath);
                        }
                    }
                }
            }
            
            return $relativePath;

        } catch (Exception $e) {
            error_log("FATAL ERROR in generateFinalPhotostrip (Photostrip ID #{$photostrip->id}): " . $e->getMessage());
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
        $copies = $input['copies'] ?? 1;

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

            // Check if file exists
            $basePath = dirname(APPROOT);
            $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $photostrip->final_image_path);
            
            if (!file_exists($photoPath)) {
                throw new Exception('Photostrip file not found: ' . $photoPath);
            }

            // Add to print queue instead of printing directly
            $printQueueModel = $this->model('PrintQueue');
            $queueId = $printQueueModel->create([
                'photostrip_id' => $photostrip_id,
                'file_path' => $photostrip->final_image_path,
                'copies' => $copies,
                'priority' => 5 // High priority for user print requests
            ]);

            // Clear workflow session after successful print queue (strict mode only)
            if (ENABLE_SESSION_REFRESH_BACK) {
                Session::set('workflow_step', null);
                Session::set('current_session_id', null);
                Session::set('current_transaction_id', null);
            }

            echo json_encode([
                'success' => true, 
                'message' => 'Photostrip telah ditambahkan ke queue print dan akan dicetak segera',
                'queue_id' => $queueId
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

            // Prepare attachments
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

            // Add email to queue instead of sending directly
                        $emailQueueModel = $this->model('EmailQueue');
            $queueId = $emailQueueModel->add(
                $email,
                'Photobooth User', // recipient name
                'Foto Session Photobooth Anda - Session #' . $session_id,
                'Terima kasih telah menggunakan layanan photobooth kami! Terlampir adalah hasil foto session Anda.',
                $attachments
            );

            // Clear workflow session after successful email queue (strict mode only)
            if (ENABLE_SESSION_REFRESH_BACK) {
                Session::set('workflow_step', null);
                Session::set('current_session_id', null);
                Session::set('current_transaction_id', null);
            }

            echo json_encode([
                'success' => true,
                'message' => 'Email telah ditambahkan ke queue dan akan dikirim segera',
                'queue_id' => $queueId
            ]);

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