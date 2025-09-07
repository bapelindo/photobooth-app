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

            // Set PNG format with highest quality
            $baseImage->setImageFormat('png');
            $baseImage->setImageCompressionQuality(100); // Highest quality (0-100)
            $baseImage->setOption('png:compression-level', 0); // No compression for maximum quality
            $baseImage->writeImage($outputPath);
            $baseImage->clear();

            return true;
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (applyOverlays): " . $e->getMessage());
            return false;
        }
    }

    public function createPhotoStrip($photoPaths, $framePath, $outputPath, $slotCoordinates, $filter = 'none')
    {
        try {
            if (empty($photoPaths) || !$framePath || !file_exists($framePath) || !$slotCoordinates) {
                // If we don't have a frame or coordinates, we can't proceed with this logic.
                // You might want to add a fallback to the old method here if needed.
                error_log('ImageProcessing Error: Frame path or slot coordinates are missing.');
                return false;
            }

            $frame = new Imagick($framePath);
            $frameWidth = $frame->getImageWidth();
            $frameHeight = $frame->getImageHeight();
            
            // Ensure 2:6 aspect ratio for photobooth standard (1:3 ratio)
            $targetRatio = 1/3; // width:height = 2:6 = 1:3
            $currentRatio = $frameWidth / $frameHeight;
            
            if (abs($currentRatio - $targetRatio) > 0.01) {
                // Resize frame to maintain proper aspect ratio
                $standardWidth = 400; // Standard width for 2-inch photostrip
                $standardHeight = $standardWidth * 3; // 6-inch height (2:6 ratio)
                
                $frame->resizeImage($standardWidth, $standardHeight, Imagick::FILTER_LANCZOS, 1);
                $frameWidth = $frame->getImageWidth();
                $frameHeight = $frame->getImageHeight();
            }

            foreach ($photoPaths as $index => $photoPath) {
                if (!isset($slotCoordinates[$index])) {
                    continue; // Skip if there is no coordinate for this photo
                }

                $coords = $slotCoordinates[$index];
                $photo = new Imagick($photoPath);

                // Apply filter if specified
                if ($filter !== 'none') {
                    $this->applyFilter($photo, $filter);
                }

                // Calculate pixel values from percentages
                $targetWidth = (int)($frameWidth * ($coords['width'] / 100));
                $targetHeight = (int)($frameHeight * ($coords['height'] / 100));
                $targetX = (int)($frameWidth * ($coords['left'] / 100));
                $targetY = (int)($frameHeight * ($coords['top'] / 100));

                // Crop and resize the photo to perfectly fit the slot dimensions
                $photo->cropThumbnailImage($targetWidth, $targetHeight);

                // Composite the photo onto the frame at the specified coordinates
                $frame->compositeImage($photo, Imagick::COMPOSITE_OVER, $targetX, $targetY);
                
                $photo->clear();
            }

            // Set PNG format with highest quality
            $frame->setImageFormat('png');
            $frame->setImageCompressionQuality(100); // Highest quality (0-100)
            $frame->setOption('png:compression-level', 0); // No compression for maximum quality
            $frame->writeImage($outputPath);
            $frame->clear();

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

            return $relativePath;
            
        } catch (ImagickException $e) {
            error_log("ImageMagick Error (saveBase64Image): " . $e->getMessage());
            // Fallback to direct file writing if Imagick fails
            if (file_put_contents($filePath, $imageData) === false) {
                throw new \Exception("Could not save image to " . $filePath);
            }
            return $relativePath;
        }
    }
}
