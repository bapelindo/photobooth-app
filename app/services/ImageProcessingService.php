<?php

namespace App\Services;

use Imagick;
use ImagickException;

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
                foreach ($stickers as $stickerData) {
                    $stickerPath = $stickerData['path'];
                    if ($stickerPath && file_exists($stickerPath) && is_readable($stickerPath)) {
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

    public function createPhotoStrip($photoPaths, $framePath, $outputPath, $filter = 'none')
    {
        try {
            if (empty($photoPaths)) {
                return false;
            }

            $firstImage = new Imagick($photoPaths[0]);
            $photoWidth = $firstImage->getImageWidth();
            $photoHeight = $firstImage->getImageHeight();
            $firstImage->clear();
            
            $photoCount = count($photoPaths);
            $spacing = 10;

            $photoStrip = new Imagick();

            if ($framePath && file_exists($framePath)) {
                $photoStrip->readImage($framePath);
                $stripWidth = $photoStrip->getImageWidth();
                $stripHeight = $photoStrip->getImageHeight();
            } else {
                $stripWidth = $photoWidth;
                $stripHeight = ($photoHeight * $photoCount) + ($spacing * ($photoCount - 1));
                $photoStrip->newImage($stripWidth, $stripHeight, 'white', 'jpg');
            }
            
            $paddingX = (int) ($stripWidth * 0.05);
            $paddingY = (int) ($stripHeight * 0.05);
            $contentWidth = (int) ($stripWidth - (2 * $paddingX));
            $contentHeight = (int) ($stripHeight - (2 * $paddingY));
            
            $totalSpacing = (int) ($spacing * ($photoCount - 1));
            $slotHeight = (int) (($contentHeight - $totalSpacing) / $photoCount);
            $slotWidth = (int) $contentWidth;

            $yOffset = $paddingY;
            foreach ($photoPaths as $path) {
                $photo = new Imagick($path);
                
                // Menerapkan filter jika ada
                if ($filter !== 'none') {
                    $this->applyFilter($photo, $filter);
                }
                
                $photo->resizeImage($slotWidth, $slotHeight, Imagick::FILTER_LANCZOS, 1);
                $photoStrip->compositeImage($photo, Imagick::COMPOSITE_OVER, $paddingX, (int) $yOffset);
                $yOffset += $slotHeight + $spacing;
                $photo->clear();
            }

            $photoStrip->setImageFormat('jpeg');
            $photoStrip->setImageCompressionQuality(95);
            $photoStrip->writeImage($outputPath);
            $photoStrip->clear();

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
}