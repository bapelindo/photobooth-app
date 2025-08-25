<?php

namespace App\Services;

class ImageProcessingService
{
    /**
     * Menerapkan frame dan stiker ke gambar.
     *
     * @param string $baseImagePath Path ke foto asli.
     * @param string|null $framePath Path ke file frame (PNG).
     * @param array $stickers Array berisi [ 'path' => 'path/to/sticker.png', 'x' => 100, 'y' => 150, 'width' => 50, 'height' => 50 ]
     * @param string $outputPath Path untuk menyimpan hasil akhir.
     * @return bool
     * @throws ImagickException
     */
    public function applyOverlays($baseImagePath, $framePath, $stickers, $outputPath)
    {
        try {
            $baseImage = new Imagick(realpath($baseImagePath));

            // Terapkan frame jika ada
            if ($framePath && file_exists(realpath($framePath))) {
                $frameImage = new Imagick(realpath($framePath));
                // Sesuaikan ukuran frame dengan gambar dasar
                $frameImage->resizeImage($baseImage->getImageWidth(), $baseImage->getImageHeight(), Imagick::FILTER_LANCZOS, 1);
                // Tumpuk frame di atas gambar dasar
                $baseImage->compositeImage($frameImage, Imagick::COMPOSITE_OVER, 0, 0);
                $frameImage->clear();
            }

            // Terapkan setiap stiker
            if (!empty($stickers)) {
                foreach ($stickers as $stickerData) {
                    $stickerPath = realpath($stickerData['path']);
                    if ($stickerPath && file_exists($stickerPath)) {
                        $stickerImage = new Imagick($stickerPath);
                        // Sesuaikan ukuran stiker
                        if (isset($stickerData['width']) && isset($stickerData['height']) && $stickerData['width'] > 0 && $stickerData['height'] > 0) {
                            $stickerImage->resizeImage($stickerData['width'], $stickerData['height'], Imagick::FILTER_LANCZOS, 1);
                        }
                        // Tumpuk stiker di atas gambar pada koordinat yang ditentukan
                        $baseImage->compositeImage($stickerImage, Imagick::COMPOSITE_OVER, $stickerData['x'], $stickerData['y']);
                        $stickerImage->clear();
                    }
                }
            }

            // Atur format output ke JPEG untuk ukuran file yang lebih kecil
            $baseImage->setImageFormat('jpeg');
            $baseImage->setImageCompressionQuality(90);

            $baseImage->writeImage($outputPath);
            $baseImage->clear();

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error: " . $e->getMessage());
            return false;
        }
    }
}