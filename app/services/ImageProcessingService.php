<?php

namespace App\Services;

use Imagick;
use ImagickException;
use ImagickPixel;

class ImageProcessingService
{
    // ... applyOverlays method remains the same ...
    public function applyOverlays($baseImagePath, $framePath, $stickers, $outputPath)
    {
        try {
            if (!file_exists($baseImagePath) || !is_readable($baseImagePath)) {
                error_log("ImageMagick Error (applyOverlays): Base image not found or not readable at " . $baseImagePath);
                return false;
            }
            $baseImage = new Imagick($baseImagePath);

            if ($framePath && file_exists($framePath) && is_readable($framePath)) {
                $frameImage = new Imagick($framePath);
                $frameImage->resizeImage($baseImage->getImageWidth(), $baseImage->getImageHeight(), Imagick::FILTER_LANCZOS, 1);
                $baseImage->compositeImage($frameImage, Imagick::COMPOSITE_OVER, 0, 0);
                $frameImage->clear();
            }

            if (!empty($stickers)) {
                error_log("══════════════════ ImageProcessingService ═════════════════");
                foreach ($stickers as $idx => $stickerData) {
                    $stickerPath = $stickerData['path'];
                    if ($stickerPath && file_exists($stickerPath) && is_readable($stickerPath)) {
                        $stickerImage = new Imagick($stickerPath);
                        $originalWidth = $stickerImage->getImageWidth();
                        $originalHeight = $stickerImage->getImageHeight();

                        error_log("Sticker #{$idx} BEFORE: file={$originalWidth}x{$originalHeight}, target={$stickerData['width']}x{$stickerData['height']}, pos={$stickerData['x']},{$stickerData['y']}");

                        if (isset($stickerData['width']) && isset($stickerData['height']) && $stickerData['width'] > 0 && $stickerData['height'] > 0) {
                            // Resize ke ukuran persis dari data (koordinat sudah 600x1800)
                            $stickerImage->resizeImage(
                                $stickerData['width'],
                                $stickerData['height'],
                                Imagick::FILTER_LANCZOS,
                                1
                            );
                            $afterWidth = $stickerImage->getImageWidth();
                            $afterHeight = $stickerImage->getImageHeight();
                            error_log("Sticker #{$idx} AFTER: resized to {$afterWidth}x{$afterHeight}");
                        }
                        // Apply offset yang sudah disesuaikan oleh user
                        $finalX = $stickerData['x'] + 11;
                        $finalY = $stickerData['y'] + 9;
                        $baseImage->compositeImage($stickerImage, Imagick::COMPOSITE_OVER, $finalX, $finalY);
                        error_log("Sticker #{$idx} COMPOSITED at x={$finalX}, y={$finalY} (original: x={$stickerData['x']}, y={$stickerData['y']}, offset: +11, +9)");
                        $stickerImage->clear();
                    }
                }
                error_log("═══════════════════════════════════════════════════════════");
            }

            // Set PNG format with highest quality
            $baseImage->setImageFormat('png');
            $baseImage->setImageCompressionQuality(100); // Highest quality (0-100)
            $baseImage->setOption('png:compression-level', 0); // No compression for maximum quality
            $baseImage->writeImage($outputPath);
            $baseImage->clear();

            // Set Windows permissions for the saved file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $outputPath . '" /grant Users:F');
                exec('icacls "' . $outputPath . '" /grant IUSR:F');
                exec('icacls "' . $outputPath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($outputPath, 0644);
            }

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (applyOverlays): " . $e->getMessage());
            return false;
        }
    }

