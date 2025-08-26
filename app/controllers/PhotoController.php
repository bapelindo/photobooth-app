<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\ImageProcessingService;
use Exception;
use Throwable;
// --- TAMBAHKAN BARIS INI ---
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PhotoController extends Controller
{
    public function selectFrame($transaction_id)
    {
        $assetModel = $this->model('Asset');
        $data['frames'] = $assetModel->getAssetsByType('frame');
        $data['transaction_id'] = $transaction_id;
        $this->view('photo/select_frame', $data);
    }

    public function capture($transaction_id, $frame_id = null)
    {
        \App\Core\Session::start();

        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($transaction_id);
        if (!$transaction) {
            die("Transaksi dengan ID {$transaction_id} tidak ditemukan. Mohon selesaikan pembayaran terlebih dahulu.");
        }

        $packageModel = $this->model('Package');
        $package = $packageModel->find($transaction->package_id);
        if (!$package) {
            die("Paket untuk transaksi ini tidak ditemukan.");
        }

        // Atur jumlah retake awal di sesi jika belum ada
        $session_key = 'retake_limit_' . $transaction_id;
        if (!\App\Core\Session::has($session_key)) {
            \App\Core\Session::set($session_key, $package->retake_limit);
        }
        
        $data['retakes_left'] = \App\Core\Session::get($session_key, 0);

        $assetModel = $this->model('Asset');
        $data['selected_frame'] = null;
        if ($frame_id) {
            $data['selected_frame'] = $assetModel->find($frame_id);
        }
        
        // Mengambil data filter dari database
        $data['filters'] = $assetModel->getAssetsByType('filter');

        $data['transaction_id'] = $transaction_id;
        $data['frame_id'] = $frame_id; 
        $data['package'] = $package;

        $this->view('photo/capture', $data);
    }

    public function editor($photo_id)
    {
        $photoModel = $this->model('Photo');
        $data['photo'] = $photoModel->find($photo_id);

        if (!$data['photo']) {
            die('Foto tidak ditemukan.');
        }

        $assetModel = $this->model('Asset');
        $data['stickers'] = $assetModel->getAssetsByType('sticker');

        $this->view('photo/editor', $data);
    }
    
    public function finalize($photo_id)
    {
        $photoModel = $this->model('Photo');
        $data['photo'] = $photoModel->find($photo_id);

        if (!$data['photo']) {
            die('Foto tidak ditemukan.');
        }

        $this->view('photo/finalize', $data);
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
        } catch (PHPMailerException $e) { // --- PERBAIKAN DI SINI ---
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $e->errorMessage()]);
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function ajax_process_photostrip()
    {
        header('Content-Type: application/json');
        
        $tempFiles = [];

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            if (json_last_error() !== JSON_ERROR_NONE || !isset($input['transaction_id'], $input['photos']) || !is_array($input['photos'])) {
                throw new Exception("Input JSON tidak valid atau data tidak lengkap.");
            }

            $transactionId = $input['transaction_id'];
            $framePath = $input['frame_path'] ?? null;
            $filter = $input['filter'] ?? 'none'; // Menangkap data filter
            $photosData = $input['photos'];
            
            $photoDir = dirname(APPROOT) . '/public/uploads/photo/';
            
            if (!is_dir($photoDir)) {
                mkdir($photoDir, 0775, true);
            }

            foreach ($photosData as $index => $dataUrl) {
                $imgData = preg_replace('/^data:image\/\w+;base64,/', '', $dataUrl);
                $decodedImage = base64_decode($imgData);
                
                $tempFilename = $photoDir . 'temp_' . $transactionId . '_' . uniqid() . '_' . $index . '.jpg';
                if (file_put_contents($tempFilename, $decodedImage) === false) {
                    throw new Exception("Gagal menyimpan file sementara. Periksa izin folder 'public/uploads/photo'.");
                }
                $tempFiles[] = $tempFilename;
            }

            $finalFilename = 'photostrip_' . uniqid() . '.jpg';
            $finalFilepath = $photoDir . $finalFilename;
            $fullFramePath = !empty($framePath) ? dirname(APPROOT) . '/public' . $framePath : null;

            $imageService = new ImageProcessingService();
            
            // Meneruskan filter ke service
            $success = $imageService->createPhotoStrip($tempFiles, $fullFramePath, $finalFilepath, $filter);

            foreach ($tempFiles as $file) {
                if (file_exists($file)) unlink($file);
            }

            if (!$success) {
                throw new Exception('Gagal membuat gambar photostrip via ImageMagick.');
            }

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $finalFilepath . '" /grant Everyone:F /T');
            } else {
                @chmod($finalFilepath, 0644);
            }

            $photoModel = $this->model('Photo');
            $photoModel->create([
                'transaction_id' => $transactionId,
                'file_path' => '/uploads/photo/' . $finalFilename
            ]);
            
            $new_photo_id = $photoModel->lastInsertId();

            echo json_encode([
                'success' => true, 
                'photo_id' => $new_photo_id, 
                'final_url' => URLROOT . '/photo/finalize/' . $new_photo_id
            ]);

        } catch (Throwable $e) {
            foreach ($tempFiles as $file) {
                if (file_exists($file)) unlink($file);
            }
            
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Server Error: ' . $e->getMessage(), 
                'file' => $e->getFile(), 
                'line' => $e->getLine()
            ]);
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
                
                $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
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
    // photobooth-app/app/controllers/PhotoController.php

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

            // --- PERBAIKAN PATH DIMULAI DI SINI ---

            // 1. Tentukan path dasar proyek.
            $basePath = dirname(APPROOT, 2);

            // 2. Gabungkan path menggunakan DIRECTORY_SEPARATOR untuk konsistensi.
            //    Ubah slash dari database menjadi separator yang benar.
            $photoPath = $basePath . DIRECTORY_SEPARATOR . 'photobooth-app\public' . str_replace('/', DIRECTORY_SEPARATOR, $photo->file_path);
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'photobooth-app\scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';

            // 3. Verifikasi path yang sudah diperbaiki.
            if (!file_exists($photoPath)) {
                // Berikan pesan error yang lebih jelas dengan path yang sudah dinormalisasi.
                throw new Exception('File foto tidak ditemukan di server: ' . $photoPath);
            }

            if (!file_exists($scriptPath)) {
                throw new Exception('Skrip cetak Python tidak ditemukan.');
            }
            
            // --- PERBAIKAN PATH SELESAI ---

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
            // Path absolut ke direktori utama aplikasi
            $basePath = dirname(APPROOT, 2) . DIRECTORY_SEPARATOR . 'photobooth-app';
            
            // --- PERBAIKAN DIMULAI DI SINI ---
            // 1. Definisikan path absolut ke folder Debug
            $sdkDebugPath = $basePath . DIRECTORY_SEPARATOR . 'Debug';

            $outputDir = $basePath . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'captures';
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'capture_sony.py';

            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
            }
            if (!file_exists($scriptPath)) {
                throw new Exception('Skrip capture_sony.py tidak ditemukan.');
            }

            $filename = 'capture_' . uniqid() . '.jpg';
            $pythonPath = 'python';

            // 2. Kirim path absolut sebagai argumen baru ke skrip Python
            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$outputDir\" \"$filename\" \"$sdkDebugPath\"");
            // --- PERBAIKAN SELESAI ---
            
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
}