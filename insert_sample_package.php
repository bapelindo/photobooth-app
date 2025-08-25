<?php
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use App\Models\Package;
use App\Core\Database; // Ensure Database class is accessible

// Initialize Database connection (assuming it's needed for the model)
$db = new Database();

$packageModel = new Package();

$data = [
    'name' => 'Paket Uji Coba',
    'description' => 'Ini adalah paket uji coba untuk memastikan tombol muncul.',
    'price' => 50000.00,
    'photo_limit' => 10,
    'retake_limit' => 3
];

if ($packageModel->create($data)) {
    echo "Sample package inserted successfully!\n";
} else {
    echo "Failed to insert sample package.\n";
}
?>
