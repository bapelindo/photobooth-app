<?php
namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception as PHPMailerException;

class EmailService
{
    /**
     * Mengirim email berisi semua foto mentah dan photostrip final.
     * @param string $toEmail
     * @param string $toName
     * @param array $rawPhotos Array of photo objects
     * @param array $finalPhotos Array of photo objects
     * @return bool
     */
    public function sendAllPhotos($toEmail, $toName, $rawPhotos, $finalPhotos) {
        $mail = new PHPMailer(true);
        $basePath = dirname(APPROOT) . '/public';

        try {
            // Pengaturan Server
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

            // Lampiran Photostrip Final
            foreach ($finalPhotos as $photo) {
                $filePath = $basePath . $photo->file_path;
                if (file_exists($filePath)) {
                    $mail->addAttachment($filePath);
                }
            }
            
            // Lampiran Foto Mentah
            foreach ($rawPhotos as $photo) {
                $filePath = $basePath . $photo->file_path;
                if (file_exists($filePath)) {
                    $mail->addAttachment($filePath);
                }
            }

            // Konten
            $mail->isHTML(true);
            $mail->Subject = 'Ini Dia Semua Foto Kerenmu dari Sesi Photobooth!';
            $mail->Body    = 'Hai ' . htmlspecialchars($toName) . ',<br><br>Terima kasih sudah seru-seruan di photobooth kami! Semua foto dari sesimu (termasuk yang mentah dan photostrip jadi) ada di lampiran email ini.<br><br>Jangan lupa bagikan momen serumu dan sampai jumpa lagi!';
            $mail->AltBody = 'Hai ' . htmlspecialchars($toName) . ', Terima kasih! Semua fotomu ada di lampiran.';

            $mail->send();
            return true;
        } catch (PHPMailerException $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        } catch (\Exception $e) {
            error_log("An error occurred in EmailService: " . $e->getMessage());
            return false;
        }
    }
}