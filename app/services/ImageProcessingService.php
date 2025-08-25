<?php

namespace App\Services;

use Imagick;
use ImagickException;

class ImageProcessingService
{
    /**
     * Menerapkan frame dan stiker ke gambar.
     * (Metode ini tetap ada jika Anda ingin menggunakannya di tempat lain)
     */
    public function applyOverlays($baseImagePath, $framePath, $stickers, $outputPath)
    {
        try {
            $baseImage = new Imagick(realpath($baseImagePath));

            if ($framePath && file_exists(realpath($framePath))) {
                $frameImage = new Imagick(realpath($framePath));
                $frameImage->resizeImage($baseImage->getImageWidth(), $baseImage->getImageHeight(), Imagick::FILTER_LANCZOS, 1);
                $baseImage->compositeImage($frameImage, Imagick::COMPOSITE_OVER, 0, 0);
                $frameImage->clear();
            }

            if (!empty($stickers)) {
                foreach ($stickers as $stickerData) {
                    $stickerPath = realpath($stickerData['path']);
                    if ($stickerPath && file_exists($stickerPath)) {
                        $stickerImage = new Imagick($stickerPath);
                        if (isset($stickerData['width']) && isset($stickerData['height']) && $stickerData['width'] > 0 && $stickerData['height'] > 0) {
                            $stickerImage->resizeImage($stickerData['width'], $stickerData['height'], Imagick::FILTER_LANCZOS, 1);
                        }
                        $baseImage->compositeImage($stickerImage, Imagick::COMPOSITE_OVER, $stickerData['x'], $stickerData['y']);
                        $stickerImage->clear();
                    }
                }
            }

            $baseImage->setImageFormat('jpeg');
            $baseImage->setImageCompressionQuality(90);
            $baseImage->writeImage($outputPath);
            $baseImage->clear();

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (applyOverlays): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Menggabungkan beberapa foto menjadi satu photostrip dengan frame.
     *
     * @param array $photoPaths Array berisi path ke setiap foto sementara.
     * @param string|null $framePath Path ke file frame (PNG).
     * @param string $outputPath Path untuk menyimpan hasil akhir.
     * @return bool
     * @throws ImagickException
     */
    public function createPhotoStrip($photoPaths, $framePath, $outputPath)
    {
        try {
            if (empty($photoPaths)) {
                return false;
            }

            $photoCount = count($photoPaths);
            $firstImage = new Imagick(realpath($photoPaths[0]));
            $photoWidth = $firstImage->getImageWidth();
            $photoHeight = $firstImage->getImageHeight();
            $firstImage->clear();

            // Asumsi layout vertikal dengan sedikit spasi
            $spacing = 10; // Spasi antar foto dalam piksel
            $stripWidth = $photoWidth;
            $stripHeight = ($photoHeight * $photoCount) + ($spacing * ($photoCount - 1));

            $photoStrip = new Imagick();
            $photoStrip->newImage($stripWidth, $stripHeight, 'white');
            $photoStrip->setImageFormat('jpeg');

            // Tempel setiap foto ke strip
            $yOffset = 0;
            foreach ($photoPaths as $path) {
                $photo = new Imagick(realpath($path));
                // Resize setiap foto ke ukuran yang sama untuk konsistensi
                $photo->resizeImage($photoWidth, $photoHeight, Imagick::FILTER_LANCZOS, 1);
                $photoStrip->compositeImage($photo, Imagick::COMPOSITE_OVER, 0, $yOffset);
                $yOffset += $photoHeight + $spacing;
                $photo->clear();
            }

            // Terapkan frame jika ada
            if ($framePath && file_exists(realpath($framePath))) {
                $frameImage = new Imagick(realpath($framePath));
                // Sesuaikan ukuran frame dengan ukuran total strip
                $frameImage->resizeImage($stripWidth, $stripHeight, Imagick::FILTER_LANCZOS, 1);
                $photoStrip->compositeImage($frameImage, Imagick::COMPOSITE_OVER, 0, 0);
                $frameImage->clear();
            }

            $photoStrip->setImageCompressionQuality(95);
            $photoStrip->writeImage($outputPath);
            $photoStrip->clear();

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (createPhotoStrip): " . $e->getMessage());
            return false;
        }
    }
}