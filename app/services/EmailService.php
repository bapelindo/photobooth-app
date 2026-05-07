<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function sendPhoto($toEmail, $toName, $photoPath, $photoFilename)
    {
        $mail = new PHPMailer(true);

        try {
            // Pengaturan Server dari config.php
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;

            // Penerima
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // Lampiran
            $mail->addAttachment($photoPath, $photoFilename);

            // Konten
            $mail->isHTML(true);
            $mail->Subject = 'Ini Dia Foto Kerenmu dari Acara Photobooth!';
            $mail->Body = 'Hai ' . htmlspecialchars($toName) . ',<br><br>Terima kasih sudah seru-seruan di photobooth kami! Foto kamu ada di lampiran email ini.<br><br>Sampai jumpa lagi!';
            $mail->AltBody = 'Hai ' . htmlspecialchars($toName) . ', Terima kasih! Foto kamu ada di lampiran.';

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error untuk debugging
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendSessionPhotos($toEmail, $attachments)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port = SMTP_PORT;

            // Recipients
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($toEmail, 'Photobooth User');

            // Build download links instead of attachments
            $downloadLinksHtml = '';
            $fileCount = 0;
            foreach ($attachments as $attachment) {
                if (isset($attachment['path']) && isset($attachment['name'])) {
                    $filePath = $attachment['path'];
                    $webPath = str_replace('\\', '/', $filePath);
                    
                    // [DYNAMIC FIX] Check if path is already absolute (contains http)
                    if (strpos($webPath, 'http') === 0) {
                        $downloadUrl = $webPath;
                    } else {
                        if (substr($webPath, 0, 1) !== '/') {
                            $webPath = '/' . $webPath;
                        }
                        $downloadUrl = URLROOT . $webPath;
                    }
                    
                    $fileSize = 'Unknown';

                    // Get file size if exists
                    $absolutePath = dirname(dirname(__DIR__)) . '/public' . $webPath;
                    if (file_exists($absolutePath)) {
                        $sizeBytes = filesize($absolutePath);
                        $fileSize = $this->formatFileSize($sizeBytes);
                    }

                    $fileCount++;
                    $downloadLinksHtml .= "
                        <div style='background: #f8f9fa; padding: 15px; margin: 10px 0; border-radius: 8px; border-left: 4px solid #6C63FF;'>
                            <h4 style='margin: 0 0 8px 0; color: #6C63FF;'>📁 {$attachment['name']}</h4>
                            <p style='margin: 0 0 10px 0; color: #666; font-size: 14px;'>Ukuran: {$fileSize}</p>
                            <a href='{$downloadUrl}' style='display: inline-block; background: linear-gradient(135deg, #6C63FF, #FF6584); color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;'>⬇️ Download File</a>
                        </div>
                    ";
                }
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Foto-foto Menakjubkan dari Sesi Photobooth Anda!';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; color: #333; }
                        .header { background: linear-gradient(135deg, #6C63FF, #FF6584); color: white; padding: 20px; border-radius: 10px; text-align: center; }
                        .content { padding: 20px; }
                        .footer { background: #f8f9fa; padding: 15px; border-radius: 10px; text-align: center; margin-top: 20px; }
                    </style>
                </head>
                <body>
                    <div class='header'>
                        <h1>🎉 Foto Photobooth Anda Siap!</h1>
                        <p>Terima kasih telah menggunakan photobooth kami!</p>
                    </div>
                    
                    <div class='content'>
                        <h2>Halo!</h2>
                        <p>Sesi foto yang menakjubkan telah selesai! Kami telah menyiapkan <strong>{$fileCount} file</strong> untuk Anda download:</p>
                        
                        <h3>📸 File Yang Tersedia:</h3>
                        {$downloadLinksHtml}
                        
                        <div style='background: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 5px;'>
                            <p style='margin: 0;'><strong>💡 Tips:</strong> Klik tombol &quot;Download File&quot; di atas untuk mengunduh setiap file. File akan tersimpan di perangkat Anda!</p>
                        </div>
                        
                        <p>✨ <em>Setiap foto adalah momen berharga yang layak dikenang selamanya.</em></p>
                    </div>
                    
                    <div class='footer'>
                        <p>Sampai jumpa lagi di sesi photobooth berikutnya! 📷</p>
                        <small>Email otomatis dari sistem Photobooth</small>
                    </div>
                </body>
                </html>
            ";

            $mail->AltBody = "Halo! Sesi foto Anda telah selesai. Silakan download file Anda di: " . URLROOT . "/downloads";

            $mail->send();
            error_log("✅ Email sent successfully to {$toEmail} with {$fileCount} download links");
            return true;
        } catch (Exception $e) {
            error_log("❌ Session email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Format file size to human readable format
     */
    private function formatFileSize($bytes)
    {
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}