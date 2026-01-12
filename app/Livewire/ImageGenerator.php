<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

class ImageGenerator extends Component
{
    use WithFileUploads;

    public $file;
    public string $summary = '';
    public bool $loading = false;
    public ?string $error = null;
    public ?string $fileName = null;
    public int $uploadProgress = 0;

    protected $rules = [
        'file' => 'required|file|mimes:pdf,ppt,pptx|max:10240', // Max 10MB
    ];

    protected $messages = [
        'file.required' => 'File harus diupload.',
        'file.mimes' => 'File harus berformat PDF, PPT, atau PPTX.',
        'file.max' => 'Ukuran file maksimal 10MB.',
    ];

    /**
     * Fungsi untuk mereset form
     */
    public function resetForm()
    {
        $this->reset(['file', 'summary', 'error', 'fileName', 'uploadProgress']);
    }

    /**
     * Fungsi utama untuk meringkas khotbah dari file
     */
    public function summarize()
    {
        $this->validate();
        $this->loading = true;
        $this->error = null;
        $this->summary = '';

        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            $this->error = 'API Key untuk Gemini (GEMINI_API_KEYY) tidak ditemukan di .env';
            $this->loading = false;
            return;
        }

        try {
            // Simpan file sementara
            $path = $this->file->store('temp', 'local');
            $fullPath = Storage::disk('local')->path($path);
            $this->fileName = $this->file->getClientOriginalName();

            // Extract text dari file
            $extractedText = $this->extractTextFromFile($fullPath, $this->file->getClientOriginalExtension());

            if (empty($extractedText)) {
                $this->error = 'Gagal mengekstrak teks dari file. Pastikan file tidak kosong atau corrupt.';
                $this->loading = false;
                Storage::disk('local')->delete($path);
                return;
            }

            // Kirim ke Gemini API untuk diringkas
            $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=' . $apiKey;

            $prompt = "Berikut adalah konten dari sebuah khotbah/presentasi:\n\n{$extractedText}\n\n" .
                      "Tolong buatkan ringkasan khotbah yang mencakup:\n" .
                      "1. Judul/Tema Utama\n" .
                      "2. Ayat/Referensi Alkitab (jika ada)\n" .
                      "3. Poin-poin Utama (3-5 poin)\n" .
                      "4. Kesimpulan/Aplikasi Praktis\n\n" .
                      "Format dalam bahasa Indonesia yang mudah dipahami.";

            $response = Http::timeout(60)
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        'temperature' => 0.7,
                        'maxOutputTokens' => 2048,
                    ]
                ]);

            if ($response->successful()) {
                $candidate = $response->json('candidates.0');
                
                if ($candidate && isset($candidate['content']['parts'][0]['text'])) {
                    $this->summary = $candidate['content']['parts'][0]['text'];
                } else {
                    $finishReason = $candidate['finishReason'] ?? 'UNKNOWN';
                    
                    if ($finishReason === 'SAFETY') {
                        $this->error = 'Konten diblokir karena alasan keamanan. Coba file lain.';
                    } else {
                        $this->error = 'API berhasil, namun tidak ada ringkasan yang dihasilkan. (Reason: ' . $finishReason . ')';
                    }
                }
            } else {
                $status = $response->status();
                $errorMessage = $response->json('error.message', $response->reason());
            
                if ($status == 400) {
                    $this->error = "Bad Request (400): " . $errorMessage;
                } elseif ($status == 403) {
                    $this->error = "Permission Denied (403). Pastikan 'Generative Language API' aktif di Google Cloud Project.";
                } elseif ($status == 429) { // Tambahkan penanganan untuk error 429
                    $this->error = "Permintaan Sedang Banyak, Silahkan Klik Ulang tombol ringkas khotbah sekarang hingga berhasil";
                } else {
                    $this->error = "Gagal ({$status}): " . $errorMessage;
                }
            }

            // Hapus file temporary
            Storage::disk('local')->delete($path);

        } catch (\Exception $e) {
            $this->error = 'Terjadi kesalahan: ' . $e->getMessage();
        } finally {
            $this->loading = false;
        }
    }

    /**
     * Extract text dari file PDF atau PPT
     */
    private function extractTextFromFile(string $filePath, string $extension): string
    {
        $text = '';

        try {
            if ($extension === 'pdf') {
                // Parsing PDF menggunakan smalot/pdfparser
                $parser = new PdfParser();
                $pdf = $parser->parseFile($filePath);
                $text = $pdf->getText();
                
            } elseif (in_array($extension, ['ppt', 'pptx'])) {
                // Parsing PPT/PPTX
                $text = $this->extractTextFromPowerPoint($filePath, $extension);
            }

            // Bersihkan dan batasi teks (max 30000 karakter untuk API)
            $text = trim(preg_replace('/\s+/', ' ', $text));
            $text = mb_substr($text, 0, 30000);

        } catch (\Exception $e) {
            \Log::error('Error extracting text: ' . $e->getMessage());
        }

        return $text;
    }

    /**
     * Extract text dari PowerPoint
     */
    private function extractTextFromPowerPoint(string $filePath, string $extension): string
    {
        $text = '';

        try {
            if ($extension === 'pptx') {
                // PPTX adalah ZIP file
                $zip = new \ZipArchive();
                
                if ($zip->open($filePath) === true) {
                    $slideCount = 0;
                    
                    // Loop through slides
                    for ($i = 1; $i <= 50; $i++) {
                        $slideXml = $zip->getFromName("ppt/slides/slide{$i}.xml");
                        
                        if ($slideXml === false) {
                            break;
                        }
                        
                        $xml = simplexml_load_string($slideXml);
                        
                        if ($xml) {
                            $xml->registerXPathNamespace('a', 'http://schemas.openxmlformats.org/drawingml/2006/main');
                            $textElements = $xml->xpath('//a:t');
                            
                            foreach ($textElements as $textElement) {
                                $text .= (string)$textElement . ' ';
                            }
                        }
                        
                        $slideCount++;
                    }
                    
                    $zip->close();
                }
            } else {
                // PPT (format lama) - lebih kompleks, gunakan fallback
                $text = 'Format PPT lama tidak sepenuhnya didukung. Silakan convert ke PPTX terlebih dahulu.';
            }

        } catch (\Exception $e) {
            \Log::error('Error parsing PowerPoint: ' . $e->getMessage());
        }

        return $text;
    }

    /**
     * Download ringkasan sebagai text file
     */
    public function downloadSummary()
    {
        if (empty($this->summary)) {
            $this->error = 'Tidak ada ringkasan untuk didownload.';
            return;
        }

        $filename = 'ringkasan-khotbah-' . date('Y-m-d-His') . '.txt';
        
        return response()->streamDownload(
            fn() => print($this->summary),
            $filename,
            ['Content-Type' => 'text/plain']
        );
    }

    /**
     * Format summary dengan styling yang lebih baik
     */
    public function formatSummary($text)
    {
        // Escape HTML terlebih dahulu
        $text = e($text);
        
        // Format judul dengan heading (pattern: angka. Teks atau ### Teks)
        $text = preg_replace('/^(\d+\.\s*)(.+)$/m', '<h3 class="text-xl font-bold text-purple-700 mt-6 mb-3 flex items-center gap-2"><span class="bg-purple-100 px-3 py-1 rounded-lg">$1</span>$2</h3>', $text);
        
        // Format sub-judul dengan bold dan warna ungu (pattern: **Teks** atau Teks:)
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong class="text-purple-600 font-bold">$1</strong>', $text);
        $text = preg_replace('/^([A-Z][^:\n]+):(\s)/m', '<strong class="text-purple-600 font-bold text-lg block mt-4 mb-2">$1:</strong>', $text);
        
        // Format list (pattern: - item atau * item)
        $text = preg_replace('/^[\-\*]\s+(.+)$/m', '<li class="ml-6 mb-2 text-gray-700 flex items-start gap-2"><span class="text-purple-500 font-bold">•</span><span>$1</span></li>', $text);
        
        // Wrap lists in ul tags
        $text = preg_replace('/(<li class="ml-6[^>]*>.*<\/li>\s*)+/s', '<ul class="space-y-2 my-4">$0</ul>', $text);
        
        // Format ayat Alkitab (pattern: Nama Kitab angka:angka)
        $text = preg_replace('/\b([A-Z][a-z]+\s+\d+:\d+(?:-\d+)?)\b/', '<span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded font-semibold">📖 $1</span>', $text);
        
        // Format paragraf
        $text = preg_replace('/\n\n/', '</p><p class="mb-4 text-gray-700 leading-relaxed">', $text);
        $text = '<p class="mb-4 text-gray-700 leading-relaxed">' . $text . '</p>';
        
        // Format quote (pattern: > Teks)
        $text = preg_replace('/<p[^>]*>&gt;\s*(.+?)<\/p>/s', '<blockquote class="border-l-4 border-purple-500 pl-4 py-2 my-4 bg-purple-50 rounded-r-lg italic text-gray-700">$1</blockquote>', $text);
        
        return $text;
    }

    public function render()
    {
        return view('livewire.image-generator');
    }
}