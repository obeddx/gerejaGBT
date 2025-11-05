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
     * ======================================================================
     */
    private function fetchAndFormatTranscript($videoId)
    {
        try {
            Log::info("Mencoba mengambil transkrip untuk $videoId menggunakan TranscriptListFetcher.");

            // 1. Inisialisasi Klien HTTP dan Factories (Sesuai Dokumentasi)
            $http_client = new Client(['timeout' => 30]); // Set timeout 30 detik
            $request_factory = new HttpFactory();
            $stream_factory = new HttpFactory(); 
            
            $fetcher = new TranscriptListFetcher($http_client, $request_factory, $stream_factory);
            
            // 2. Fetch Transcript List
            $transcript_list = $fetcher->fetch($videoId);

            // 3. Cari Transkrip (Prioritas: ID, EN, atau yang pertama)
            $language_codes = ['id', 'en']; // Prioritaskan ID (Indonesia) dan EN (English)
            
            try {
                 $transcript = $transcript_list->findTranscript($language_codes);
            } catch (TranscriptNotFoundException $e) {
                // Coba ambil transkrip yang tersedia, auto-generated atau bahasa lain
                $available_codes = $transcript_list->getAvailableLanguageCodes();
                if (empty($available_codes)) {
                    throw new TranscriptNotFoundException("Tidak ada transkrip yang tersedia di video ini.");
                }
                // Ambil transkrip pertama yang tersedia
                $transcript = $transcript_list->findTranscript([$available_codes[0]]); 
                
            }
            
            // 4. Fetch Teks Transkrip
            $rawTranscript = $transcript->fetch();

            if (empty($rawTranscript)) {
                Log::error('Transkrip yang dikembalikan dari library kosong.');
                return ['error' => 'Video ini tidak memiliki subtitle/transkrip (teks kosong).'];
            }

            // 5. Gabungkan array of lines menjadi satu string panjang
            $transcriptText = '';
            foreach ($rawTranscript as $line) {
                // Tambahkan teks setiap baris, dipisahkan oleh spasi
                $transcriptText .= $line['text'] . ' ';
            }
            
            Log::info('Berhasil mendapatkan transkrip, panjang: ' . strlen($transcriptText) . ' char.');
            return ['transcript' => trim($transcriptText)];

        } catch (TranscriptNotFoundException $e) {
            Log::error('TranscriptNotFoundException: ' . $e->getMessage());
            return ['error' => 'Video ini tidak memiliki subtitle/transkrip yang tersedia.'];
        } catch (\Exception $e) {
            Log::error('Error fetchAndFormatTranscript: ' . $e->getMessage());
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
}