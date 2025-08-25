<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use App\Models\Package;
use App\Core\Database;

// Initialize Database connection
$db = new Database();

$packageModel = new Package();

$data = [
    'name' => 'Paket Ceria Photostrip',
    'description' => 'Abadikan momen seru dengan 4 gaya berbeda dalam satu strip!',
    'price' => 50000.00,
    'photo_limit' => 1, // Jumlah sesi photostrip
    'photo_slots' => 4, // Jumlah foto dalam satu strip
    'retake_limit' => 3
];

if ($packageModel->create($data)) {
    echo "Sample package inserted successfully!\n";
} else {
    echo "Failed to insert sample package.\n";
}
?>