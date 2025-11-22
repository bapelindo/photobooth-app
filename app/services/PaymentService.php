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

        // Setel path ke sertifikat CA bundle
        Config::$curlOptions = array(
            CURLOPT_CAINFO => dirname(__DIR__, 2) . '/vendor/midtrans/midtrans-php/data/cacert.pem'
        );
        
        // Note: apiBase and snapBase are not configurable properties in Midtrans Config
        // The library handles these automatically based on isProduction setting
    }

    /**
     * Membuat transaksi di payment gateway dan mendapatkan token/URL pembayaran.
     * @param array $transactionDetails
     * @return array ['token' => '...', 'redirect_url' => '...']
     */
    public function createTransaction($transactionDetails)
    {
        try {
            // Add debug log to see what's being sent to Midtrans
            error_log("Midtrans Transaction Details: " . json_encode($transactionDetails));
            
            // Verify configuration
            error_log("Midtrans Config - Server Key: " . substr($this->config['server_key'], 0, 10) . "...");
            error_log("Midtrans Config - Is Production: " . ($this->config['is_production'] ? 'true' : 'false'));

            // Use only getSnapToken for popup integration
            $snapToken = Snap::getSnapToken($transactionDetails);
            error_log("Generated Snap Token: " . $snapToken);
            
            // For popup integration, we only need the token
            // The redirect URL is only needed for redirect-based integration
            return ['token' => $snapToken, 'redirect_url' => null];
            
        } catch (\Throwable $e) {
            error_log("Midtrans Error: " . $e->getMessage());
            error_log("Midtrans Error Code: " . $e->getCode());
            error_log("Midtrans Error File: " . $e->getFile() . ":" . $e->getLine());
            
            // Check if it's a Midtrans API error
            if (method_exists($e, 'getHttpBody')) {
                error_log("Midtrans HTTP Body: " . $e->getHttpBody());
            }
            if (method_exists($e, 'getHttpStatus')) {
                error_log("Midtrans HTTP Status: " . $e->getHttpStatus());
            }
            
            // Re-throw with more context
            throw new \Exception("Midtrans API Error: " . $e->getMessage() . " (Code: " . $e->getCode() . ")", $e->getCode(), $e);
        }
    }
}