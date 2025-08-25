<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\CameraService;
use App\Services\ImageProcessingService;

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
    // Pastikan transaksi ada sebelum memulai sesi foto
    $transactionModel = $this->model('Transaction');
    if (!$transactionModel->find($transaction_id)) {
        die("Transaction with ID {$transaction_id} not found. Please complete payment first.");
    }

    $assetModel = $this->model('Asset'); // <-- Make sure the Asset model is loaded

    // Muat frame yang dipilih jika ada
    $data['selected_frame'] = null;
    if ($frame_id) {
        // Use the find method from the Asset model
        $data['selected_frame'] = $assetModel->find($frame_id);
    }

    // Muat semua stiker
    $data['stickers'] = $assetModel->getAssetsByType('sticker');

    // Pass the transaction_id to the view as well
    $data['transaction_id'] = $transaction_id;

    $this->view('photo/capture', $data);
}
    public function editor($photo_id)
    {
        $photoModel = $this->model('Photo');
        $data['photo'] = $photoModel->find($photo_id);

        $assetModel = $this->model('Asset');
        $data['stickers'] = $assetModel->getAssetsByType('sticker');

        $this->view('photo/editor', $data);
    }

    public function ajax_take_photo()
    {
        header('Content-Type: application/json');

        // Terima data dari request POST (AJAX)
        $input = json_decode(file_get_contents('php://input'), true);
        $framePath = $input['frame'] ?? null;    // path relatif frame
        $stickers = $input['stickers'] ?? [];  // array data stiker
        $transactionId = $input['transaction_id']; // Ambil ID transaksi

        // 1. Panggil CameraService untuk mengambil foto dari DSLR
        $cameraService = new CameraService();
        try {
            $rawPhotoFilename = $cameraService->takePhoto();
            $baseFilepath = '../public/photos/' . $rawPhotoFilename;
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Camera Error: ' . $e->getMessage()]);
            return;
        }

        // 2. Siapkan path untuk pemrosesan
        $finalFilename = 'photo_final_' . uniqid() . '.jpg';
        $finalFilepath = '../public/photos/' . $finalFilename;
        $fullFramePath = !empty($framePath) ? '../public' . $framePath : null;

        $processedStickers = [];
        if (!empty($stickers)) {
            foreach ($stickers as $sticker) {
                $processedStickers[] = [
                    'path' => '../public' . $sticker['path'],
                    'x' => (int)$sticker['x'],
                    'y' => (int)$sticker['y'],
                    'width' => (int)$sticker['width'],
                    'height' => (int)$sticker['height']
                ];
            }
        }

        // 3. Panggil ImageProcessingService
        $imageService = new ImageProcessingService();

        try {
            $success = $imageService->applyOverlays($baseFilepath, $fullFramePath, $processedStickers, $finalFilepath);
            if (!$success) {
                throw new \Exception('Failed to process image with overlays.');
            }

            // Hapus foto mentah setelah berhasil diproses
            unlink($baseFilepath);

            // 4. Simpan record foto ke database
            $photoModel = $this->model('Photo');
            $photo_id = $photoModel->create([
                'transaction_id' => $transactionId,
                'file_path' => '/photos/' . $finalFilename
            ]);

            // 5. Kirim kembali URL gambar yang sudah jadi
            echo json_encode(['success' => true, 'photo_id' => $photo_id, 'photo_url' => '/photos/' . $finalFilename, 'photo_path' => $finalFilepath]);
        } catch (\Exception $e) {
            if (file_exists($baseFilepath)) unlink($baseFilepath);
            echo json_encode(['success' => false, 'message' => 'Image processing failed: ' . $e->getMessage()]);
        }
    }

    public function ajax_save_photo()
    {
        header('Content-Type: application/json');

        $input = json_decode(file_get_contents('php://input'), true);
        $imageData = $input['image'];
        $photo_id = $input['photo_id'];

        $photoModel = $this->model('Photo');
        $photo = $photoModel->find($photo_id);

        if ($photo) {
            $filePath = '../public' . $photo->file_path;

            // Remove the 'data:image/jpeg;base64,' part from the string
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $decodedImage = base64_decode($imageData);

            if (file_put_contents($filePath, $decodedImage)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to save photo.']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Photo not found.']);
        }
    }

    // Metode untuk mengirim email akan ditambahkan di sini
}