<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

use App\Models\Asset;
use App\Core\Database;

// Initialize Database connection (assuming it's needed for the model)
$db = new Database();

$assetModel = new Asset();

$sampleAssets = [
    ['name' => 'Frame Basic 1', 'type' => 'frame', 'file_path' => '/assets/frames/frame1.png'],
    ['name' => 'Frame Basic 2', 'type' => 'frame', 'file_path' => '/assets/frames/frame2.png'],
    ['name' => 'Sticker Hati', 'type' => 'sticker', 'file_path' => '/assets/stickers/sticker1.png'],
    ['name' => 'Sticker Bintang', 'type' => 'sticker', 'file_path' => '/assets/stickers/sticker2.png'],
    ['name' => 'Filter Sepia', 'type' => 'filter', 'file_path' => '/assets/filters/filter_sepia.png'],
    ['name' => 'Filter Hitam Putih', 'type' => 'filter', 'file_path' => '/assets/filters/filter_bw.png'],
];

foreach ($sampleAssets as $assetData) {
    if ($assetModel->create($assetData)) {
        echo "Sample asset '{$assetData['name']}' inserted successfully!\n";
    } else {
        echo "Failed to insert sample asset '{$assetData['name']}'.\n";
    }
}

?>