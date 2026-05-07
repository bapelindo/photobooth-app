<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use Exception;
use Google\Auth\ApplicationDefaultCredentials;

class AiController extends Controller
{
    public function enhance($session_id)
    {
        Session::start();

        // Skip AI page if disabled in config
        if (!AI_ENHANCE_ENABLED) {
            header('Location: ' . URLROOT . '/photo/layout/' . $session_id);
            exit();
        }

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);

        if (!$session || $session->session_status !== 'completed') {
            $this->flashAndRedirect('packages', 'Sesi tidak valid atau belum selesai.');
        }

        $photos = $photoSessionModel->getSavedPhotos($session_id);

        if (empty($photos)) {
            header('Location: ' . URLROOT . '/photo/layout/' . $session_id);
            exit();
        }

        $data = [
            'session' => $session,
            'photos' => $photos,
            'default_prompt' => AI_ENHANCE_DEFAULT_PROMPT,
            'provider' => defined('AI_PROVIDER') ? AI_PROVIDER : 'REPLICATE',
            'api_key_set' => (defined('AI_PROVIDER') && AI_PROVIDER === 'GEMINI') ? true : (REPLICATE_API_TOKEN !== 'YOUR_REPLICATE_API_TOKEN_HERE' && !empty(REPLICATE_API_TOKEN)),
        ];

