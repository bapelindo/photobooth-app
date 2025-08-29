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
            // Handle package not found, e.g., redirect or show error
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
        Session::unset('payment_finished_displayed'); // Clear previous flag
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

        if (!$order_id) {
            header('Location: /photobooth-app/public/packages');
            exit();
        }

        $transactionModel = $this->model('Transaction');
        $transaction = $transactionModel->findByOrderId($order_id);

        if (ENABLE_SESSION_REFRESH_BACK) {
            if ($transaction && $transaction->payment_status === 'success') {
                // Already processed. Don't resume, just redirect to start.
                $this->flashAndRedirect('packages', 'Transaksi telah selesai diproses. Silakan mulai lagi jika ingin membuat yang baru.');
                exit();
            }
        }
        
        // Process a pending transaction
        if ($transaction && $transaction->payment_status === 'pending' && ($transaction_status === 'capture' || $transaction_status === 'settlement')) {
            $transactionModel->updateStatusByOrderId($order_id, 'success');
            $transaction->payment_status = 'success'; // Update local object for view
            
            Session::set('workflow_step', 'frame_selection_unlocked');
            Session::set('current_transaction_id', $transaction->id);
        }

        $data['transaction'] = $transaction;
        $this->view('payment/finish', $data);
    }


    public function callback()
    {
        // Load Midtrans configuration
        $config = require '../config/payment.php';
        \Midtrans\Config::$serverKey = $config['server_key'];
        \Midtrans\Config::$isProduction = $config['is_production'];
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        // Create Midtrans Notification object
        $notif = new \Midtrans\Notification();

        $transaction = $notif->transaction_status;
        $type = $notif->payment_type;
        $order_id = $notif->order_id;
        $fraud = $notif->fraud_status;

        error_log('PaymentController::callback - Received callback for Order ID: ' . $order_id . ', Transaction Status: ' . $transaction . ', Payment Type: ' . $type . ', Fraud Status: ' . $fraud);

        $transactionModel = $this->model('Transaction');
        $currentTransaction = $transactionModel->findByOrderId($order_id);

        if ($currentTransaction) {
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        // TODO set transaction status on your database to 'challenge'
                        $transactionModel->updateStatusByOrderId($order_id, 'challenge');
                        error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to challenge');
                    } else {
                        // TODO set transaction status on your database to 'success'
                        $transactionModel->updateStatusByOrderId($order_id, 'success');
                        error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to success (capture)');
                    }
                }
            } elseif ($transaction == 'settlement') {
                // TODO set transaction status on your database to 'success'
                $transactionModel->updateStatusByOrderId($order_id, 'success');
                error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to success (settlement)');
            } elseif ($transaction == 'pending') {
                // TODO set transaction status on your database to 'pending' / waiting payment
                $transactionModel->updateStatusByOrderId($order_id, 'pending');
                error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to pending');
            } elseif ($transaction == 'deny') {
                // TODO set transaction status on your database to 'deny'
                $transactionModel->updateStatusByOrderId($order_id, 'deny');
                error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to deny');
            } elseif ($transaction == 'expire') {
                // TODO set transaction status on your database to 'expire'
                $transactionModel->updateStatusByOrderId($order_id, 'expire');
                error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to expire');
            } elseif ($transaction == 'cancel') {
                // TODO set transaction status on your database to 'cancel'
                $transactionModel->updateStatusByOrderId($order_id, 'cancel');
                error_log('PaymentController::callback - Order ID: ' . $order_id . ' status set to cancel');
            }
        } else {
            error_log('PaymentController::callback - Transaction not found for Order ID: ' . $order_id);
        }

        http_response_code(200);
    }
}