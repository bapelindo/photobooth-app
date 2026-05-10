<?php

namespace App\Services;

use App\Models\EmailQueue;
use App\Services\EmailService;
use App\Core\Database;

class EmailQueueService
{
    private $emailQueue;
    private $emailService;
    private $db;

    public function __construct()
    {
        $this->emailQueue = new EmailQueue();
        $this->emailService = new EmailService();
        $this->db = new Database();
    }

    /**
     * Add email to queue for background processing with compression
     */
    public function queueSessionEmail($recipientEmail, $attachments)
    {
        // Compress attachments to reduce size
        $compressedAttachments = $this->compressAttachments($attachments);

        // Calculate total size
        $totalSize = 0;
        foreach ($compressedAttachments as $att) {
            if (file_exists($att['path'])) {
                $totalSize += filesize($att['path']);
            }
        }

        $totalMB = round($totalSize / 1024 / 1024, 2);

        // Skip if still too large (>20MB)
        if ($totalMB > 20) {
            error_log("EmailQueueService: Skipping email to $recipientEmail - attachments too large ({$totalMB}MB)");
            return false; // Don't queue oversized emails
        }

        $subject = 'Foto-foto Menakjubkan dari Sesi Photobooth Anda!';
        $body = $this->getSessionEmailBody();

        error_log("EmailQueueService: Queueing email to $recipientEmail with {$totalMB}MB attachments");

        $queueId = $this->emailQueue->add($recipientEmail, 'Photobooth User', $subject, $body, $compressedAttachments);

        if ($queueId && defined('QUEUE_PROCESS_MODE') && QUEUE_PROCESS_MODE === 'webhook') {
            $this->triggerWebhook('email');
        }

        return $queueId;
    }

    /**
     * Trigger webhook asynchronously
     */
    private function triggerWebhook($type)
    {
        $baseUrl = defined('WEBHOOK_URL') && !empty(WEBHOOK_URL) ? rtrim(WEBHOOK_URL, '/') : URLROOT;
        $url = $baseUrl . "/webhook/{$type}";

        // Asynchronous cURL request (timeout 1s)
        $ch = @curl_init();
        if ($ch !== false) {
            @curl_setopt($ch, CURLOPT_URL, $url);
            @curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            @curl_setopt($ch, CURLOPT_TIMEOUT, 1); // 1s timeout
            @curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
            // Execute and immediately ignore response
            @curl_exec($ch);
            @curl_close($ch);
        }
    }

    /**
     * Compress attachments to reduce email size
     */
    private function compressAttachments($attachments)
    {
        $imageService = new \App\Services\ImageProcessingService();
        $compressedAttachments = [];

        foreach ($attachments as $attachment) {
            $originalPath = $attachment['path'];

            // Skip if file doesn't exist
            if (!file_exists($originalPath)) {
                error_log("EmailQueueService: Attachment not found: $originalPath");
                continue;
            }

            // Create compressed version
            $pathInfo = pathinfo($originalPath);
            $compressedPath = $pathInfo['dirname'] . '/compressed_' . $pathInfo['filename'] . '.jpg';

            $compressedSize = $imageService->compressImage($originalPath, $compressedPath, 85);

            if ($compressedSize !== false) {
                // Use compressed version
                $compressedAttachments[] = [
                    'path' => $compressedPath,
                    'name' => $pathInfo['filename'] . '.jpg'
                ];
            } else {
                // Fallback to original if compression fails
                error_log("EmailQueueService: Compression failed for $originalPath, using original");
                $compressedAttachments[] = $attachment;
            }
        }

        return $compressedAttachments;
    }

    /**
     * Reset stuck emails that have been processing for too long
     */
    private function resetStuckEmails()
    {
        // Reset emails that have been in 'processing' status for more than 5 minutes
        // Use created_at since there's no updated_at column
        $query = "UPDATE email_queue 
                  SET status = 'pending' 
                  WHERE status = 'processing' 
                  AND created_at < DATE_SUB(NOW(), INTERVAL 5 MINUTE)";

        $this->db->query($query);
        $this->db->execute();

        $resetCount = $this->db->rowCount();
        if ($resetCount > 0) {
            error_log("Reset $resetCount stuck emails from 'processing' to 'pending'");
        }

        return $resetCount;
    }

    /**
     * Process pending emails from queue
     */
    public function processPendingEmails($limit = 5)
    {
        // First, reset any stuck emails
        $this->resetStuckEmails();

        $pendingEmails = $this->emailQueue->getPending($limit);
        $processed = 0;

        error_log("Found " . count($pendingEmails) . " pending emails to process");

        foreach ($pendingEmails as $emailJob) {
            try {
                error_log("Processing email #{$emailJob->id} to {$emailJob->email}");

                // Mark as processing
                $this->emailQueue->markProcessing($emailJob->id);

                // Decode attachments
                $attachments = json_decode($emailJob->attachments ?: '[]', true);
                error_log("Email #{$emailJob->id} has " . count($attachments) . " attachments");

                // Send email
                $success = $this->emailService->sendSessionPhotos(
                    $emailJob->email,
                    $attachments
                );

                if ($success) {
                    $this->emailQueue->markSent($emailJob->id);
                    $processed++;
                    error_log("✅ Email #{$emailJob->id} sent successfully");
                } else {
                    $this->emailQueue->markFailed($emailJob->id, 'Email service returned false');
                    error_log("❌ Email #{$emailJob->id} failed: Email service returned false");
                }

            } catch (\Exception $e) {
                $this->emailQueue->markFailed($emailJob->id, $e->getMessage());
                error_log("❌ Email #{$emailJob->id} failed with exception: " . $e->getMessage());
                error_log("Stack trace: " . $e->getTraceAsString());
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