        $this->view('photo/ai_enhance', $data);
    }

    public function process()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $session_id = $input['session_id'] ?? null;
        $photo_path = $input['photo_path'] ?? null;
        $prompt = $input['prompt'] ?? AI_ENHANCE_DEFAULT_PROMPT;
        // API key dari config, tidak pernah dari client
        $provider = defined('AI_PROVIDER') ? AI_PROVIDER : 'REPLICATE';

        if (!$session_id || !$photo_path) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        try {
            if ($provider === 'REPLICATE') {
                $api_key = REPLICATE_API_TOKEN;
                if (empty($api_key) || $api_key === 'YOUR_REPLICATE_API_TOKEN_HERE') {
                    throw new Exception('Replicate API Token belum dikonfigurasi di config.php');
                }
                return $this->processReplicate($session_id, $photo_path, $prompt, $api_key);
            } else if ($provider === 'GEMINI') {
                return $this->processGemini($session_id, $photo_path, $prompt);
            } else {
                throw new Exception('AI Provider tidak valid.');
            }
        } catch (Exception $e) {
            error_log('AiController::process error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function processReplicate($session_id, $photo_path, $prompt, $api_key)
    {
        try {
            $photoSessionModel = $this->model('PhotoSession');
            $session = $photoSessionModel->find($session_id);
            if (!$session)
                throw new Exception('Session not found');

            $absolutePath = dirname(APPROOT) . '/public' . $photo_path;
            if (!file_exists($absolutePath))
                throw new Exception('Photo file not found');

            // Replicate memerlukan URL publik, bukan base64 data URI
            $imageUrl = URLROOT . $photo_path;

            $modelStr = defined('REPLICATE_MODEL') ? REPLICATE_MODEL : 'stability-ai/sdxl';

            $inputData = [
                'prompt' => $prompt,
                'image' => $imageUrl,
                'prompt_strength' => defined('REPLICATE_PROMPT_STRENGTH') ? (float) REPLICATE_PROMPT_STRENGTH : 0.40
            ];

            // Tambahkan parameter tambahan dari config jika ada
            if (defined('REPLICATE_NUM_INFERENCE_STEPS')) {
                $inputData['num_inference_steps'] = (int) REPLICATE_NUM_INFERENCE_STEPS;
            }
            // SDXL menggunakan guidance_scale, bukan guidance
            if (defined('REPLICATE_GUIDANCE')) {
                $inputData['guidance_scale'] = (float) REPLICATE_GUIDANCE;
            }

            if (strpos($modelStr, '/') !== false) {
                // Format owner/model name
                $replicateUrl = 'https://api.replicate.com/v1/models/' . $modelStr . '/predictions';
                $bodyParams = ['input' => $inputData];
            } else {
                // Format version hash
                $replicateUrl = 'https://api.replicate.com/v1/predictions';
                $bodyParams = [
                    'version' => $modelStr,
                    'input' => $inputData
                ];
            }

            $requestBody = json_encode($bodyParams);

            $ch = curl_init($replicateUrl);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $requestBody,
                CURLOPT_HTTPHEADER => [
                    'Authorization: Bearer ' . $api_key,
                    'Content-Type: application/json',
                    'Prefer: wait'
                ],
                CURLOPT_TIMEOUT => 120,
                CURLOPT_SSL_VERIFYPEER => false,
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            if ($curlError)
                throw new Exception('cURL error: ' . $curlError);

            $responseData = json_decode($response, true);

            error_log('Replicate raw response (HTTP ' . $httpCode . '): ' . $response);

            if ($httpCode >= 400) {
                // Replicate bisa mengembalikan error di berbagai field
                $errorMsg = $responseData['detail']
                    ?? $responseData['error']
                    ?? $responseData['title']
                    ?? ('HTTP ' . $httpCode . ' - ' . $response);

                if ($httpCode === 429) {
                    throw new Exception(
                        'Replicate API error 429: Rate limit atau kredit habis. ' .
                        'Pastikan akun Replicate Anda memiliki kredit/payment method aktif di https://replicate.com/account/billing. ' .
                        'Detail: ' . $errorMsg
                    );
                }
                if ($httpCode === 401) {
                    throw new Exception('Replicate API error 401: API Token tidak valid. Cek REPLICATE_API_TOKEN di config.php.');
                }
                throw new Exception('Replicate API error (' . $httpCode . '): ' . $errorMsg);
            }

            $status = $responseData['status'] ?? '';
            $pollUrl = $responseData['urls']['get'] ?? '';

            $maxRetries = 30; // 60 seconds (2s per poll)
            $attempts = 0;
            while (in_array($status, ['starting', 'processing']) && $attempts < $maxRetries) {
                sleep(2);
                $attempts++;

                $ch = curl_init($pollUrl);
                curl_setopt_array($ch, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HTTPHEADER => [
                        'Authorization: Bearer ' . $api_key,
                    ],
                    CURLOPT_SSL_VERIFYPEER => false,
                ]);
                $pollResponse = curl_exec($ch);
                curl_close($ch);

                $pollData = json_decode($pollResponse, true);
                $status = $pollData['status'] ?? 'failed';
                $responseData = $pollData;
            }

            if ($status !== 'succeeded') {
                throw new Exception('Replicate failed or timed out. Status: ' . $status);
            }

            $outputUrl = is_array($responseData['output']) ? $responseData['output'][0] : $responseData['output'];
            $textResponse = 'Image generated successfully.';

            if ($outputUrl) {
                $chImage = curl_init($outputUrl);
                curl_setopt_array($chImage, [
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_TIMEOUT => 30
                ]);
                $imageContent = curl_exec($chImage);
                $httpCodeImage = curl_getinfo($chImage, CURLINFO_HTTP_CODE);
                curl_close($chImage);

                if (!$imageContent || $httpCodeImage >= 400)
                    throw new Exception('Gagal mendownload hasil dari Replicate');
                $generatedBase64 = base64_encode($imageContent);
            } else {
                $generatedBase64 = null;
            }

            if ($generatedBase64) {
                $uploadDir = dirname(APPROOT) . '/public/uploads/ai_enhanced/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0775, true);
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        exec('icacls "' . $uploadDir . '" /grant Users:(OI)(CI)F');
                        exec('icacls "' . $uploadDir . '" /grant IUSR:(OI)(CI)F');
                        exec('icacls "' . $uploadDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                    }
                }

                $filename = 'ai_' . $session_id . '_' . uniqid() . '.png';
                $savePath = $uploadDir . $filename;
                $relativePath = '/uploads/ai_enhanced/' . $filename;

                file_put_contents($savePath, base64_decode($generatedBase64));

                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $savePath . '" /grant Users:F');
                    exec('icacls "' . $savePath . '" /grant IUSR:F');
                    exec('icacls "' . $savePath . '" /grant IIS_IUSRS:F');
                }

                // Save as a new session photo so it appears in layout editor
                $photoSessionPhotoModel = $this->model('PhotoSessionPhoto');
                $photo_id = $photoSessionPhotoModel->create([
                    'session_id' => $session_id,
                    'file_path' => $relativePath,
                    'is_saved' => 1,
                ]);

                echo json_encode([
                    'success' => true,
                    'image_path' => $relativePath,
                    'photo_id' => $photo_id,
                    'text_response' => $textResponse,
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Replicate tidak menghasilkan gambar. ' . ($textResponse ?? 'Coba ubah prompt Anda.'),
                    'text_response' => $textResponse,
                ]);
            }

        } catch (Exception $e) {
            throw $e;
        }
    }

    private function processGemini($session_id, $photo_path, $prompt)
    {
        $projectId = GOOGLE_CLOUD_PROJECT_ID;
        $location = GOOGLE_CLOUD_LOCATION;

        // Define path to the ADC file created by gcloud on Windows
        $adcPath = 'C:\Users\ahnri\AppData\Roaming\gcloud\application_default_credentials.json';
        if (file_exists($adcPath)) {
            putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $adcPath);
        }

        // Dapatkan Access Token menggunakan Application Default Credentials (ADC)
        // Pastikan user sudah menjalankan `gcloud auth application-default login`
        try {
            $scopes = ['https://www.googleapis.com/auth/cloud-platform'];
            $credentials = ApplicationDefaultCredentials::getCredentials($scopes);
            $tokenData = $credentials->fetchAuthToken();
            $accessToken = $tokenData['access_token'] ?? null;

            if (!$accessToken) {
                throw new Exception('Gagal mendapatkan Google Access Token. Pastikan gcloud ADC sudah diatur.');
            }
        } catch (Exception $e) {
            throw new Exception('Autentikasi Google Cloud gagal: ' . $e->getMessage() . '. Jalankan "gcloud auth application-default login" di terminal.');
        }

        $photoSessionModel = $this->model('PhotoSession');
        $session = $photoSessionModel->find($session_id);
        if (!$session)
            throw new Exception('Session not found');

        $absolutePath = dirname(APPROOT) . '/public' . $photo_path;
        if (!file_exists($absolutePath))
            throw new Exception('Photo file not found');

        $imageContent = file_get_contents($absolutePath);
        $base64Image = base64_encode($imageContent);
        $mimeType = mime_content_type($absolutePath);

        // Endpoint Vertex AI Gemini (menggunakan model nano banana 2 / gemini-3.1-flash-image)
        $modelStr = defined('GEMINI_MODEL') ? GEMINI_MODEL : 'gemini-3.1-flash-image';
        $url = "https://{$location}-aiplatform.googleapis.com/v1/projects/{$projectId}/locations/{$location}/publishers/google/models/{$modelStr}:generateContent";

        $payload = [
            'contents' => [
                [
                    'role' => 'user',
                    'parts' => [
                        ['text' => $prompt],
                        [
                            'inline_data' => [
                                'mime_type' => $mimeType,
                                'data'      => $base64Image
                            ]
                        ]
                    ]
                ]
            ],
            'generationConfig' => [
                'temperature' => defined('GEMINI_TEMPERATURE') ? (float)GEMINI_TEMPERATURE : 0.4,
                'topK' => defined('GEMINI_TOP_K') ? (int)GEMINI_TOP_K : 32,
                'topP' => defined('GEMINI_TOP_P') ? (float)GEMINI_TOP_P : 1.0,
                'maxOutputTokens' => defined('GEMINI_MAX_TOKENS') ? (int)GEMINI_MAX_TOKENS : 2048,
            ]
        ];

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json'
            ],
            CURLOPT_TIMEOUT        => 120,
            CURLOPT_SSL_VERIFYPEER => false,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $responseData = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMsg = $responseData['error']['message'] ?? $response;
            throw new Exception('Gemini API Error (' . $httpCode . '): ' . $errorMsg);
        }

        $generatedBase64 = null;
        $textResponse = 'Image generated successfully with Gemini (Nano Banana 2).';

        if (isset($responseData['candidates'][0]['content']['parts'])) {
            $parts = $responseData['candidates'][0]['content']['parts'];
            foreach ($parts as $part) {
                if (isset($part['inlineData']) || isset($part['inline_data'])) {
                    $inlineData = $part['inlineData'] ?? $part['inline_data'];
                    $generatedBase64 = $inlineData['data'];
                    break;
                } elseif (isset($part['text'])) {
                    // Jika model membalas dengan teks alih-alih gambar
                    $textResponse = $part['text'];
                }
            }
        }

        if ($generatedBase64) {
            $uploadDir = dirname(APPROOT) . '/public/uploads/ai_enhanced/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0775, true);
                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                    exec('icacls "' . $uploadDir . '" /grant Users:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IUSR:(OI)(CI)F');
                    exec('icacls "' . $uploadDir . '" /grant IIS_IUSRS:(OI)(CI)F');
                }
            }

            $filename     = 'ai_' . $session_id . '_' . uniqid() . '.png';
            $savePath     = $uploadDir . $filename;
            $relativePath = '/uploads/ai_enhanced/' . $filename;

            file_put_contents($savePath, base64_decode($generatedBase64));

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                exec('icacls "' . $savePath . '" /grant Users:F');
                exec('icacls "' . $savePath . '" /grant IUSR:F');
                exec('icacls "' . $savePath . '" /grant IIS_IUSRS:F');
            }

            // Save as a new session photo so it appears in layout editor
            $photoSessionPhotoModel = $this->model('PhotoSessionPhoto');
            $photo_id = $photoSessionPhotoModel->create([
                'session_id' => $session_id,
                'file_path'  => $relativePath,
                'is_saved'   => 1,
            ]);

            echo json_encode([
                'success'       => true,
                'image_path'    => $relativePath,
                'photo_id'      => $photo_id,
                'text_response' => $textResponse,
            ]);
        } else {
            // Jika tidak ada gambar yang dihasilkan, kembalikan response error/teks
            echo json_encode([
                'success'       => false,
                'message'       => 'Gemini tidak menghasilkan gambar. Response: ' . $textResponse,
                'text_response' => $textResponse,
            ]);
        }
    }
}
