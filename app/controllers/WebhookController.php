<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PrintQueue;
use App\Models\Photostrip;
use App\Services\EmailQueueService;
use Exception;

class WebhookController extends Controller
{
    // Webhook for processing emails
    public function processEmail()
    {
        header('Content-Type: application/json');

        // Optional security: Check a token or secret if needed
        // $secret = $_GET['token'] ?? '';
        // if ($secret !== 'YOUR_SECRET_TOKEN') {
        //    http_response_code(403);
        //    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        //    return;
        // }

        try {
            $emailQueueService = new EmailQueueService();
            // Process up to 10 emails per request to avoid timeout on Cloud Run
            $processed = $emailQueueService->processPendingEmails(10);

            echo json_encode([
                'success' => true,
                'message' => "Successfully processed {$processed} emails.",
                'processed' => $processed
            ]);
        } catch (Exception $e) {
            error_log('Webhook Email Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error processing emails: ' . $e->getMessage()
            ]);
        }
    }

    // Webhook for processing prints
    public function processPrint()
    {
        header('Content-Type: application/json');

        try {
            $printQueue = new PrintQueue();
            $photostripModel = new Photostrip();

            // Process up to 5 print jobs per request
            $jobs = $printQueue->getPendingJobs(5);
            $processedCount = 0;
            $errors = [];

            if (!empty($jobs)) {
                foreach ($jobs as $job) {
                    try {
                        // Mark as processing
                        $printQueue->updateStatus($job->id, 'processing');

                        $basePath = dirname(APPROOT); // APPROOT is /app, so dirname is the project root
                        $photoPath = $basePath . DIRECTORY_SEPARATOR . 'public' . str_replace('/', DIRECTORY_SEPARATOR, $job->file_path);
                        $scriptPath = $basePath . DIRECTORY_SEPARATOR . 'scripts' . DIRECTORY_SEPARATOR . 'print_photostrip.py';

                        if (!file_exists($photoPath)) {
                            throw new Exception("Photostrip file not found: {$photoPath}");
                        }

                        if (!file_exists($scriptPath)) {
                            throw new Exception("Print script not found: {$scriptPath}");
                        }

                        $successCount = 0;
                        $jobErrors = [];

                        $printMethod = defined('PRINT_METHOD') ? PRINT_METHOD : 'gdi';

                        for ($copy = 1; $copy <= $job->copies; $copy++) {
                            $pythonPath = 'python';
                            $command = escapeshellcmd("$pythonPath \"$scriptPath\" \"$photoPath\" " . $printMethod);
                            $output = shell_exec("$command 2>&1");

                            if (strpos(strtolower((string)$output), 'error') !== false) {
                                $jobErrors[] = "Copy {$copy}: {$output}";
                                error_log("Webhook Print Error (Copy {$copy}): {$output}");
                            } else {
                                $successCount++;
                            }
                        }

                        if ($successCount > 0) {
                            $photostripModel->markAsPrinted($job->photostrip_id);
                            
                            if ($successCount === (int)$job->copies) {
                                $printQueue->updateStatus($job->id, 'completed');
                            } else {
                                $errorMsg = "Partial success: {$successCount}/{$job->copies} copies. Errors: " . implode('; ', $jobErrors);
                                $printQueue->updateStatus($job->id, 'completed', $errorMsg);
                                $errors[] = "Job {$job->id}: {$errorMsg}";
                            }
                            $processedCount++;
                        } else {
                            throw new Exception('All print attempts failed: ' . implode('; ', $jobErrors));
                        }

                    } catch (Exception $e) {
                        $printQueue->incrementRetries($job->id);
                        $job = $printQueue->find($job->id); // Reload to get updated retries
                        
                        if ($job && $job->retries >= 3) {
                            $printQueue->updateStatus($job->id, 'failed', $e->getMessage());
                        } else {
                            $printQueue->updateStatus($job->id, 'pending', $e->getMessage());
                        }
                        $errors[] = "Job {$job->id} failed: " . $e->getMessage();
                    }
                }
            }

            // Clean up old completed jobs occasionally (e.g. 10% chance per request)
            if (rand(1, 10) === 1) {
                $printQueue->deleteOldJobs(7);
            }

            echo json_encode([
                'success' => true,
                'message' => "Processed {$processedCount} print jobs.",
                'jobs_found' => count($jobs),
                'processed' => $processedCount,
                'errors' => $errors
            ]);
        } catch (Exception $e) {
            error_log('Webhook Print Error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error processing prints: ' . $e->getMessage()
            ]);
        }
    }
}
