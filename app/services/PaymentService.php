<?php

namespace App\Services;

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
        // Di aplikasi nyata:
        // \Midtrans\Config::$serverKey = $this->config['server_key'];
        // \Midtrans\Config::$isProduction = $this->config['is_production'];
        // \Midtrans\Config::$isSanitized = true;
        // \Midtrans\Config::$is3ds = true;
    }

    /**
     * Membuat transaksi di payment gateway dan mendapatkan token/URL pembayaran.
     * @param array $transactionDetails
     * @return array ['token' => '...', 'redirect_url' => '...']
     */
    public function createTransaction($transactionDetails)
    {
        // Di aplikasi nyata, Anda akan memanggil:
        // $snapToken = \Midtrans\Snap::getSnapToken($transactionDetails);
        // $snapUrl = \Midtrans\Snap::createTransaction($transactionDetails)->redirect_url;

        // Simulasi untuk demo:
        $snapToken = 'dummy-snap-token-' . $transactionDetails['transaction_details']['order_id'];
        // URL finish simulasi kita
        $snapUrl = \URLROOT . '/payment/finish?order_id=' . $transactionDetails['transaction_details']['order_id'] . '&status_code=200&transaction_status=capture';

        return ['token' => $snapToken, 'redirect_url' => $snapUrl];
    }
}