    public function createPhotoStrip($photosData, $framePath, $outputPath, $slotCoordinates, $filter = 'none')
    {
        try {
            if (empty($photosData) || !$framePath || !file_exists($framePath) || !$slotCoordinates) {
                error_log('ImageProcessing Error: Frame path or slot coordinates are missing.');
                return false;
            }

            // Load and resize the frame
            $frameOriginal = new Imagick($framePath);
            $frameOriginal->resizeImage(600, 1800, Imagick::FILTER_LANCZOS, 1);
            $frameWidth = $frameOriginal->getImageWidth();
            $frameHeight = $frameOriginal->getImageHeight();

            $targetRatio = 1 / 3;
            $currentRatio = $frameWidth / $frameHeight;

            if (abs($currentRatio - $targetRatio) > 0.01) {
                $standardWidth = 400;
                $standardHeight = $standardWidth * 3;
                $frameOriginal->resizeImage($standardWidth, $standardHeight, Imagick::FILTER_LANCZOS, 1);
                $frameWidth = $frameOriginal->getImageWidth();
                $frameHeight = $frameOriginal->getImageHeight();
            }

            // Create a new canvas with transparent background
            $canvas = new Imagick();
            $canvas->newImage($frameWidth, $frameHeight, new ImagickPixel('transparent'));
            $canvas->setImageFormat('png');

            // Composite the frame at the bottom (background layer)
            $canvas->compositeImage($frameOriginal, Imagick::COMPOSITE_OVER, 0, 0);

            // Composite each photo onto the canvas (underneath where the frame will be placed again)
            foreach ($photosData as $index => $photoData) {
                if (!isset($slotCoordinates[$index])) {
                    continue;
                }

                $coords = $slotCoordinates[$index];
                $photo = new Imagick($photoData['path']);

                if ($filter !== 'none') {
                    $this->applyFilter($photo, $filter);
                }

                $targetWidth = (int) ($frameWidth * ($coords['width'] / 100));
                $targetHeight = (int) ($frameHeight * ($coords['height'] / 100));
                $targetX = (int) ($frameWidth * ($coords['left'] / 100));
                $targetY = (int) ($frameHeight * ($coords['top'] / 100));

                $photoWidth = $photo->getImageWidth();
                $photoHeight = $photo->getImageHeight();
                $photoRatio = $photoWidth / $photoHeight;
                $targetSlotRatio = $targetWidth / $targetHeight;

                if ($photoRatio > $targetSlotRatio) {
                    $newHeight = $targetHeight;
                    $newWidth = (int) ($newHeight * $photoRatio);
                    $photo->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);
                } else {
                    $newWidth = $targetWidth;
                    $newHeight = (int) ($newWidth / $photoRatio);
                    $photo->resizeImage($newWidth, $newHeight, Imagick::FILTER_LANCZOS, 1);
                }

                $panX = $photoData['panX'] ?? 0.5;
                $panY = $photoData['panY'] ?? 0.5;

                $cropX = (int) (($photo->getImageWidth() - $targetWidth) * (1 - $panX));
                $cropY = (int) (($photo->getImageHeight() - $targetHeight) * (1 - $panY));

                $photo->cropImage($targetWidth, $targetHeight, $cropX, $cropY);

                // Composite photo onto canvas (will be under the top frame layer)
                $canvas->compositeImage($photo, Imagick::COMPOSITE_OVER, $targetX, $targetY);

                $photo->clear();
            }

            // Composite the frame AGAIN on top (this ensures frame stickers/decorations are on top of photos)
            $canvas->compositeImage($frameOriginal, Imagick::COMPOSITE_OVER, 0, 0);

            $canvas->setImageFormat('png');
            $canvas->setImageCompressionQuality(100);
            $canvas->setOption('png:compression-level', 0);
            $canvas->writeImage($outputPath);
            $canvas->clear();
            $frameOriginal->clear();

            // Set Windows permissions for the saved file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $outputPath . '" /grant Users:F');
                exec('icacls "' . $outputPath . '" /grant IUSR:F');
                exec('icacls "' . $outputPath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($outputPath, 0644);
            }

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (createPhotoStrip): " . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper method to apply an effect based on a CSS-like filter string.
     * @param Imagick $image The Imagick image object.
     * @param string $filterCss The CSS filter string (e.g., "sepia(100%)").
     */
    private function applyFilter(Imagick $image, $filterCss)
    {
        // This is a simple mapping and can be expanded.
        if (strpos($filterCss, 'grayscale') !== false) {
            $image->modulateImage(100, 0, 100); // Brightness, Saturation, Hue
        } elseif (strpos($filterCss, 'sepia') !== false) {
            $image->sepiaToneImage(80); // Threshold is a percentage
        } elseif (strpos($filterCss, 'invert') !== false) {
            $image->negateImage(false);
        }
        // You can add more mappings here for contrast, brightness, etc.
        // Example for contrast: $image->contrastImage(true);
    }

    public function saveBase64Image($base64Image, $filename, $directory)
    {
        try {
            $imageData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));

            $uploadDir = dirname(APPROOT) . '/public/' . $directory;
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
                // Set Windows permissions for non-admin write access
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $uploadDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }

