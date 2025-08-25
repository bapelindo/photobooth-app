<?php

namespace App\Services;
use Exception;
class CameraService
{
    public function takePhoto() {
        $pythonPath = 'python'; // Sesuaikan path python Anda
        $scriptPath = realpath(__DIR__ . '/../../scripts/capture.py');
        $outputDir = realpath(__DIR__ . '/../../public/photos');

        if (!$scriptPath || !$outputDir) {
            throw new \Exception("Script or output directory not found.");
        }

        // Pastikan direktori dapat ditulis oleh web server
        if (!is_writable($outputDir)) {
            throw new \Exception("Output directory is not writable.");
        }

        $command = escapeshellcmd("$pythonPath $scriptPath $outputDir");
        $output = shell_exec("$command 2>&1"); // Tangkap stdout dan stderr

        // Cek jika output berisi error atau bukan nama file yang valid
        if (strpos(strtolower($output), 'error') !== false || !str_ends_with(trim($output), '.jpg')) {
            throw new \Exception("Failed to capture photo. Camera response: " . $output);
        }

        return trim($output); // Mengembalikan nama file yang berhasil ditangkap
    }
}