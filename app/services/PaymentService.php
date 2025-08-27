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
        $this->config = require '../config/payment.php';
        
        Config::$serverKey = $this->config['server_key'];
        Config::$isProduction = $this->config['is_production'];
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Membuat transaksi di payment gateway dan mendapatkan token/URL pembayaran.
     * @param array $transactionDetails
     * @return array ['token' => '...', 'redirect_url' => '...']
     */
    public function createTransaction($transactionDetails)
    {
        // Add debug log to see what's being sent to Midtrans
        error_log("Midtrans Transaction Details: " . json_encode($transactionDetails));

        $snapToken = Snap::getSnapToken($transactionDetails);
        $snapUrl = Snap::createTransaction($transactionDetails)->redirect_url;

        return ['token' => $snapToken, 'redirect_url' => $snapUrl];
    }
}