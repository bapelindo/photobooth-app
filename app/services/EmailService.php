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
}