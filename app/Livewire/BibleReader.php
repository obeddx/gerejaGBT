<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BibleReader extends Component
{
    // API Endpoints
    protected string $bookListApiUrl = 'https://beeble.vercel.app/api/v1/passage/list';
    protected string $passageApiBaseUrl = 'https://beeble.vercel.app/api/v1/passage/';

    // Data untuk Select Kitab
    public array $books = [];

    // State untuk Select Kitab yang dipilih
    public ?string $selectedAbbr = null;
    public ?int $selectedChapter = 1;

    // State baru untuk pencarian Livewire murni
    public string $searchTerm = '';
    
    // State untuk data respons API ayat
    public array $passageData = [];

    public bool $isBooksLoading = true;
    
    /**
     * Dijalankan sekali saat komponen pertama kali diinisialisasi.
     */
    public function mount()
    {
        $this->loadBooksFromApi();
    }

    /**
     * Mengambil daftar kitab dari API dan menginisialisasi state.
     */
    protected function loadBooksFromApi(): void
    {
        try {
            // Panggil API untuk mendapatkan daftar kitab
            $response = Http::timeout(10)->get($this->bookListApiUrl);

            if ($response->successful()) {
                $data = $response->json();
                $rawBooks = $data['data'] ?? [];

                // 1. Format data kitab
                $this->books = collect($rawBooks)->map(function ($book) {
                    return [
                        'abbr' => $book['abbr'],
                        'name' => $book['name'],
                        'max_chapter' => $book['chapter'],
                        'label' => $book['name'] . ' (' . $book['abbr'] . ')',
                    ];
                })->toArray();
                
                // 2. Set nilai default (kitab pertama)
                if (!empty($this->books)) {
                    $this->selectedAbbr = $this->books[0]['abbr']; // Default ke kitab pertama (Kejadian)
                }
                
                // 3. Muat data ayat awal
                $this->fetchPassage();
                
            } else {
                $this->passageData = ['error' => 'Gagal memuat daftar Kitab dari API. Status: ' . $response->status()];
                Log::error('Failed to fetch book list', ['status' => $response->status(), 'response' => $response->body()]);
            }
        } catch (\Exception $e) {
            $this->passageData = ['error' => 'Terjadi kesalahan koneksi saat memuat daftar Kitab: ' . $e->getMessage()];
            Log::error('Connection error fetching book list', ['error' => $e->getMessage()]);
        } finally {
            $this->isBooksLoading = false;
        }
    }

    /**
     * Dipanggil ketika $selectedAbbr berubah.
     */
    public function updatedSelectedAbbr()
    {
        // Reset Chapter ke 1 setiap kali Kitab berubah
        $this->selectedChapter = 1;
        // Panggil fetchPassage untuk memuat data baru
        $this->fetchPassage();
    }
    
    /**
     * Dipanggil ketika $selectedChapter berubah.
     */
    public function updatedSelectedChapter()
    {
        // Panggil fetchPassage untuk memuat data baru
        $this->fetchPassage();
    }


    /**
     * Mengambil data ayat dari API
     */
    public function fetchPassage()
    {
        if (!$this->selectedAbbr || !$this->selectedChapter) {
            return;
        }

        $url = $this->passageApiBaseUrl . $this->selectedAbbr . '/' . $this->selectedChapter;
        
        try {
            $response = Http::timeout(10)->get($url);

            if ($response->successful()) {
                $data = $response->json();
                $this->passageData = $data['data'] ?? [];
            } else {
                $this->passageData = ['error' => 'Gagal mengambil data ayat dari API. Status: ' . $response->status()];
            }
        } catch (\Exception $e) {
            $this->passageData = ['error' => 'Terjadi kesalahan koneksi: ' . $e->getMessage()];
        }
    }


    /**
     * Hitung jumlah maksimum chapter untuk kitab yang dipilih (Computed Property)
     */
    public function getMaxChaptersProperty(): int
    {
        $book = collect($this->books)->where('abbr', $this->selectedAbbr)->first();
        return $book['max_chapter'] ?? 1;
    }
    
    /**
     * Filter daftar kitab berdasarkan searchTerm (Computed Property)
     */
    public function getFilteredBooksProperty(): array
    {
        if (empty($this->searchTerm)) {
            // Jika searchTerm kosong, gunakan selectedAbbr untuk memastikan Kitab yang dipilih
            // tetap terlihat di display jika tidak ada search
            $selectedBook = collect($this->books)->firstWhere('abbr', $this->selectedAbbr);
            return $selectedBook ? [$selectedBook] : [];
        }

        $searchTermLower = strtolower($this->searchTerm);

        return collect($this->books)
            // Menggunakan str_contains (built-in PHP)
            ->filter(fn ($book) => str_contains(strtolower($book['label']), $searchTermLower))
            ->values()
            ->toArray();
    }
    
    /**
     * Aksi saat Kitab dipilih dari daftar filter.
     */
    public function selectBook(string $abbr): void
    {
        $this->selectedAbbr = $abbr;
        // Kosongkan search term agar daftar hasil pencarian hilang
        $this->searchTerm = '';
        // Karena kita set property, updatedSelectedAbbr() akan dipanggil otomatis
    }

    public function render()
    {
        return view('livewire.bible-reader');
    }
}
