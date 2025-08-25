<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function process($package_id)
    {
        // 1. Dapatkan detail paket
        $packageModel = $this->model('Package');
        $package = $packageModel->find($package_id);
        if (!$package) {
            die('Paket tidak ditemukan.');
        }

        // 2. Buat record transaksi di database dengan status 'pending'
        $transactionModel = $this->model('Transaction');
        $order_id = 'PHOTOBOOT-' . time() . '-' . $package_id;
        $transactionId = $transactionModel->create([
            'package_id' => $package_id,
            'amount' => $package->price,
            'payment_status' => 'pending',
            'order_id' => $order_id,
        ]);

        // 3. Siapkan detail untuk dikirim ke payment gateway
        $transactionDetails = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $package->price,
            ],
            'item_details' => [[
                'id' => $package->id,
                'price' => $package->price,
                'quantity' => 1,
                'name' => $package->name,
            ]],
            // 'customer_details' => [...] // Anda bisa menambahkan detail pelanggan
        ];

        // 4. Panggil PaymentService untuk mendapatkan URL pembayaran
        $paymentService = new PaymentService();
        $paymentInfo = $paymentService->createTransaction($transactionDetails);

        // 5. Update record transaksi dengan token dan URL
        $transactionModel->updatePaymentInfo($transactionId, $paymentInfo['token'], $paymentInfo['redirect_url']);

        // 6. Arahkan pengguna ke halaman pembayaran
        header('Location: ' . $paymentInfo['redirect_url']);
        exit();
    }

    public function finish()
    {
        $order_id = $_GET['order_id'] ?? null;
        $status_code = $_GET['status_code'] ?? null;
        $transaction_status = $_GET['transaction_status'] ?? null;

        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->findByOrderId($order_id);

        // Simulasi callback: update status jika belum diupdate
        if ($transaction && $transaction->payment_status === 'pending' && $transaction_status === 'capture') {
            $transactionModel->updateStatusByOrderId($order_id, 'success');
            $transaction->payment_status = 'success'; // Update objek lokal
        }

        $data['transaction'] = $transaction;
        $this->view('payment/finish', $data);
    }

    public function callback()
    {
        // Ini adalah endpoint untuk notifikasi server-to-server dari payment gateway.
        // Di aplikasi nyata, Anda akan memverifikasi signature key, lalu update status transaksi.
        // $notification = json_decode(file_get_contents('php://input'), true);
        // ... logika verifikasi dan update ...
        http_response_code(200);
    }
}