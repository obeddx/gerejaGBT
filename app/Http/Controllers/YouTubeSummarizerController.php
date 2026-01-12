<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
// --- IMPORT LIBRARY EXTERNAL (YOUTUBE TRANSCRIPT) ---
use MrMySQL\YoutubeTranscript\TranscriptListFetcher;
use MrMySQL\YoutubeTranscript\Exceptions\TranscriptNotFoundException;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory; // Untuk PSR-17 factories
// --- END IMPORT ---

class YouTubeSummarizerController extends Controller
{
    private $geminiApiKey;

    public function __construct()
    {
        $this->geminiApiKey = env('GEMINI_API_KEY');
    }

    public function summarize(Request $request)
    {
        if (empty($this->geminiApiKey)) {
            Log::error('GEMINI_API_KEY tidak ditemukan di file .env');
            return response()->json(['error' => 'Kunci API tidak dikonfigurasi di server.'], 500);
        }

        $validator = Validator::make($request->all(), [
            'url' => 'required|url'
        ], [
            'url.required' => 'URL YouTube tidak boleh kosong.',
            'url.url' => 'Format URL tidak valid.'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $youtubeUrl = $request->input('url');
        $videoId = $this->extractVideoId($youtubeUrl);
        if (!$videoId) {
            return response()->json(['error' => 'URL YouTube tidak valid'], 400);
        }

        // Panggil fungsi baru yang menggunakan TranscriptListFetcher
        $transcriptResult = $this->fetchAndFormatTranscript($videoId);
        if (isset($transcriptResult['error'])) {
            return response()->json(['error' => $transcriptResult['error']], 400);
        }
        $transcript = $transcriptResult['transcript'];

        // Rangkum dengan Gemini AI
        $summary = $this->summarizeWithGemini($transcript);
        if (isset($summary['error'])) {
            return response()->json(['error' => $summary['error']], 500);
        }

        return response()->json([
            'success' => true,
            'summary' => $summary['text']
        ]);
    }
    
    /**
     * ======================================================================
     * FUNGSI BARU: Mengambil transkrip menggunakan TranscriptListFetcher
     * DENGAN ENHANCED DEBUGGING
     * ======================================================================
     */
    private function fetchAndFormatTranscript($videoId)
    {
        try {
            // === STEP 1: LOG ENVIRONMENT INFO ===
            Log::info("=== START FETCH TRANSCRIPT DEBUG ===");
            Log::info("Video ID: $videoId");
            Log::info("PHP Version: " . PHP_VERSION);
            Log::info("Server IP: " . ($_SERVER['SERVER_ADDR'] ?? 'unknown'));
            Log::info("allow_url_fopen: " . ini_get('allow_url_fopen'));
            Log::info("max_execution_time: " . ini_get('max_execution_time'));
            
            // Cek ekstensi yang diperlukan
            $extensions = [
                'curl' => extension_loaded('curl'),
                'openssl' => extension_loaded('openssl'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
            ];
            Log::info("PHP Extensions: " . json_encode($extensions));
            
            // === STEP 2: TEST BASIC CONNECTIVITY ===
            Log::info("Testing YouTube connectivity...");
            try {
                $testUrl = "https://www.youtube.com/watch?v={$videoId}";
                $ch = curl_init($testUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                
                $result = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                $curlError = curl_error($ch);
                $curlErrno = curl_errno($ch);
                curl_close($ch);
                
                Log::info("YouTube Connectivity Test:", [
                    'http_code' => $httpCode,
                    'curl_error' => $curlError,
                    'curl_errno' => $curlErrno,
                    'response_length' => strlen($result),
                    'success' => $httpCode == 200
                ]);
                
                if ($httpCode != 200) {
                    Log::warning("YouTube tidak dapat diakses. HTTP Code: $httpCode");
                }
            } catch (\Exception $e) {
                Log::error("Connectivity test failed: " . $e->getMessage());
            }

            // === STEP 3: INITIALIZE TRANSCRIPT FETCHER ===
            Log::info("Initializing TranscriptListFetcher...");
            
            try {
                $http_client = new Client([
                    'timeout' => 30,
                    'connect_timeout' => 10,
                    'verify' => true, // SSL verification
                    'http_errors' => true,
                    'headers' => [
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    ]
                ]);
                
                $request_factory = new HttpFactory();
                $stream_factory = new HttpFactory(); 
                
                $fetcher = new TranscriptListFetcher($http_client, $request_factory, $stream_factory);
                Log::info("TranscriptListFetcher initialized successfully");
                
            } catch (\Exception $e) {
                Log::error("Failed to initialize TranscriptListFetcher", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
            
            // === STEP 4: FETCH TRANSCRIPT LIST ===
            Log::info("Fetching transcript list from YouTube...");
            
            try {
                $transcript_list = $fetcher->fetch($videoId);
                Log::info("Transcript list fetched successfully");
                
                // Log available languages
                $available_codes = $transcript_list->getAvailableLanguageCodes();
                Log::info("Available language codes: " . json_encode($available_codes));
                
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                Log::error("Guzzle Request Exception", [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'has_response' => $e->hasResponse(),
                    'response' => $e->hasResponse() ? $e->getResponse()->getBody()->getContents() : null,
                ]);
                throw $e;
            } catch (\Exception $e) {
                Log::error("Failed to fetch transcript list", [
                    'error_class' => get_class($e),
                    'error_message' => $e->getMessage(),
                    'error_code' => $e->getCode(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            // === STEP 5: FIND TRANSCRIPT ===
            Log::info("Finding transcript in preferred languages...");
            
            $language_codes = ['id', 'en']; // Prioritaskan ID (Indonesia) dan EN (English)
            
            try {
                $transcript = $transcript_list->findTranscript($language_codes);
                Log::info("Transcript found in preferred languages");
                
            } catch (TranscriptNotFoundException $e) {
                Log::warning("Preferred transcript not found, trying alternatives");
                
                // Coba ambil transkrip yang tersedia
                $available_codes = $transcript_list->getAvailableLanguageCodes();
                if (empty($available_codes)) {
                    throw new TranscriptNotFoundException("Tidak ada transkrip yang tersedia di video ini.");
                }
                
                Log::info("Using fallback language: " . $available_codes[0]);
                $transcript = $transcript_list->findTranscript([$available_codes[0]]); 
            }
            
            // === STEP 6: FETCH TRANSCRIPT TEXT ===
            Log::info("Fetching transcript text...");
            
            try {
                $rawTranscript = $transcript->fetch();
                Log::info("Raw transcript fetched", [
                    'lines_count' => count($rawTranscript),
                    'first_line_sample' => isset($rawTranscript[0]) ? substr($rawTranscript[0]['text'] ?? '', 0, 50) : 'N/A'
                ]);
                
            } catch (\Exception $e) {
                Log::error("Failed to fetch transcript text", [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }

            if (empty($rawTranscript)) {
                Log::error('Transkrip yang dikembalikan dari library kosong.');
                return ['error' => 'Video ini tidak memiliki subtitle/transkrip (teks kosong).'];
            }

            // === STEP 7: FORMAT TRANSCRIPT ===
            Log::info("Formatting transcript...");
            $transcriptText = '';
            foreach ($rawTranscript as $line) {
                $transcriptText .= $line['text'] . ' ';
            }
            
            $finalLength = strlen(trim($transcriptText));
            Log::info("=== TRANSCRIPT FETCH SUCCESS ===", [
                'final_length' => $finalLength,
                'character_count' => $finalLength,
                'word_count_estimate' => str_word_count($transcriptText)
            ]);
            
            return ['transcript' => trim($transcriptText)];

        } catch (TranscriptNotFoundException $e) {
            Log::error('=== TranscriptNotFoundException ===', [
                'message' => $e->getMessage(),
                'video_id' => $videoId
            ]);
            return ['error' => 'Video ini tidak memiliki subtitle/transkrip yang tersedia.'];
            
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            Log::error('=== Guzzle Connection Exception ===', [
                'message' => $e->getMessage(),
                'video_id' => $videoId,
                'hint' => 'Kemungkinan firewall memblokir atau timeout'
            ]);
            return ['error' => 'Tidak dapat terhubung ke YouTube. Silakan coba lagi nanti.'];
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            Log::error('=== Guzzle Request Exception ===', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'video_id' => $videoId,
                'has_response' => $e->hasResponse()
            ]);
            return ['error' => 'Terjadi kesalahan saat mengambil transkrip dari YouTube.'];
            
        } catch (\Exception $e) {
            Log::error('=== General Exception ===', [
                'class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'video_id' => $videoId,
                'trace' => $e->getTraceAsString()
            ]);
            return ['error' => 'Terjadi kesalahan saat mengambil transkrip: ' . $e->getMessage()];
        }
    }


    private function extractVideoId($url)
    {
        // Logika ekstrak ID tetap sama
        $patterns = [
            '/youtube\.com\/watch\?v=([^&]+)/',
            '/youtu\.be\/([^?]+)/',
            '/youtube\.com\/embed\/([^?]+)/',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    private function summarizeWithGemini($transcript)
    {
        // Logika rangkuman Gemini tetap sama
        try {
            $geminiUrl = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-preview-09-2025:generateContent?key=" . $this->geminiApiKey;

            $prompt = "Tolong berikan rangkuman utama dalam bentuk poin-poin (bullet points) dari transkrip khotbah berikut ini. Fokus pada:\n";
            $prompt .= "1. Tema utama khotbah\n";
            $prompt .= "2. Ayat Alkitab yang digunakan\n";
            $prompt .= "3. Poin-poin pengajaran utama\n";
            $prompt .= "4. Aplikasi praktis untuk kehidupan\n\n";
            $prompt .= "Gunakan Bahasa Indonesia yang baik dan mudah dipahami.\n\n";
            $prompt .= "TRANSKRIP:\n" . substr($transcript, 0, 30000);

            $payload = [
                'contents' => [['parts' => [['text' => $prompt]]]],
                'generationConfig' => [
                    'temperature' => 0.5,
                    'maxOutputTokens' => 8192,
                ]
            ];

            Log::info('Mengirim transkrip ke Gemini API...');
            $response = Http::timeout(120)->post($geminiUrl, $payload);

            if (!$response->successful()) {
                $errorData = $response->json();
                $errorMessage = $errorData['error']['message'] ?? 'Unknown error';
                Log::error('Gemini API error: ' . $errorMessage);
                return ['error' => 'Gemini API error: ' . $errorMessage];
            }

            $result = $response->json();

            if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
                Log::info('Berhasil mendapatkan rangkuman dari Gemini');
                return ['text' => $result['candidates'][0]['content']['parts'][0]['text']];
            } else {
                Log::error('Struktur respons Gemini tidak terduga: ' . json_encode($result));
                return ['error' => 'Tidak dapat menemukan konten rangkuman dalam respons API'];
            }

        } catch (\Exception $e) {
            Log::error('Error summarizeWithGemini: ' . $e->getMessage());
            return ['error' => 'Terjadi kesalahan saat membuat rangkuman: ' . $e->getMessage()];
        }
    }
    
    /**
     * ======================================================================
     * ENDPOINT UNTUK DEBUG - HAPUS SETELAH DEBUGGING SELESAI
     * ======================================================================
     */
    public function debugEnvironment()
    {
        return response()->json([
            'php_version' => PHP_VERSION,
            'server_ip' => $_SERVER['SERVER_ADDR'] ?? 'unknown',
            'allow_url_fopen' => ini_get('allow_url_fopen'),
            'curl_version' => function_exists('curl_version') ? curl_version() : 'not available',
            'openssl_version' => OPENSSL_VERSION_TEXT,
            'max_execution_time' => ini_get('max_execution_time'),
            'memory_limit' => ini_get('memory_limit'),
            'extensions' => [
                'curl' => extension_loaded('curl'),
                'openssl' => extension_loaded('openssl'),
                'json' => extension_loaded('json'),
                'mbstring' => extension_loaded('mbstring'),
            ],
            'youtube_test' => $this->testYouTubeConnection(),
        ]);
    }
    
    private function testYouTubeConnection()
    {
        try {
            $ch = curl_init('https://www.youtube.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            
            $result = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
            
            return [
                'success' => $httpCode == 200,
                'http_code' => $httpCode,
                'error' => $error,
                'response_length' => strlen($result)
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}