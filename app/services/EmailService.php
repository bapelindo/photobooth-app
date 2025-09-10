<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService
{
    public function sendPhoto($toEmail, $toName, $photoPath, $photoFilename) {
        $mail = new PHPMailer(true);

        try {
            // Pengaturan Server dari config.php
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;

            // Penerima
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // Lampiran
            $mail->addAttachment($photoPath, $photoFilename);

            // Konten
            $mail->isHTML(true);
            $mail->Subject = 'Ini Dia Foto Kerenmu dari Acara Photobooth!';
            $mail->Body    = 'Hai ' . htmlspecialchars($toName) . ',<br><br>Terima kasih sudah seru-seruan di photobooth kami! Foto kamu ada di lampiran email ini.<br><br>Sampai jumpa lagi!';
            $mail->AltBody = 'Hai ' . htmlspecialchars($toName) . ', Terima kasih! Foto kamu ada di lampiran.';

            $mail->send();
            return true;
        } catch (Exception $e) {
            // Log error untuk debugging
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }

    public function sendSessionPhotos($toEmail, $attachments) {
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = SMTP_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = SMTP_USERNAME;
            $mail->Password   = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_SECURE;
            $mail->Port       = SMTP_PORT;

            // Recipients
            $mail->setFrom(EMAIL_FROM_ADDRESS, EMAIL_FROM_NAME);
            $mail->addAddress($toEmail, 'Photobooth User');

            // Attachments
            foreach ($attachments as $attachment) {
                if (isset($attachment['path']) && isset($attachment['name'])) {
                    $filePath = $attachment['path'];
                    
                    // Handle Windows path issues - convert relative to absolute
                    if (!file_exists($filePath)) {
                        $basePath = dirname(dirname(__DIR__));
                        $filePath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $attachment['path']);
                    }
                    
                    if (file_exists($filePath)) {
                        $mail->addAttachment($filePath, $attachment['name']);
                    } else {
                        error_log("Email attachment not found: " . $attachment['path'] . " (tried: " . $filePath . ")");
                        // Continue with other attachments even if one is missing
                    }
                }
            }

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Foto-foto Menakjubkan dari Sesi Photobooth Anda!';
            $mail->Body    = '
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
                    <div class="header">
                        <h1>🎉 Foto Photobooth Anda Siap!</h1>
                        <p>Terima kasih telah menggunakan photobooth kami!</p>
                    </div>
                    
                    <div class="content">
                        <h2>Halo!</h2>
                        <p>Sesi foto yang menakjubkan telah selesai! Berikut adalah semua foto dan photostrip yang telah Anda buat:</p>
                        
                        <h3>📸 Yang Anda Dapatkan:</h3>
                        <ul>
                            <li><strong>Photostrip Final:</strong> Semua photostrip dengan frame dan dekorasi yang Anda pilih</li>
                            <li><strong>File ZIP:</strong> Berisi semua foto asli yang Anda simpan selama sesi</li>
                        </ul>
                        
                        <p>Semua file sudah dilampirkan dalam email ini. Unduh dan simpan untuk kenangan yang tak terlupakan!</p>
                        
                        <p>✨ <em>Setiap foto adalah momen berharga yang layak dikenang selamanya.</em></p>
                    </div>
                    
                    <div class="footer">
                        <p>Sampai jumpa lagi di sesi photobooth berikutnya! 📷</p>
                        <small>Email otomatis dari sistem Photobooth</small>
                    </div>
                </body>
                </html>
            ';
            
            $mail->AltBody = 'Halo! Sesi foto Anda telah selesai. File foto dan photostrip terlampir dalam email ini. Terima kasih telah menggunakan photobooth kami!';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Session email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}