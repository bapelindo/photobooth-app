<?php

namespace App\Services;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService

    public function sendPhoto($toEmail, $toName, $photoPath, $photoFilename) {
        $mail = new PHPMailer(true);

        try {
            // Pengaturan Server
            $mail->isSMTP();
            $mail->Host       = 'smtp.example.com'; // Ganti dengan host SMTP Anda
            $mail->SMTPAuth   = true;
            $mail->Username   = 'user@example.com'; // Ganti dengan username SMTP
            $mail->Password   = 'secret'; // Ganti dengan password SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Penerima
            $mail->setFrom('no-reply@yourphotobooth.com', 'Photobooth Keren');
            $mail->addAddress($toEmail, $toName);

            // Lampiran
            $mail->addAttachment($photoPath, $photoFilename);

            // Konten
            $mail->isHTML(true);
            $mail->Subject = 'Ini Dia Foto Kerenmu!';
            $mail->Body    = 'Hai ' . htmlspecialchars($toName) . ',<br><br>Terima kasih sudah seru-seruan di photobooth kami! Foto kamu ada di lampiran email ini.<br><br>Sampai jumpa lagi!';
            $mail->AltBody = 'Hai ' . htmlspecialchars($toName) . ', Terima kasih! Foto kamu ada di lampiran.';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
            return false;
        }
    }
}