<?php

namespace App\Services;

use App\Models\EmailQueue;
use App\Services\EmailService;

class EmailQueueService
{
    private $emailQueue;
    private $emailService;

    public function __construct()
    {
        $this->emailQueue = new EmailQueue();
        $this->emailService = new EmailService();
    }

    /**
     * Add email to queue for background processing
     */
    public function queueSessionEmail($recipientEmail, $attachments)
    {
        $subject = 'Foto-foto Menakjubkan dari Sesi Photobooth Anda!';
        $body = $this->getSessionEmailBody();
        
        return $this->emailQueue->add($recipientEmail, 'Photobooth User', $subject, $body, $attachments);
    }

    /**
     * Process pending emails from queue
     */
    public function processPendingEmails($limit = 5)
    {
        $pendingEmails = $this->emailQueue->getPending($limit);
        $processed = 0;

        foreach ($pendingEmails as $emailJob) {
            try {
                // Mark as processing
                $this->emailQueue->markProcessing($emailJob->id);

                // Decode attachments
                $attachments = json_decode($emailJob->attachments ?: '[]', true);

                // Send email
                $success = $this->emailService->sendSessionPhotos(
                    $emailJob->email,
                    $attachments
                );

                if ($success) {
                    $this->emailQueue->markSent($emailJob->id);
                    $processed++;
                } else {
                    $this->emailQueue->markFailed($emailJob->id, 'Email service returned false');
                }

            } catch (\Exception $e) {
                $this->emailQueue->markFailed($emailJob->id, $e->getMessage());
                error_log('Email queue processing error: ' . $e->getMessage());
            }
        }

        return $processed;
    }

    /**
     * Get email body template for session emails
     */
    private function getSessionEmailBody()
    {
        return '
            <!DOCTYPE html>
            <html>
            <head>
                <meta charset="UTF-8">
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
                    .content { background: white; padding: 30px; border-radius: 0 0 10px 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
                    .footer { text-align: center; margin-top: 20px; color: #666; font-size: 14px; }
                    .highlight { color: #6C63FF; font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="container">
                    <div class="header">
                        <h1>📸 Photobooth Memories!</h1>
                        <p>Terima kasih telah berpartisipasi!</p>
                    </div>
                    <div class="content">
                        <h2>Halo!</h2>
                        <p>Sesi foto Anda di photobooth telah selesai dan hasilnya <span class="highlight">luar biasa</span>!</p>
                        
                        <p>Kami telah menyiapkan semua foto dan photostrip Anda dalam email ini:</p>
                        <ul>
                            <li>📷 Foto-foto terbaik dari sesi Anda</li>
                            <li>🎨 Photostrip yang sudah diolah dengan frame pilihan</li>
                            <li>📁 File ZIP berisi semua koleksi foto</li>
                        </ul>
                        
                        <p>Semua file sudah dilampirkan dalam email ini. Unduh dan simpan untuk kenangan yang tak terlupakan!</p>
                        
                        <p>Jangan lupa untuk membagikannya di media sosial dan tag kami!</p>
                    </div>
                    <div class="footer">
                        <p>🎉 Sampai jumpa di acara selanjutnya!</p>
                        <small>Email otomatis dari sistem Photobooth</small>
                    </div>
                </div>
            </body>
            </html>
        ';
    }

    /**
     * Get queue statistics
     */
    public function getQueueStats()
    {
        return $this->emailQueue->getStats();
    }
}