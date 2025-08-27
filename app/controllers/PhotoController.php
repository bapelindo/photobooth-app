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
        
        if (Session::get('workflow_step') !== 'frame_selection_unlocked' || Session::get('current_transaction_id') != $transaction_id) {
            $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
        }
        
        Session::set('workflow_step', 'frame_selected');

        // Get the package photo limit to filter frames
        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($transaction_id);
        if (!$transaction) {
            die("Transaksi tidak ditemukan.");
        }
        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);
        if (!$package) {
            die("Paket tidak ditemukan.");
        }

        $assetModel = $this->model('Asset');
        // Pass the photo_limit to the model method
        $data['frames'] = $assetModel->getAssetsByType('frame', $package->photo_limit);
        $data['transaction_id'] = $transaction_id;
        $this->view('photo/select_frame', $data);
    }

    public function capture($transaction_id, $frame_id = null)
    {
        Session::start();

        if (Session::get('workflow_step') !== 'frame_selected' || Session::get('current_transaction_id') != $transaction_id) {
            $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
        }

        Session::set('selected_frame_id', $frame_id);
        Session::set('workflow_step', 'capture_started');
        
        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($transaction_id);
        if (!$transaction) { die("Transaksi tidak ditemukan."); }

        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);
        if (!$package) { die("Paket tidak ditemukan."); }
        
        $session_key = 'retake_limit_' . $transaction_id;
        if (!Session::has($session_key)) {
            Session::set($session_key, $package->retake_limit);
        }
        $data['retakes_left'] = Session::get($session_key, 0);
        
        $assetModel = $this->model('Asset');
        $data['selected_frame'] = $frame_id ? $assetModel->find($frame_id) : null;
        $data['filters'] = $assetModel->getAssetsByType('filter');
        $data['transaction_id'] = $transaction_id;
        $data['frame_id'] = $frame_id;
        $data['package'] = $package;
        $this->view('photo/capture', $data);
    }

    public function editor()
    {
        Session::start();

        if (Session::get('workflow_step') !== 'editor_unlocked') {
            $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
        }
        
        Session::set('workflow_step', 'editing_started');
        
        $photostripPath = Session::get('photostrip_path');
        if (!$photostripPath) {
            $this->flashAndRedirect('packages', 'Gagal memproses photostrip. Silakan mulai dari awal.');
        }

        $data['photostrip_url'] = URLROOT . $photostripPath;
        $data['transaction_id'] = Session::get('transaction_id');
        
        $assetModel = $this->model('Asset');
        $data['stickers'] = $assetModel->getAssetsByType('sticker');

        $this->view('photo/editor', $data);
    }
    
    public function finalize($photo_id)
    {
        Session::start();
        
        if (Session::get('workflow_step') !== 'finalize_unlocked') {
             $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
        }
        
        Session::unset('workflow_step');
        Session::unset('current_transaction_id');
        
        $photoModel = $this->model('Photo');
        $data['photo'] = $photoModel->find($photo_id);
        if (!$data['photo']) { die('Foto tidak ditemukan.'); }

        $this->view('photo/finalize', $data);
    }

    public function ajax_save_captured_photos()
    {
        header('Content-Type: application/json');
        Session::start();
        
        if (Session::get('workflow_step') !== 'capture_started') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Akses tidak sah.']);
            return;
        }

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['photos']) || !is_array($input['photos'])) {
                throw new Exception("Input foto tidak valid.");
            }

            $basePath = dirname(APPROOT) . '/public';
            $tempPhotoPaths = [];
            $tempDir = $basePath . '/uploads/temp/';
            if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);

            foreach ($input['photos'] as $key => $photoData) {
                $photoData = str_replace('data:image/jpeg;base64,', '', $photoData);
                $photoData = str_replace(' ', '+', $photoData);
                $filePath = $tempDir . uniqid('photo_') . '.jpg';
                file_put_contents($filePath, base64_decode($photoData));
                $tempPhotoPaths[] = $filePath;
            }

            $frameId = Session::get('selected_frame_id');
            $frame = null;
            $slotCoordinates = null;
            if ($frameId) {
                $assetModel = $this->model('Asset');
                $frame = $assetModel->find($frameId);
                if ($frame) {
                    $slotCoordinates = json_decode($frame->slot_coordinates, true);
                }
            }

            $framePath = $frame ? $basePath . $frame->path : null;
            $outputStripDir = $basePath . '/uploads/photo/';
            if (!is_dir($outputStripDir)) mkdir($outputStripDir, 0775, true);
            $outputStripFilename = 'photostrip_pre_' . uniqid() . '.jpg';
            $outputStripFullPath = $outputStripDir . $outputStripFilename;

            $imageService = new ImageProcessingService();
            $success = $imageService->createPhotoStrip(
                $tempPhotoPaths,
                $framePath,
                $outputStripFullPath,
                $slotCoordinates, // Pass coordinates
                $input['filter'] ?? 'none'
            );

            foreach ($tempPhotoPaths as $path) {
                @unlink($path);
            }

            if (!$success) {
                throw new Exception("Gagal membuat photostrip di server.");
            }

            Session::set('workflow_step', 'editor_unlocked');
            Session::set('photostrip_path', '/uploads/photo/' . $outputStripFilename);
            Session::set('transaction_id', $input['transaction_id']);

            Session::unset('captured_photos');
            Session::unset('frame_path');
            Session::unset('filter');

            echo json_encode(['success' => true, 'editor_url' => URLROOT . '/photo/editor']);

        } catch (Throwable $e) {
            http_response_code(500);
            error_log('Error in ajax_save_captured_photos: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()]);
        }
    }

    public function ajax_save_final_photostrip()
    {
        header('Content-Type: application/json');
        Session::start();

        if (Session::get('workflow_step') !== 'editing_started') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Akses tidak sah.']);
            return;
        }
        
        Session::set('workflow_step', 'finalize_unlocked');

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $imageData = $input['image'];
            $transactionId = Session::get('transaction_id');

            if (!$imageData || !$transactionId) {
                throw new Exception("Data gambar atau ID transaksi tidak ditemukan.");
            }

            $photoDir = dirname(APPROOT) . '/public/uploads/photo/';
            if (!is_dir($photoDir)) mkdir($photoDir, 0775, true);

            $tempStripPath = dirname(APPROOT) . '/public' . Session::get('photostrip_path');
            if (file_exists($tempStripPath)) {
                @unlink($tempStripPath);
            }

            $finalFilename = 'photostrip_' . uniqid() . '.png';
            $finalFilepath = $photoDir . $finalFilename;
            
            $imageData = str_replace('data:image/png;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            file_put_contents($finalFilepath, base64_decode($imageData));

            $photoModel = $this->model('Photo');
            $photoModel->create([
                'transaction_id' => $transactionId,
                'file_path' => '/uploads/photo/' . $finalFilename
            ]);
            
            $new_photo_id = $photoModel->lastInsertId();

            Session::unset('photostrip_path');
            
            echo json_encode([
                'success' => true, 
                'photo_id' => $new_photo_id,
                'finalize_url' => URLROOT . '/photo/finalize/' . $new_photo_id
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function send_email()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Metode request tidak valid.');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $photo_id = $input['photo_id'] ?? null;
            $email = $input['email'] ?? null;

            if (!$photo_id || !$email) {
                throw new Exception('Photo ID dan email diperlukan.');
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Alamat email tidak valid.');
            }

            $photoModel = $this->model('Photo');
            $photo = $photoModel->find($photo_id);

            if (!$photo) {
                throw new Exception('Data foto tidak ditemukan.');
            }

            $photoPath = dirname(APPROOT) . '/public' . $photo->file_path;
            
            if (!file_exists($photoPath) || !is_readable($photoPath)) {
                error_log("Gagal mengirim email: File foto tidak ditemukan di " . $photoPath);
                throw new Exception('File foto tidak dapat ditemukan di server.');
            }

            $emailService = new \App\Services\EmailService();
            $photoFilename = basename($photo->file_path);

            $success = $emailService->sendPhoto($email, 'Tamu Photobooth', $photoPath, $photoFilename);

            if ($success) {
                $photoModel->updateEmailedTo($photo_id, $email);
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Layanan email gagal mengirim foto. Cek konfigurasi SMTP.');
            }
        } catch (PHPMailerException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $e->errorMessage()]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function ajax_print_photo()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Metode request tidak valid.');
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $photo_id = $input['photo_id'] ?? null;

            if (!$photo_id) {
                throw new Exception('Photo ID diperlukan.');
            }
            
            $photoModel = $this->model('Photo');
            $photo = $photoModel->find($photo_id);

            if (!$photo) {
                throw new Exception('Data foto tidak ditemukan.');
            }
            
            $basePath = dirname(APPROOT);
            $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $photo->file_path);
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';

            if (!file_exists($photoPath)) {
                throw new Exception('File foto tidak ditemukan di server: ' . $photoPath);
            }

            if (!file_exists($scriptPath)) {
                throw new Exception('Skrip cetak Python tidak ditemukan.');
            }
            
            $pythonPath = 'python';
            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$photoPath\"");
            $output = shell_exec("$command 2>&1");
            
            if (strpos(strtolower($output), 'error') !== false) {
                throw new Exception('Gagal mencetak foto. Respons dari printer: ' . $output);
            }
            
            echo json_encode(['success' => true, 'message' => 'Foto telah dikirim ke printer!', 'debug_output' => $output]);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

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

            $filename = 'capture_' . uniqid() . '.jpg';
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