            $filename = uniqid($filename . '_') . '.png';
            $filePath = $uploadDir . '/' . $filename;
            $relativePath = '/' . $directory . '/' . $filename;

            // Use Imagick to ensure highest quality PNG
            $image = new Imagick();
            $image->readImageBlob($imageData);
            $image->setImageFormat('png');
            $image->setImageCompressionQuality(100); // Highest quality
            $image->setOption('png:compression-level', 0); // No compression
            $image->writeImage($filePath);
            $image->clear();

            // Set Windows permissions for the saved file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $filePath . '" /grant Users:F');
                exec('icacls "' . $filePath . '" /grant IUSR:F');
                exec('icacls "' . $filePath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($filePath, 0644);
            }

            return $relativePath;

        } catch (ImagickException $e) {
            error_log("ImageMagick Error (saveBase64Image): " . $e->getMessage());
            // Fallback to direct file writing if Imagick fails
            if (file_put_contents($filePath, $imageData) === false) {
                throw new \Exception("Could not save image to " . $filePath);
            }
            // Set Windows permissions for the saved file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $filePath . '" /grant Users:F');
                exec('icacls "' . $filePath . '" /grant IUSR:F');
                exec('icacls "' . $filePath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($filePath, 0644);
            }
            return $relativePath;
        }
    }

    /**
     * Compress an image to reduce file size for email attachments
     * @param string $sourcePath Path to source image
     * @param string $outputPath Path to save compressed image
     * @param int $quality JPEG quality (1-100, default 85)
     * @return int|false Size of compressed file in bytes, or false on failure
     */
    public function compressImage($sourcePath, $outputPath, $quality = 85)
    {
        try {
            if (!file_exists($sourcePath) || !is_readable($sourcePath)) {
                error_log("ImageProcessingService::compressImage - Source file not found: $sourcePath");
                return false;
            }

            $imagick = new Imagick($sourcePath);

            // Convert to JPEG format for better compression
            $imagick->setImageFormat('jpeg');

            // Set compression quality (85 is good balance between size and quality)
            $imagick->setImageCompressionQuality($quality);

            // Strip metadata to reduce size
            $imagick->stripImage();

            // Write compressed image
            $imagick->writeImage($outputPath);
            $fileSize = filesize($outputPath);

            // Set Windows permissions for the saved file
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $outputPath . '" /grant Users:F');
                exec('icacls "' . $outputPath . '" /grant IUSR:F');
                exec('icacls "' . $outputPath . '" /grant IIS_IUSRS:F');
            } else {
                @chmod($outputPath, 0644);
            }

            $imagick->clear();
            $imagick->destroy();

            $originalSize = filesize($sourcePath);
            $savedPercent = round((1 - $fileSize / $originalSize) * 100, 1);
            error_log("ImageProcessingService::compressImage - Compressed $sourcePath: " .
                round($originalSize / 1024 / 1024, 2) . "MB -> " .
                round($fileSize / 1024 / 1024, 2) . "MB (saved {$savedPercent}%)");

            return $fileSize;

        } catch (ImagickException $e) {
            error_log("ImageProcessingService::compressImage - Error: " . $e->getMessage());
            return false;
        }
    }
}
