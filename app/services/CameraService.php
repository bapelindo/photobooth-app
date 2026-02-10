<?php

namespace App\Services;

use Exception;

class CameraService
{
    public function takePhoto() {
        $pythonPath = 'python'; // Sesuaikan path python Anda
        $scriptPath = realpath(__DIR__ . '/../../scripts/capture.py');
        
        // --- PATH BARU ---
        $outputDir = realpath(__DIR__ . '/../../public/uploads/photo');

        if (!$scriptPath || !$outputDir) {
            // Buat direktori jika belum ada
            $outputDir = dirname(__DIR__, 2) . '/public/uploads/photo';
            if (!is_dir($outputDir)) {
                mkdir($outputDir, 0775, true);
                // Set Windows permissions for non-admin write access
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $outputDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $outputDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }
        }

        if (!is_writable($outputDir)) {
            throw new Exception("Direktori output tidak bisa ditulis: " . $outputDir);
        }

        $command = escapeshellcmd("$pythonPath $scriptPath $outputDir");
        $output = shell_exec("$command 2>&1"); 

        if (strpos(strtolower($output), 'error') !== false || !str_ends_with(trim($output), '.png')) {
            throw new Exception("Gagal mengambil foto. Respons kamera: " . $output);
        }

        return trim($output); 
    }
}