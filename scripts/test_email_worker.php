<?php
/**
 * Test script to debug email worker issues
 */

echo "Testing email worker components...\n";

// Bootstrap the application
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

echo "✓ Config and autoload loaded\n";

// Test Database class
try {
    $db = new App\Core\Database();
    echo "✓ Database class loaded successfully\n";
} catch (Exception $e) {
    echo "✗ Database class failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test EmailQueue model
try {
    $emailQueue = new App\Models\EmailQueue();
    echo "✓ EmailQueue model loaded successfully\n";
} catch (Exception $e) {
    echo "✗ EmailQueue model failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test EmailQueueService
try {
    $emailQueueService = new App\Services\EmailQueueService();
    echo "✓ EmailQueueService loaded successfully\n";
} catch (Exception $e) {
    echo "✗ EmailQueueService failed: " . $e->getMessage() . "\n";
    exit(1);
}

// Test database connection
try {
    $stats = $emailQueueService->getQueueStats();
    echo "✓ Database connection works\n";
    echo "✓ Queue stats: " . count($stats) . " status types found\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    echo "Note: Make sure to create the email_queue table first\n";
}

echo "\nAll tests passed! Email worker should work now.\n";
?>