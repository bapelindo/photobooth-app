<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\PaymentService;

class PaymentController extends Controller
{
    public function process($package_id)
    {
        $packageModel = $this->model('Package');
        $package = $packageModel->find($package_id);
        if (!$package) {
            die('Paket tidak ditemukan.');
        }

        $transactionModel = $this->model('Transaction');
        $order_id = 'PHOTOBOOT-' . time() . '-' . $package_id;
        $transactionId = $transactionModel->create([
            'package_id' => $package_id,
            'amount' => $package->price,
            'payment_status' => 'pending',
            'order_id' => $order_id,
        ]);

        // Memulai sesi dan menandai langkah alur kerja
        Session::start();
        Session::set('workflow_step', 'payment_initiated');
        Session::set('current_transaction_id', $transactionId);

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
        ];

        $paymentService = new PaymentService();
        $paymentInfo = $paymentService->createTransaction($transactionDetails);

        $transactionModel->updatePaymentInfo($transactionId, $paymentInfo['token'], $paymentInfo['redirect_url']);

        header('Location: ' . $paymentInfo['redirect_url']);
        exit();
    }

    public function finish()
    {
        Session::start();
        $order_id = $_GET['order_id'] ?? null;
        $transaction_status = $_GET['transaction_status'] ?? null;

        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->findByOrderId($order_id);

        if ($transaction && $transaction->payment_status === 'pending' && $transaction_status === 'capture') {
            $transactionModel->updateStatusByOrderId($order_id, 'success');
            
            // Perbarui langkah alur kerja setelah pembayaran berhasil
            Session::set('workflow_step', 'frame_selection_unlocked');
            
            $transaction->payment_status = 'success';
        }

        $data['transaction'] = $transaction;
        $this->view('payment/finish', $data);
    }

    public function callback()
    {
        http_response_code(200);
    }
}