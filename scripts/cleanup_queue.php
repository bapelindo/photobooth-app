<?php
/**
 * Cleanup script to remove queue entries with missing files
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/vendor/autoload.php';

use App\Models\EmailQueue;
use App\Models\PrintQueue;

echo "Starting queue cleanup...\n";

// Clean email queue
$emailQueue = new EmailQueue();
$jobs = $emailQueue->getPending(100);
$cleaned = 0;

echo "Checking " . count($jobs) . " email queue entries...\n";

foreach ($jobs as $job) {
    $attachments = json_decode($job->attachments ?: '[]', true);
    $hasValidFile = false;
    
    foreach ($attachments as $attachment) {
        if (isset($attachment['path'])) {
            $filePath = $attachment['path'];
            
            // Try relative path first
            if (!file_exists($filePath)) {
                $basePath = dirname(__DIR__);
                $filePath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $attachment['path']);
            }
            
            if (file_exists($filePath)) {
                $hasValidFile = true;
                break;
            }
        }
    }
    
    if (!$hasValidFile && !empty($attachments)) {
        echo "Marking failed: Job #{$job->id} - no valid files found\n";
        $emailQueue->markFailed($job->id, 'Files not found during cleanup');
        $cleaned++;
    }
}

// Clean print queue
$printQueue = new PrintQueue();
$printJobs = $printQueue->getPendingJobs(100);
$printCleaned = 0;

echo "Checking " . count($printJobs) . " print queue entries...\n";

foreach ($printJobs as $job) {
    if ($job->status !== 'pending') continue;
    
    $filePath = $job->file_path;
    
    if (!file_exists($filePath)) {
        $basePath = dirname(__DIR__);
        $filePath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $job->file_path);
    }
    
    if (!file_exists($filePath)) {
        echo "Marking failed: Print Job #{$job->id} - file not found: {$job->file_path}\n";
        $printQueue->updateStatus($job->id, 'failed', 'File not found during cleanup');
        $printCleaned++;
    }
}

echo "\nCleanup completed!\n";
echo "Email jobs cleaned: $cleaned\n";
echo "Print jobs cleaned: $printCleaned\n";