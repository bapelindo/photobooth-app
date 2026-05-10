<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\ImageProcessingService; // Pastikan service ini ada dan benar
use Exception;
use Throwable;
use PHPMailer\PHPMailer\Exception as PHPMailerException;
use Imagick;

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
            if (
                $sessionWorkflowStep !== 'frame_selection_unlocked' ||
                $sessionCurrentTransactionId != $transaction_id ||
                empty($frameSelectionToken)
            ) {
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
            if (
                $sessionWorkflowStep !== 'photo_session_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($photoSessionToken)
            ) {
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
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');

        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        // Log debug info
        error_log("=== saveSessionPhoto DEBUG ===");
        error_log("POST data: " . print_r($_POST, true));
        error_log("FILES data: " . print_r($_FILES, true));
        error_log("Server upload max filesize: " . ini_get('upload_max_filesize'));
        error_log("Server post max size: " . ini_get('post_max_size'));
        error_log("Server memory limit: " . ini_get('memory_limit'));

        $session_id = $_POST['session_id'] ?? null;
        $photoFile = $_FILES['photo'] ?? null;

        if (!$session_id || !$photoFile) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => 'Missing data',
                'debug' => [
                    'session_id' => $session_id,
                    'photo_file' => $photoFile ? 'found' : 'not found',
                    'post' => $_POST,
                    'files' => $_FILES
                ]
            ]);
            return;
        }

        // Check for upload errors
        if ($photoFile['error'] !== UPLOAD_ERR_OK) {
            $uploadErrors = [
                UPLOAD_ERR_INI_SIZE => 'File terlalu besar (melebihi upload_max_filesize)',
                UPLOAD_ERR_FORM_SIZE => 'File terlalu besar (melebihi MAX_FILE_SIZE)',
                UPLOAD_ERR_PARTIAL => 'File hanya terupload sebagian',
                UPLOAD_ERR_NO_FILE => 'Tidak ada file yang diupload',
                UPLOAD_ERR_NO_TMP_DIR => 'Temporary folder tidak ditemukan',
                UPLOAD_ERR_CANT_WRITE => 'Gagal menulis file ke disk',
                UPLOAD_ERR_EXTENSION => 'File upload dihentikan oleh extension'
            ];

            $errorMsg = $uploadErrors[$photoFile['error']] ?? "Unknown upload error code: {$photoFile['error']}";

            error_log("Upload error: " . $errorMsg);

            http_response_code(400);
            echo json_encode([
                'success' => false,
                'error' => $errorMsg,
                'error_code' => $photoFile['error']
            ]);
            return;
        }

        try {
            // Validate session
            $photoSessionModel = $this->model('PhotoSession');
            $session = $photoSessionModel->find($session_id);
            if (!$session) {
                throw new Exception('Session not found');
            }

            // Create upload directory with proper permissions
            $uploadDir = dirname(APPROOT) . '/public/uploads/session_photos/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0775, true)) {
                    throw new Exception('Gagal membuat directory upload');
                }
                // Set Windows permissions for non-admin write access
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $uploadDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }

            // Check if directory is writable
            if (!is_writable($uploadDir)) {
                throw new Exception('Directory upload tidak writable. Cek permission folder.');
            }

            // Generate unique filename
            $filename = 'session_' . $session_id . '_' . uniqid() . '.png';
            $filePath = $uploadDir . $filename;
            $relativeFilePath = '/uploads/session_photos/' . $filename;

            error_log("Saving photo to: " . $filePath);

            // Move uploaded file
            if (!move_uploaded_file($photoFile['tmp_name'], $filePath)) {
                throw new Exception('Gagal memindahkan file upload. Cek disk space dan permissions.');
            }

            // Set Windows permissions for the uploaded file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $filePath . '" /grant Users:F');
                exec('icacls "' . $filePath . '" /grant IUSR:F');
                exec('icacls "' . $filePath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($filePath, 0644);
            }

            // Verify file was saved
            if (!file_exists($filePath)) {
                throw new Exception('File tidak berhasil disimpan ke disk');
            }

            error_log("File saved successfully: " . $filePath);

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
            error_log("Error in saveSessionPhoto: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
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
            if (
                $sessionWorkflowStep !== 'photo_session_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($layoutEditorToken)
            ) {
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
            if (
                $sessionWorkflowStep !== 'layout_editor_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($decorationEditorToken)
            ) {
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
            if (
                $sessionWorkflowStep !== 'decoration_editor_active' ||
                $sessionCurrentSessionId != $session_id ||
                empty($finalizeSessionToken)
            ) {
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


    private function generateFinalPhotostrip($photostrip)
    {
        try {
            $outputDir = dirname(APPROOT) . '/public/uploads/final_photostrips/';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
                // Set Windows permissions for non-admin write access
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $outputDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }

            $filename = 'final_photostrip_' . $photostrip->id . '_' . uniqid() . '.png';
            $relativePath = '/uploads/final_photostrips/' . $filename;
            $outputPath = $outputDir . $filename;

            $imageService = new \App\Services\ImageProcessingService();

            // --- SIMPLIFIED APPROACH: Always prefer original layout file first ---
            // Priority 1: Find original layout file (photostrip_layout_*.png) - always fresh from layout editor
            $photostripsDir = dirname(APPROOT) . '/public/uploads/photostrips/';
            $pattern = 'photostrip_layout_' . $photostrip->session_id . '_' . $photostrip->frame_id . '_*.png';
            $layoutFiles = glob($photostripsDir . $pattern);

            if (!empty($layoutFiles)) {
                // Use the first (and should be only) matching file
                $sourcePath = $layoutFiles[0];

                // Load with Imagick to resize to 600x1800 (decoration editor uses 600x1800 coordinate system)
                $imagick = new Imagick($sourcePath);
                $currentWidth = $imagick->getImageWidth();
                $currentHeight = $imagick->getImageHeight();
                error_log("Layout file original size: {$currentWidth}x{$currentHeight}");

                // Resize to 600x1800 to match decoration editor coordinate system
                $imagick->resizeImage(600, 1800, Imagick::FILTER_LANCZOS, 1);
                $imagick->setImageFormat('png');
                $imagick->writeImage($outputPath);
                $imagick->clear();

                error_log("Resized layout file from {$currentWidth}x{$currentHeight} to 600x1800: {$outputPath}");

                // CRITICAL: Composite frame again on top to ensure it covers photos
                $framePath = dirname(APPROOT) . '/public' . $photostrip->frame_path;
                if (file_exists($framePath)) {
                    $baseImage = new Imagick($outputPath);
                    $frameImage = new Imagick($framePath);
                    $frameImage->resizeImage(600, 1800, Imagick::FILTER_LANCZOS, 1);
                    $baseImage->compositeImage($frameImage, Imagick::COMPOSITE_OVER, 0, 0);
                    $baseImage->setImageFormat('png');
                    $baseImage->writeImage($outputPath);
                    $baseImage->clear();
                    $frameImage->clear();
                    error_log("Composited frame on top to ensure proper layering");
                }

                // Set Windows permissions
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $outputPath . '" /grant Users:F');
                    exec('icacls "' . $outputPath . '" /grant IUSR:F');
                    exec('icacls "' . $outputPath . '" /grant IIS_IUSRS:F');
                }
            }
            // Priority 2: Try final_image_path from database if layout file not found
            elseif ($photostrip->final_image_path) {
                $sourcePath = dirname(APPROOT) . '/public' . $photostrip->final_image_path;
                if (file_exists($sourcePath)) {
                    if (copy($sourcePath, $outputPath)) {
                        error_log("Copied from database final_image_path: {$sourcePath} -> {$outputPath}");
                        // Set Windows permissions
                        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                            exec('icacls "' . $outputPath . '" /grant Users:F');
                            exec('icacls "' . $outputPath . '" /grant IUSR:F');
                            exec('icacls "' . $outputPath . '" /grant IIS_IUSRS:F');
                        }
                    } else {
                        error_log("Failed to copy final_image_path: {$sourcePath}");
                    }
                } else {
                    error_log("final_image_path file not found: {$sourcePath}");
                }
            }

            // If output file still doesn't exist, try fallback method (createPhotoStrip)
            if (!file_exists($outputPath)) {
                error_log("Output file still not created, trying fallback method...");
                $layoutData = json_decode($photostrip->layout_data ?: '[]', true) ?: [];
                $slotCoordinates = json_decode($photostrip->slot_coordinates ?: '[]', true) ?: [];
                $photosData = [];
                if (!empty($layoutData)) {
                    foreach ($layoutData as $slotIndex => $photo) {
                        if (!is_array($photo))
                            continue;
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
                                $photosData[(int) ($photo['slot'] ?? $slotIndex)] = ['path' => $photoPath, 'panX' => $photo['panX'] ?? 0.5, 'panY' => $photo['panY'] ?? 0.5];
                            }
                        }
                    }
                }
                $framePath = dirname(APPROOT) . '/public' . $photostrip->frame_path;
                if (!file_exists($framePath))
                    return null;
                $imageService->createPhotoStrip($photosData, $framePath, $outputPath, $slotCoordinates, 'none');
            }

            // [FIXED] Koordinat sudah dalam 600x1800 dari decoration editor
            // Tidak perlu scaling - langsung pakai koordinat dari data
            $decorationPayload = json_decode($photostrip->decoration_data ?: '[]', true) ?: [];

            error_log("=== DECORATION DEBUG (Photostrip ID #{$photostrip->id}) ===");
            error_log("Decoration data exists: " . (!empty($photostrip->decoration_data) ? 'YES' : 'NO'));
            error_log("Decoration payload: " . json_encode($decorationPayload));
            error_log("Has canvas_context: " . (isset($decorationPayload['canvas_context']) ? 'YES' : 'NO'));
            error_log("Has stickers: " . (isset($decorationPayload['stickers']) ? 'YES' : 'NO'));

            if (isset($decorationPayload['canvas_context']) && isset($decorationPayload['stickers'])) {
                $ctx = $decorationPayload['canvas_context'];
                $decorationData = $decorationPayload['stickers'];
                $stickers = [];

                error_log("══════════════════ BACKEND (PhotoController) ══════════════════");
                error_log("Canvas Context: {$ctx['width']}x{$ctx['height']}");
                error_log("Sticker count: " . count($decorationData));

                if (!empty($decorationData)) {
                    // Koordinat sudah dalam 600x1800, langsung pakai
                    foreach ($decorationData as $idx => $decoration) {
                        $stickerPath = dirname(APPROOT) . '/public' . $decoration['stickerPath'];
                        if (file_exists($stickerPath)) {
                            $stickers[] = [
                                'path' => $stickerPath,
                                'x' => (int) round($decoration['x']),
                                'y' => (int) round($decoration['y']),
                                'width' => (int) round($decoration['width']),
                                'height' => (int) round($decoration['height'])
                            ];

                            error_log("Sticker #{$idx}: x={$decoration['x']}, y={$decoration['y']}, w={$decoration['width']}, h={$decoration['height']}");
                        } else {
                            error_log("Sticker #{$idx}: FILE NOT FOUND - {$stickerPath}");
                        }
                    }
                    error_log("═══════════════════════════════════════════════════════════");

                    if (!empty($stickers)) {
                        error_log("Applying " . count($stickers) . " stickers to {$outputPath}");
                        $tempPath = $outputDir . 'temp_' . $filename;
                        error_log("Temp path: {$tempPath}");
                        $result = $imageService->applyOverlays($outputPath, null, $stickers, $tempPath);
                        error_log("applyOverlays result: " . ($result ? 'SUCCESS' : 'FAILED'));
                        error_log("Temp file exists: " . (file_exists($tempPath) ? 'YES' : 'NO'));

                        if (file_exists($tempPath)) {
                            error_log("Renaming temp file to output path");
                            rename($tempPath, $outputPath);
                            error_log("Final file exists after rename: " . (file_exists($outputPath) ? 'YES' : 'NO'));
                        } else {
                            error_log("ERROR: Temp file was not created!");
                        }
                    } else {
                        error_log("No stickers to apply (stickers array is empty)");
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

            // Trigger webhook if configured
            if ($queueId && defined('QUEUE_PROCESS_MODE') && QUEUE_PROCESS_MODE === 'webhook') {
                $baseUrl = defined('WEBHOOK_URL') && !empty(WEBHOOK_URL) ? rtrim(WEBHOOK_URL, '/') : URLROOT;
                $url = $baseUrl . "/webhook/print";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_exec($ch);
                curl_close($ch);
            }

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
                            'path' => URLROOT . $photostrip->final_image_path, // [DYNAMIC FIX] Use absolute URL to capture current domain
                            'name' => 'photostrip_' . $photostrip->frame_name . '.png'
                        ];
                    }
                }
            }

            // Add ZIP file
            if ($zipPath && file_exists($zipPath)) {
                // Convert absolute path to relative web path
                $relativePath = str_replace(dirname(APPROOT) . '/public', '', $zipPath);
                $relativePath = str_replace('\\', '/', $relativePath);

                $attachments[] = [
                    'path' => URLROOT . $relativePath, // [DYNAMIC FIX] Use absolute URL to capture current domain
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

            // Trigger webhook if configured
            if ($queueId && defined('QUEUE_PROCESS_MODE') && QUEUE_PROCESS_MODE === 'webhook') {
                $baseUrl = defined('WEBHOOK_URL') && !empty(WEBHOOK_URL) ? rtrim(WEBHOOK_URL, '/') : URLROOT;
                $url = $baseUrl . "/webhook/email";
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT_MS, 100);
                curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
                curl_exec($ch);
                curl_close($ch);
            }

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
            // Set Windows permissions for non-admin write access
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $zipDir . '" /grant Users:(OI)(CI)F');
                exec('icacls "' . $zipDir . '" /grant IUSR:(OI)(CI)F');
                exec('icacls "' . $zipDir . '" /grant IIS_IUSRS:(OI)(CI)F');
            }
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

    public function downloadSession($session_id)
    {
        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);

        if (!$session) {
            die('Session not found');
        }

        $photostripModel = $this->model('Photostrip');
        $photostrips = $photostripModel->getBySessionId($session_id);
        $sessionPhotos = $photoSessionModel->getSavedPhotos($session_id);

        $data = [
            'session' => $session,
            'photostrips' => $photostrips,
            'session_photos' => $sessionPhotos,
        ];

        $this->view('photo/download_gallery', $data);
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
                    'is_printed' => (bool) $photostrip->is_printed
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

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
                // Set Windows permissions for non-admin write access
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $outputDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }
            if (!file_exists($scriptPath))
                throw new Exception('Skrip capture_sony.py tidak ditemukan.');

            $filename = 'capture_' . uniqid() . '.png';
            $pythonPath = 'python';
            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$outputDir\" \"$filename\" \"$sdkDebugPath\"");
            $output = shell_exec("$command 2>&1");

            $relativePath = trim($output);

            $capturedFile = $outputDir . DIRECTORY_SEPARATOR . $filename;
            if (strpos($relativePath, '/uploads/captures/') === 0 && file_exists($capturedFile)) {
                // Set Windows permissions for the captured file
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $capturedFile . '" /grant Users:F');
                    exec('icacls "' . $capturedFile . '" /grant IUSR:F');
                    exec('icacls "' . $capturedFile . '" /grant IIS_IUSRS:F');
                }
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
                    // Set Windows permissions for the saved file
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        exec('icacls "' . $filePath . '" /grant Users:F');
                        exec('icacls "' . $filePath . '" /grant IUSR:F');
                        exec('icacls "' . $filePath . '" /grant IIS_IUSRS:F');
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