<?php
/**
 * Background Email Worker
 * Run this script continuously to process email queue
 * Usage: php email_worker.php
 */

// Bootstrap the application
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

// Load all necessary classes
use App\Services\EmailQueueService;
use App\Core\Database;

// Set time limit to unlimited for continuous processing
set_time_limit(0);

// Create email queue service
$emailQueueService = new EmailQueueService();

echo "Starting email worker...\n";
echo "Process ID: " . getmypid() . "\n";
echo "Started at: " . date('Y-m-d H:i:s') . "\n\n";

// Main processing loop
while (true) {
    try {
        // Process up to 5 emails per batch
        $processed = $emailQueueService->processPendingEmails(5);
        
        if ($processed > 0) {
            echo "[" . date('H:i:s') . "] Processed $processed emails\n";
        }
        
        // Wait for 10 seconds before next batch
        sleep(10);
        
        // Optional: Add memory check to prevent memory leaks
        if (memory_get_usage() > 50 * 1024 * 1024) { // 50MB
            echo "[" . date('H:i:s') . "] Memory usage high, restarting worker\n";
            break;
        }
        
    } catch (Exception $e) {
        echo "[" . date('H:i:s') . "] Worker error: " . $e->getMessage() . "\n";
        sleep(30); // Wait longer on error
    }
}

echo "Email worker stopped at: " . date('Y-m-d H:i:s') . "\n";
?>