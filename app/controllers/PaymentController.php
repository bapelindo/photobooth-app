<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Services\PaymentService;
use Exception;

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

    public function getSnapToken($package_id)
    {
        // Start with basic error logging
        error_log('PaymentController::getSnapToken called with package_id: ' . $package_id);
        
        // Suppress any unexpected output and ensure clean JSON response
        error_reporting(E_ALL);
        ini_set('display_errors', 0);
        
        try {
            ob_clean();
            header('Content-Type: application/json');
            
            // Basic validation
            if (empty($package_id) || !is_numeric($package_id)) {
                error_log('PaymentController::getSnapToken - Invalid package ID');
                http_response_code(400);
                echo json_encode([
                    'success' => false,
                    'error' => 'Invalid package ID provided'
                ]);
                return;
            }
            
            error_log('PaymentController::getSnapToken - Package ID validation passed');
            
            error_log('PaymentController::getSnapToken - Loading Package model');
            $packageModel = $this->model('Package');
            
            error_log('PaymentController::getSnapToken - Finding package with ID: ' . $package_id);
            $package = $packageModel->find($package_id);
            if (!$package) {
                error_log('PaymentController::getSnapToken - Package not found');
                http_response_code(404);
                echo json_encode([
                    'success' => false,
                    'error' => 'Package not found'
                ]);
                return;
            }
            
            error_log('PaymentController::getSnapToken - Package found: ' . $package->name);

            // Load models with error checking
            error_log('PaymentController::getSnapToken - Loading Transaction model');
            $transactionModel = $this->model('Transaction');
            if (!$transactionModel) {
                error_log('PaymentController::getSnapToken - Failed to load Transaction model');
                throw new Exception('Failed to load Transaction model');
            }
            
            $order_id = 'PHOTOBOOT-' . time() . '-' . $package_id;
            $transactionId = $transactionModel->create([
                'package_id' => $package_id,
                'amount' => $package->price,
                'payment_status' => 'pending',
                'order_id' => $order_id,
            ]);

            // Start session for workflow
            Session::start();
            Session::unset('payment_finished_displayed');
            Session::set('workflow_step', 'payment_initiated');
            Session::set('current_transaction_id', $transactionId);

            $transactionDetails = [
                'transaction_details' => [
                    'order_id' => $order_id,
                    'gross_amount' => (int)$package->price, // Ensure integer
                ],
                'item_details' => [[
                    'id' => (string)$package->id,
                    'price' => (int)$package->price,
                    'quantity' => 1,
                    'name' => (string)$package->name,
                ]],
                'customer_details' => [
                    'first_name' => 'Customer',
                    'last_name' => 'Photobooth',
                    'email' => 'customer@photobooth.com',
                    'phone' => '08123456789',
                ],
            ];

            error_log('PaymentController::getSnapToken - Creating PaymentService');
            try {
                $paymentService = new PaymentService();
                error_log('PaymentController::getSnapToken - PaymentService created successfully');
            } catch (Exception $serviceException) {
                error_log('PaymentController::getSnapToken - PaymentService creation failed: ' . $serviceException->getMessage());
                throw new Exception('PaymentService initialization failed: ' . $serviceException->getMessage());
            }
            
            error_log('PaymentController::getSnapToken - Calling createTransaction');
            $paymentInfo = $paymentService->createTransaction($transactionDetails);
            error_log('PaymentController::getSnapToken - Transaction created successfully');

            $transactionModel->updatePaymentInfo($transactionId, $paymentInfo['token'], $paymentInfo['redirect_url']);

            echo json_encode([
                'success' => true,
                'snap_token' => $paymentInfo['token'],
                'transaction_id' => $transactionId,
                'order_id' => $order_id
            ]);

        } catch (Exception $e) {
            error_log('PaymentController::getSnapToken Error: ' . $e->getMessage());
            error_log('PaymentController::getSnapToken Stack trace: ' . $e->getTraceAsString());
            
            // Make sure we send a response even if there's an error
            try {
                ob_clean();
                header('Content-Type: application/json');
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'error' => 'Failed to create payment: ' . $e->getMessage(),
                    'debug_info' => $e->getFile() . ':' . $e->getLine()
                ]);
            } catch (Exception $innerException) {
                error_log('PaymentController::getSnapToken - Even error response failed: ' . $innerException->getMessage());
                echo json_encode(['success' => false, 'error' => 'Critical error occurred']);
            }
        }
    }

    public function testEndpoint()
    {
        ob_clean();
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Endpoint is working',
            'timestamp' => time()
        ]);
    }

    public function testMidtrans()
    {
        ob_clean();
        header('Content-Type: application/json');
        
        try {
            // Simple test transaction
            $testOrderId = 'TEST-' . time();
            $testTransaction = [
                'transaction_details' => [
                    'order_id' => $testOrderId,
                    'gross_amount' => 10000,
                ],
                'item_details' => [[
                    'id' => 'test-item',
                    'price' => 10000,
                    'quantity' => 1,
                    'name' => 'Test Item',
                ]],
                'customer_details' => [
                    'first_name' => 'Test',
                    'last_name' => 'Customer',
                    'email' => 'test@example.com',
                    'phone' => '08123456789',
                ],
            ];
            
            $paymentService = new PaymentService();
            $result = $paymentService->createTransaction($testTransaction);
            
            echo json_encode([
                'success' => true,
                'message' => 'Midtrans test successful',
                'snap_token' => substr($result['token'], 0, 20) . '...',
                'redirect_url' => $result['redirect_url']
            ]);
            
        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ]);
        }
    }

    public function getTransactionByOrder($order_id)
    {
        header('Content-Type: application/json');
        
        try {
            $transactionModel = $this->model('Transaction');
            $transaction = $transactionModel->findByOrderId($order_id);
            
            if ($transaction) {
                // Update payment status to success if not already
                if ($transaction->payment_status !== 'success') {
                    $transactionModel->updateStatusByOrderId($order_id, 'success');
                }
                
                // Set session workflow step
                Session::start();
                Session::set('workflow_step', 'frame_selection_unlocked');
                Session::set('current_transaction_id', $transaction->id);
                
                echo json_encode([
                    'success' => true,
                    'transaction_id' => $transaction->id
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'error' => 'Transaction not found'
                ]);
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Failed to get transaction: ' . $e->getMessage()
            ]);
        }
    }

    public function finish($transaction_id = null)
    {
        Session::start();
        
        if (ENABLE_SESSION_REFRESH_BACK && Session::get('payment_finished_displayed')) {
            $this->flashAndRedirect('packages', 'Transaksi telah selesai diproses. Silakan mulai lagi jika ingin membuat yang baru.');
            exit();
        }

        // Support both transaction_id parameter and order_id from GET
        $order_id = $_GET['order_id'] ?? null;
        $transaction_status = $_GET['transaction_status'] ?? null;

        $transactionModel = $this->model('Transaction');
        
        // If transaction_id is provided as parameter, use it
        if ($transaction_id) {
            $transaction = $transactionModel->find($transaction_id);
            if ($transaction && $transaction->payment_status !== 'success') {
                // Mark as success if coming from successful payment redirect
                $transactionModel->updateStatus($transaction->id, 'success');
                $transaction->payment_status = 'success';
            }
        } elseif ($order_id) {
            $transaction = $transactionModel->findByOrderId($order_id);
        } else {
            header('Location: /photobooth-app/public/packages');
            exit();
        }
        
        // Process a pending transaction
        if ($transaction && $transaction->payment_status === 'pending' && ($transaction_status === 'capture' || $transaction_status === 'settlement')) {
            if ($order_id) {
                $transactionModel->updateStatusByOrderId($order_id, 'success');
            } else {
                // If using transaction_id directly, update by ID
                $transactionModel->updateStatus($transaction->id, 'success');
            }
            $transaction->payment_status = 'success'; // Update local object for view
            
            Session::set('workflow_step', 'frame_selection_unlocked');
            Session::set('current_transaction_id', $transaction->id);
        }

        if ($transaction && $transaction->payment_status === 'success') {
            Session::set('payment_finished_displayed', true);
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