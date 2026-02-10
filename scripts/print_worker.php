<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once dirname(__DIR__) . '/app/Core/Database.php';
require_once dirname(__DIR__) . '/app/Models/PrintQueue.php';
require_once dirname(__DIR__) . '/app/Models/Photostrip.php';

use App\Models\PrintQueue;
use App\Models\Photostrip;

echo "Print Queue Worker Started...\n";

$printQueue = new PrintQueue();
$photostripModel = new Photostrip();

// Run indefinitely
while (true) {
    try {
        // Get pending jobs
        $jobs = $printQueue->getPendingJobs(3);
        
        if (empty($jobs)) {
            echo "[" . date('Y-m-d H:i:s') . "] No pending print jobs. Waiting...\n";
            sleep(10); // Wait 10 seconds before checking again
            continue;
        }

        echo "[" . date('Y-m-d H:i:s') . "] Processing " . count($jobs) . " print jobs...\n";

        foreach ($jobs as $job) {
            echo "[" . date('Y-m-d H:i:s') . "] Processing print job ID: {$job->id} for photostrip {$job->photostrip_id}\n";

            try {
                // Mark as processing
                $printQueue->updateStatus($job->id, 'processing');

                // Get full file path
                $basePath = dirname(dirname(__FILE__));
                $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $job->file_path);
                $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';

                // Check if files exist
                if (!file_exists($photoPath)) {
                    throw new Exception("Photostrip file not found: {$photoPath}");
                }

                if (!file_exists($scriptPath)) {
                    throw new Exception("Print script not found: {$scriptPath}");
                }

                // Execute print command for each copy
                $successCount = 0;
                $errors = [];

                for ($copy = 1; $copy <= $job->copies; $copy++) {
                    $pythonPath = 'python';
                    $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$photoPath\" " . PRINT_METHOD);
                    $output = shell_exec("$command 2>&1");

                    if (strpos(strtolower($output), 'error') !== false) {
                        $errors[] = "Copy {$copy}: {$output}";
                        echo "[" . date('Y-m-d H:i:s') . "] ⚠ Print copy {$copy} failed: {$output}\n";
                    } else {
                        $successCount++;
                        echo "[" . date('Y-m-d H:i:s') . "] ✓ Print copy {$copy} successful\n";
                    }

                    // Small delay between copies
                    if ($copy < $job->copies) {
                        sleep(2);
                    }
                }

                if ($successCount > 0) {
                    // Mark photostrip as printed in database
                    $photostripModel->markAsPrinted($job->photostrip_id);
                    
                    if ($successCount === $job->copies) {
                        $printQueue->updateStatus($job->id, 'completed');
                        echo "[" . date('Y-m-d H:i:s') . "] ✓ All {$job->copies} copies printed successfully\n";
                    } else {
                        $errorMsg = "Partial success: {$successCount}/{$job->copies} copies printed. Errors: " . implode('; ', $errors);
                        $printQueue->updateStatus($job->id, 'completed', $errorMsg);
                        echo "[" . date('Y-m-d H:i:s') . "] ⚠ Partial success: {$successCount}/{$job->copies} copies\n";
                    }
                } else {
                    throw new Exception('All print attempts failed: ' . implode('; ', $errors));
                }

            } catch (Exception $e) {
                $printQueue->incrementRetries($job->id);
                
                // If too many retries, mark as failed
                if ($job->retries >= 3) {
                    $printQueue->updateStatus($job->id, 'failed', $e->getMessage());
                    echo "[" . date('Y-m-d H:i:s') . "] ✗ Print job {$job->id} failed after {$job->retries} retries: {$e->getMessage()}\n";
                } else {
                    $printQueue->updateStatus($job->id, 'pending', $e->getMessage());
                    echo "[" . date('Y-m-d H:i:s') . "] ⚠ Print job {$job->id} failed (retry {$job->retries}/3): {$e->getMessage()}\n";
                }
            }

            // Delay between jobs to prevent printer overload
            sleep(3);
        }

        // Clean up old completed jobs once per hour
        if (date('i') === '00') {
            $deleted = $printQueue->deleteOldJobs(7);
            if ($deleted > 0) {
                echo "[" . date('Y-m-d H:i:s') . "] Cleaned up {$deleted} old print jobs\n";
            }
        }

    } catch (Exception $e) {
        echo "[" . date('Y-m-d H:i:s') . "] Worker error: {$e->getMessage()}\n";
        sleep(5); // Wait before continuing
    }
}