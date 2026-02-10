<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;

/**
 * Ini adalah simulasi dari Payment Gateway Service.
 * Di aplikasi nyata, Anda akan menggunakan SDK resmi (misal, Midtrans-php).
 */
class PaymentService
{
    private $config;

    public function __construct()
    {
        $this->config = require dirname(__DIR__, 2) . '/config/payment.php';

        // Validate configuration (sandbox keys start with SB-Mid-)
        if (empty($this->config['server_key']) || strlen($this->config['server_key']) < 20) {
            throw new \Exception('Invalid or missing Midtrans server key. Please check your payment configuration.');
        }
        if (empty($this->config['client_key']) || strlen($this->config['client_key']) < 20) {
            throw new \Exception('Invalid or missing Midtrans client key. Please check your payment configuration.');
        }

        Config::$serverKey = $this->config['server_key'];
        Config::$isProduction = $this->config['is_production'];
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // SSL Configuration: Use system CA bundle in production for better compatibility
        if ($this->config['is_production']) {
            // Production: Use system's default CA bundle (more reliable)
            Config::$curlOptions = array(
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            );
            error_log('PaymentService: Using production mode with system CA bundle');
        } else {
            // Sandbox: Use custom CA bundle
            Config::$curlOptions = array(
                CURLOPT_CAINFO => dirname(__DIR__, 2) . '/vendor/midtrans/midtrans-php/data/cacert.pem',
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
            );
            error_log('PaymentService: Using sandbox mode with custom CA bundle');
        }

        // Log configuration for debugging
        error_log('PaymentService initialized - Production: ' . ($this->config['is_production'] ? 'YES' : 'NO'));
        error_log('PaymentService - Server Key: ' . substr($this->config['server_key'], 0, 15) . '...');
    }

    /**
     * Membuat transaksi di payment gateway dan mendapatkan token/URL pembayaran.
     * @param array $transactionDetails
     * @return array ['token' => '...', 'redirect_url' => '...']
     */
    public function createTransaction($transactionDetails)
    {
        try {
            // Log transaction creation attempt
            error_log("=== Midtrans Transaction Creation Started ===");
            error_log("Order ID: " . ($transactionDetails['transaction_details']['order_id'] ?? 'N/A'));
            error_log("Amount: " . ($transactionDetails['transaction_details']['gross_amount'] ?? 'N/A'));
            error_log("Production Mode: " . ($this->config['is_production'] ? 'YES' : 'NO'));
            error_log("Transaction Details: " . json_encode($transactionDetails, JSON_PRETTY_PRINT));

            // Use only getSnapToken for popup integration
            $snapToken = Snap::getSnapToken($transactionDetails);

            error_log("=== Midtrans Transaction Created Successfully ===");
            error_log("Snap Token: " . substr($snapToken, 0, 20) . "...");
            error_log("Token Length: " . strlen($snapToken));

            // For popup integration, we only need the token
            // The redirect URL is only needed for redirect-based integration
            return ['token' => $snapToken, 'redirect_url' => null];

        } catch (\Throwable $e) {
            error_log("=== Midtrans Transaction Creation FAILED ===");
            error_log("Error Message: " . $e->getMessage());
            error_log("Error Code: " . $e->getCode());
            error_log("Error File: " . $e->getFile() . ":" . $e->getLine());
            error_log("Stack Trace: " . $e->getTraceAsString());

            // Check if it's a Midtrans API error with additional details
            if (method_exists($e, 'getHttpBody')) {
                error_log("Midtrans HTTP Response Body: " . $e->getHttpBody());
            }
            if (method_exists($e, 'getHttpStatus')) {
                error_log("Midtrans HTTP Status Code: " . $e->getHttpStatus());
            }

            // Log cURL-specific errors if available
            if (strpos($e->getMessage(), 'cURL') !== false || strpos($e->getMessage(), 'SSL') !== false) {
                error_log("SSL/Network Error Detected - Check server connectivity and SSL certificates");
            }

            // Re-throw with more context
            throw new \Exception("Midtrans API Error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")", $e->getCode(), $e);
        }
    }

    /**
     * Test Midtrans API connectivity and credential validity
     * @return array ['success' => bool, 'message' => string, 'details' => array]
     */
    public function testConnection()
    {
        try {
            error_log("=== Testing Midtrans API Connection ===");

            // Create a minimal test transaction
            $testOrderId = 'TEST-CONNECTION-' . time();
            $testTransaction = [
                'transaction_details' => [
                    'order_id' => $testOrderId,
                    'gross_amount' => 10000,
                ],
                'item_details' => [
                    [
                        'id' => 'test-item',
                        'price' => 10000,
                        'quantity' => 1,
                        'name' => 'Connection Test',
                    ]
                ],
                'customer_details' => [
                    'first_name' => 'Test',
                    'last_name' => 'User',
                    'email' => 'test@example.com',
                    'phone' => '08123456789',
                ],
            ];

            // Attempt to get snap token
            $snapToken = Snap::getSnapToken($testTransaction);

            error_log("Connection test successful - Token: " . substr($snapToken, 0, 20) . "...");

            return [
                'success' => true,
                'message' => 'Successfully connected to Midtrans API',
                'details' => [
                    'mode' => $this->config['is_production'] ? 'Production' : 'Sandbox',
                    'token_generated' => true,
                    'token_length' => strlen($snapToken),
                    'test_order_id' => $testOrderId,
                ]
            ];

        } catch (\Throwable $e) {
            error_log("Connection test failed: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to connect to Midtrans API',
                'details' => [
                    'mode' => $this->config['is_production'] ? 'Production' : 'Sandbox',
                    'error' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'is_ssl_error' => (strpos($e->getMessage(), 'SSL') !== false || strpos($e->getMessage(), 'cURL') !== false),
                ]
            ];
        }
    }
}