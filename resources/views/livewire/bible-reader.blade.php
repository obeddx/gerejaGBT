<div class="p-6 border rounded-xl shadow-2xl bg-white max-w-4xl mx-auto my-8">
<h2 class="text-3xl font-extrabold text-purple-900 mb-6 text-center">📖 ALKITAB ONLINE </h2>
<p class="text-gray-500 text-center mb-6">Silahkan Cari Kitab dan Pilih Kitab yang anda cari , kemudian Pilih Pasal dari Kitab.</p>

@if ($isBooksLoading)
    <div class="text-center py-12">
        <svg class="animate-spin h-8 w-8 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-lg text-gray-600">Memuat daftar Kitab...</p>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    
    {{-- LIVEWIRE MURNI: KITAB SEARCH --}}
        <div id="kitab-selector" class="relative md:col-span-2">
            <label for="search-book" class="block text-sm font-medium text-gray-700 mb-1">Cari Kitab</label>
            
            {{-- Input Pencarian Kitab --}}
            <input 
                wire:model.live.debounce.300ms="searchTerm" 
                type="text" 
                id="search-book"
                placeholder="Ketik nama kitab (mis: Kejadian, Mazmur)..." 
                class="w-full border rounded-lg p-3 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 transition duration-150">

            {{-- Daftar Kitab yang Sedang Dipilih --}}
            @php
                $currentBook = collect($books)->firstWhere('abbr', $selectedAbbr)['label'] ?? 'Pilih Kitab';
            @endphp
            <div class="mt-2 text-sm text-purple-700 font-medium">
                Kitab Saat Ini: <span class="font-bold">{{ $currentBook }}</span>
            </div>

            {{-- Daftar Hasil Filter (Dropdown) --}}
            @if (!empty($searchTerm))
                <div id="kitab-list"
                    class="absolute z-10 mt-1 w-full rounded-lg bg-white shadow-xl border border-gray-200 ring-1 ring-black ring-opacity-5 focus:outline-none max-h-64 overflow-y-auto">
                    <ul class="py-1"> 
                        @forelse ($this->filteredBooks as $book)
                            {{-- Gunakan wire:click untuk memilih Kitab dan mereset searchTerm --}}
                            <li class="text-gray-900 cursor-pointer select-none relative py-2 px-4 transition duration-150 ease-in-out 
                                @if($book['abbr'] == $selectedAbbr) bg-indigo-50 font-semibold @else hover:bg-indigo-600 hover:text-white @endif"
                                wire:click="selectBook('{{ $book['abbr'] }}')">
                                {{ $book['label'] }}
                            </li>
                        @empty
                            <li class="text-gray-500 py-2 px-4 italic">Tidak ada kitab ditemukan.</li>
                        @endforelse
                    </ul>
                </div>
            @endif
        </div>

        {{-- SELECT CHAPTER STANDAR --}}
        <div class="relative">
            <label for="chapter-select" class="block text-sm font-medium text-gray-700 mb-1">Pasal (Chapter)</label>
            {{-- Gunakan wire:model.live agar setiap perubahan langsung memanggil updatedSelectedChapter() --}}
            <select wire:model.live="selectedChapter" id="chapter-select" class="mt-1 block w-full rounded-lg border border-gray-300 bg-white p-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 text-base">
                @for ($i = 1; $i <= $this->max_chapters; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
        </div>
        
        {{-- TOMBOL MUAT ULANG (Opsional, karena sudah dimuat otomatis oleh wire:model.live) --}}
        <div class="md:col-span-3 flex justify-center mt-4">
            <button wire:click="fetchPassage" 
                    wire:loading.attr="disabled"
                    class="px-8 py-3 bg-purple-600 hover:bg-purple-700 text-white font-bold text-lg rounded-full shadow-lg transition duration-200 ease-in-out disabled:bg-indigo-400">
                <span wire:loading.remove>Baca Ayat Sekarang</span>
                <span wire:loading>Memuat Ayat...</span>
            </button>
        </div>
    </div>

    {{-- AREA HASIL RESPON API --}}
    <hr class="my-8 border-gray-200">
    <div class="min-h-[200px] relative">
        <div wire:loading.remove.delay>
            @if (isset($passageData['error']))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ $passageData['error'] }}</span>
                </div>
            @elseif (isset($passageData['book']))
                <h3 class="text-2xl font-bold text-gray-800 mb-4">{{ $passageData['book']['name'] }} Pasal {{ $passageData['book']['chapter'] }}</h3>
                
                <div class="space-y-4 text-gray-700 text-lg leading-relaxed">
                    @foreach ($passageData['verses'] as $verse)
                        @if ($verse['type'] == 'title')
                            <p class="font-bold italic text-indigo-700 mt-4">{{ $verse['content'] }}</p>
                        @elseif ($verse['type'] == 'content')
                            <p class="flex items-start">
                                <span class="font-bold text-base text-indigo-600 mr-2 flex-shrink-0">{{ $verse['verse'] }}.</span> 
                                <span class="flex-grow">{{ $verse['content'] }}</span>
                            </p>
                        @endif
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-gray-500 italic text-lg">Pilih Kitab dan Pasal di atas untuk menampilkan teks Alkitab.</p>
                </div>
            @endif
        </div>
        
        <div wire:loading.delay class="absolute inset-0 flex items-center justify-center bg-white bg-opacity-80 rounded-lg">
            <p class="text-xl font-semibold text-indigo-600 flex items-center">
                <svg class="animate-spin h-6 w-6 mr-3 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Memuat data ayat...
            </p>
        </div>
    </div>
@endif


</div>