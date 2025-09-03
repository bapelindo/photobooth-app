<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\ImageProcessingService;
use Exception;
use Throwable;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class PhotoController extends Controller
{
    // ... (Metode selectFrame, capture, ajax_save_raw_photos, layoutEditor, ajax_process_layout tetap sama)

    public function selectFrame($transaction_id)
    {
        Session::start();
        
        if (ENABLE_SESSION_REFRESH_BACK) {
            $sessionWorkflowStep = Session::get('workflow_step');
            $sessionCurrentTransactionId = Session::get('current_transaction_id');
            if (($sessionWorkflowStep !== 'frame_selection_unlocked') || ($sessionCurrentTransactionId != $transaction_id)) {
                $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
            }
        }
        
        Session::set('workflow_step', 'frame_selected');

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

        $assetModel = $this->model('Asset');
        $data['frames'] = $assetModel->getAssetsByType('frame', 3);
        $data['transaction_id'] = $transaction_id;
        $data['package'] = $package;
        $this->view('photo/select_frame', $data);
    }
    
    public function capture($transaction_id)
    {
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_frames'])) {
            Session::set('selected_frame_ids', $_POST['selected_frames']);
        }

        $selectedFrameIds = Session::get('selected_frame_ids');
        if (!$selectedFrameIds || !is_array($selectedFrameIds)) {
            $this->flashAndRedirect('packages', 'Silakan pilih bingkai terlebih dahulu.');
        }

        if (ENABLE_SESSION_REFRESH_BACK) {
            if (Session::get('workflow_step') !== 'frame_selected' || Session::get('current_transaction_id') != $transaction_id) {
                $this->flashAndRedirect('packages', 'Sesi sebelumnya telah berakhir atau tidak valid. Silakan mulai lagi.');
            }
        }
        Session::set('workflow_step', 'capture_started');

        $packageModel = $this->model('Package');
        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->find($transaction_id);
        $package = $packageModel->find($transaction->package_id);

        $assetModel = $this->model('Asset');
        $allSlotsData = [];
        foreach ($selectedFrameIds as $frameId) {
            $frame = $assetModel->find($frameId);
            if ($frame && $frame->slot_coordinates) {
                $slots = json_decode($frame->slot_coordinates, true);
                $allSlotsData = array_merge($allSlotsData, $slots);
            }
        }
        
        $data['transaction_id'] = $transaction_id;
        $data['package'] = $package;
        $data['all_slots_data'] = $allSlotsData;

        $this->view('photo/capture', $data);
    }

    public function ajax_save_raw_photos()
    {
        header('Content-Type: application/json');
        Session::start();

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $transactionId = $input['transaction_id'];
            $photos = $input['photos'];

            if (!$transactionId || !$photos) {
                throw new Exception("Data tidak lengkap.");
            }

            $photoModel = $this->model('Photo');
            $photoDir = dirname(APPROOT) . '/public/uploads/photo/raw/';
            if (!is_dir($photoDir)) mkdir($photoDir, 0775, true);

            foreach ($photos as $photoData) {
                $imageData = str_replace('data:image/jpeg;base64,', '', $photoData['imageData']);
                $imageData = str_replace(' ', '+', $imageData);
                $filename = 'raw_' . uniqid() . '.jpg';
                $filepath = $photoDir . $filename;
                
                file_put_contents($filepath, base64_decode($imageData));

                $photoModel->create([
                    'transaction_id' => $transactionId,
                    'file_path' => '/uploads/photo/raw/' . $filename,
                    'type' => 'raw'
                ]);
            }
            
            Session::set('workflow_step', 'layout_editor_unlocked');
            
            echo json_encode([
                'success' => true,
                'editor_url' => URLROOT . '/photo/layoutEditor/' . $transactionId
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
            error_log('Error in ajax_save_raw_photos: ' . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan internal: ' . $e->getMessage()]);
        }
    }

    public function layoutEditor($transaction_id)
    {
        Session::start();
        $photoModel = $this->model('Photo');
        $assetModel = $this->model('Asset');

        $data['transaction_id'] = $transaction_id;
        $data['raw_photos'] = $photoModel->getRawPhotosByTransaction($transaction_id);
        
        $selectedFrameIds = Session::get('selected_frame_ids');
        $selectedFramesData = [];
        foreach($selectedFrameIds as $id) {
            $frame = $assetModel->find($id);
            if ($frame) {
                $selectedFramesData[] = [
                    'id' => $frame->id,
                    'path' => $frame->path,
                    'slot_coordinates' => json_decode($frame->slot_coordinates, true)
                ];
            }
        }
        $data['selected_frames_with_slots'] = $selectedFramesData;
        
        $this->view('photo/layout_editor', $data);
    }
    
    public function ajax_process_layout()
    {
        header('Content-Type: application/json');
        Session::start();

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $transactionId = $input['transaction_id'];
            $finalImages = $input['final_images'];

            $tempDir = dirname(APPROOT) . '/public/uploads/temp/';
            if (!is_dir($tempDir)) mkdir($tempDir, 0775, true);

            $photostripUrls = [];
            foreach ($finalImages as $imageData) {
                 $imageData = str_replace('data:image/png;base64,', '', $imageData);
                 $imageData = str_replace(' ', '+', $imageData);
                 $filename = 'strip_layout_' . uniqid() . '.png';
                 file_put_contents($tempDir . $filename, base64_decode($imageData));
                 $photostripUrls[] = URLROOT . '/uploads/temp/' . $filename;
            }

            Session::set('photostrip_temp_urls', $photostripUrls);
            Session::set('workflow_step', 'sticker_editor_unlocked');

            echo json_encode([
                'success' => true,
                'sticker_editor_url' => URLROOT . '/photo/editor/' . $transactionId
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function editor($transaction_id)
    {
        Session::start();
        
        $photostripUrls = Session::get('photostrip_temp_urls');
        if (!$photostripUrls) {
            $this->flashAndRedirect('packages', 'Sesi layout tidak ditemukan.');
        }

        $data['photostrip_urls'] = $photostripUrls;
        $data['transaction_id'] = $transaction_id;
        
        $assetModel = $this->model('Asset');
        $data['stickers'] = $assetModel->getAssetsByType('sticker');

        $this->view('photo/editor', $data);
    }

    public function ajax_save_final_photostrip()
    {
        header('Content-Type: application/json');
        Session::start();

        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $transactionId = $input['transaction_id']; // Mengambil dari request
            $images = $input['images'];

            if (!$transactionId || !$images) {
                throw new Exception("Data tidak lengkap.");
            }

            $photoModel = $this->model('Photo');
            $finalPhotoIds = [];
            $photoDir = dirname(APPROOT) . '/public/uploads/photo/final/';
            if (!is_dir($photoDir)) mkdir($photoDir, 0775, true);

            foreach($images as $imageData) {
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = str_replace(' ', '+', $imageData);
                $filename = 'final_strip_' . uniqid() . '.png';
                file_put_contents($photoDir . $filename, base64_decode($imageData));

                $photoModel->create([
                    'transaction_id' => $transactionId,
                    'file_path' => '/uploads/photo/final/' . $filename,
                    'type' => 'final'
                ]);
                $finalPhotoIds[] = $photoModel->lastInsertId();
            }
            
            // Hapus file sementara
            $tempUrls = Session::get('photostrip_temp_urls', []);
            foreach ($tempUrls as $url) {
                $path = str_replace(URLROOT, dirname(APPROOT) . '/public', $url);
                if (file_exists($path)) {
                    @unlink($path);
                }
            }
            Session::unset('photostrip_temp_urls');

            Session::set('final_photo_ids', $finalPhotoIds);
            Session::set('workflow_step', 'finalize_unlocked');

            echo json_encode([
                'success' => true, 
                'finalize_url' => URLROOT . '/photo/finalize/' . $transactionId
            ]);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    /**
     * REVISI: Menampilkan halaman finalisasi.
     */
    public function finalize($transaction_id)
    {
        Session::start();
        
        $photoModel = $this->model('Photo');
        // REVISI: Menggunakan method yang benar untuk mengambil foto final
        $data['final_photos'] = $photoModel->getAllFinalPhotosByTransaction($transaction_id); 
        
        if (empty($data['final_photos'])) {
             $this->flashAndRedirect('packages', 'Foto final tidak ditemukan.');
        }

        $data['transaction_id'] = $transaction_id;
        $this->view('photo/finalize', $data);
    }
    
    public function send_email()
    {
        header('Content-Type: application/json');
        Session::start();
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $transactionId = $input['transaction_id'] ?? null;
            $email = $input['email'] ?? null;

            if (!$transactionId || !$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception('Data tidak valid.');
            }

            $photoModel = $this->model('Photo');
            $rawPhotos = $photoModel->getRawPhotosByTransaction($transactionId);
            $finalPhotos = $photoModel->getAllFinalPhotosByTransaction($transactionId);

            $emailService = new \App\Services\EmailService();
            $success = $emailService->sendAllPhotos($email, 'Tamu Photobooth', $rawPhotos, $finalPhotos);

            if ($success) {
                foreach(array_merge($rawPhotos, $finalPhotos) as $photo) {
                    $photoModel->updateEmailedTo($photo->id, $email);
                }
                echo json_encode(['success' => true]);
            } else {
                throw new Exception('Layanan email gagal mengirim foto.');
            }
        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function ajax_print_photo()
    {
        header('Content-Type: application/json');
        
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $transactionId = $input['transaction_id'] ?? null;
            if (!$transactionId) throw new Exception('ID Transaksi diperlukan.');
            
            $photoModel = $this->model('Photo');
            $finalPhotos = $photoModel->getAllFinalPhotosByTransaction($transactionId);
            if (empty($finalPhotos)) throw new Exception('Tidak ada foto final untuk dicetak.');

            $basePath = dirname(APPROOT);
            $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';
            if (!file_exists($scriptPath)) throw new Exception('Skrip cetak tidak ditemukan.');

            $pythonPath = 'python';
            $all_output = "";
            foreach ($finalPhotos as $photo) {
                $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $photo->file_path);
                if (file_exists($photoPath)) {
                    $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$photoPath\"");
                    $all_output .= shell_exec("$command 2>&1") . "\n";
                }
            }
            
            echo json_encode(['success' => true, 'message' => 'Semua foto telah dikirim ke printer!', 'debug_output' => $all_output]);

        } catch (Throwable $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}