<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}"> <title>Selamat Datang - GBT Galilea Semarang</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Google Font (Inter) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js (untuk mobile menu) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    
    <!-- 1. TOM SELECT CSS (Untuk Livewire) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
    
    <!-- 2. Swiper.js CSS (BARU - Wajib untuk slider berita) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
    
    

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #581c87; /* Default purple-900 */
        }
        /* Custom Purple Colors */
        .text-purple-primary { color: #7e22ce; } /* purple-700 */
        .text-purple-secondary { color: #581c87; } /* purple-900 */
        .bg-purple-primary { background-color: #7e22ce; }
        .bg-purple-secondary { background-color: #581c87; }
        .bg-purple-light { background-color: #faf5ff; } /* purple-50 */
        .border-purple-light { border-color: #e9d5ff; } /* purple-200 */
        
        #result-content {
            white-space: pre-wrap;
            text-align: left;
        }

        /* BARU: Style untuk pagination dots Swiper agar warnanya ungu */
        .news-slider .swiper-pagination-bullet {
            background-color: #e9d5ff; /* purple-200 */
            opacity: 1;
        }
        .news-slider .swiper-pagination-bullet-active {
            background-color: #7e22ce !important; /* purple-700 */
        }
    </style>
    @livewireStyles
</head>
<body class="bg-white">

    <!-- 1. Komponen Navbar -->
    <nav x-data="{ open: false }" class="sticky top-0 w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Kiri: Logo dan Nama -->
                <div class="flex-shrink-0 flex items-center">
                    <img src="{{ asset('gbtgalilea.png') }}" 
                         alt="Logo GBT Galilea Semarang" 
                         class="w-8 h-8 object-cover mr-2 rounded-md"> 
                    <span class="font-bold text-xl text-purple-secondary">GBT GALILEA SEMARANG</span>
                </div>
                
                <!-- Tengah: Navigasi (Desktop) -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="#news" class="font-medium text-purple-primary hover:text-purple-secondary transition">G News</a>
                    <a href="#event" class="font-medium text-purple-primary hover:text-purple-secondary transition">Event</a>
                    {{-- <a href="#sejarah" class="font-medium text-purple-primary hover:text-purple-secondary transition">Sejarah</a> --}}
                    <a href="#khotbah" class="font-medium text-purple-primary hover:text-purple-secondary transition">Ringkas Khotbah</a>
                </div>
                
                <!-- Kanan: Tombol Hamburger (Mobile) -->
                <div class="md:hidden flex items-center">
                    <button @click="open = !open" class="inline-flex items-center justify-center p-2 rounded-md text-purple-primary hover:bg-purple-light focus:outline-none">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div :class="{'block': open, 'hidden': !open}" class="md:hidden hidden bg-white shadow-lg">
            <a href="#news" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">G News</a>
            <a href="#event" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">Event</a>
            {{-- <a href="#sejarah" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">Sejarah</a> --}}
            <a href="#khotbah" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">Ringkas Khotbah</a>
        </div>
    </nav>

    <!-- 2. Komponen Slider Foto Header -->
    <header class="relative w-full h-[70vh] max-h-[800px] overflow-hidden shadow-lg">
        <div id="slider-container" class="relative w-full h-full">
            @php $i = 0; @endphp
            @foreach ($events as $event)
                @if ($event->image)
                    <div class="slider-item absolute inset-0 transition-opacity duration-1000 ease-in-out {{ $i == 0 ? 'opacity-100' : 'opacity-0' }}">
                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->nama_event }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-white text-center p-4">
                            <h1 class="text-4xl md:text-6xl font-bold drop-shadow-md mb-4">{{ $event->nama_event }}</h1>
                            <p class="text-xl md:text-2xl font-light drop-shadow-md">Setiap {{ $event->hari }}, Pukul {{ date('H:i', strtotime($event->jam)) }}</p>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endif
            @endforeach
        </div>
        <button id="slider-prev" class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white bg-opacity-30 text-white p-3 rounded-full hover:bg-opacity-50 transition z-10">
            &#10094;
        </button>
        <button id="slider-next" class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white bg-opacity-30 text-white p-3 rounded-full hover:bg-opacity-50 transition z-10">
            &#10095;
        </button>
    </header>

    <!-- 3. Section Berita (News Slider) -->
    <section id="news" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-purple-secondary mb-12">Galilea News</h2>

            <!-- Swiper Slider Container -->
            <!-- 'overflow-visible' penting agar shadow terlihat -->
            <div class="swiper news-slider overflow-visible">
                <div class="swiper-wrapper">
                    
                    <!-- Loop Data Berita ($newss dari WelcomeController) -->
                    @forelse ($newss as $news)
                    <div class="swiper-slide h-auto pb-12"> <!-- Tambah pb-12 untuk ruang pagination -->
                        <!-- Card Berita -->
                        <div class="flex flex-col h-full bg-white rounded-xl shadow-lg overflow-hidden border border-purple-light transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                            <!-- Gambar Berita -->
                            @if ($news->gambar)
                                <img src="{{ Storage::url($news->gambar) }}" alt="{{ $news->judul }}" class="w-full h-48 object-cover">
                            @else
                                <div class="w-full h-48 bg-purple-200 flex items-center justify-center">
                                    <span class="text-purple-500">Tidak Ada Gambar</span>
                                </div>
                            @endif
                            
                            <!-- Konten Card -->
                            <div class="p-5 flex flex-col flex-grow">
                                <h3 class="text-xl font-bold text-purple-secondary mb-2 h-14 overflow-hidden">{{ $news->judul }}</h3>
                                
                                <!-- Keterangan (Lokasi) -->
                            <div class="flex items-start text-sm text-gray-600 mb-4">
                                
                                <!-- Ikon (masih ikon lokasi) -->
                                <svg class="w-4 h-4 mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                
                                <!-- 
                                PERBAIKAN: 
                                1. Ganti <span> ke <p> (lebih baik untuk paragraf).
                                2. Tambahkan 'break-words'. Ini akan memecah URL panjang (seperti contoh Anda) 
                                    dan mengizinkan teks 350 karakter untuk 'wrap' (membungkus) ke baris baru 
                                    tanpa merusak tata letak card.
                                -->
                                <p class="break-words">
                                    {!! linkify($news->keterangan) !!}
                                </p>
                            </div>

                                <div class="flex-grow"></div>

                                <!-- Info Waktu (Tanggal & Jam) -->
                                <div class="flex justify-between text-xs text-purple-primary pt-4 border-t border-purple-light mt-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        <span>{{ $news->tanggal ? \Carbon\Carbon::parse($news->tanggal)->format('d M Y') : 'TBA' }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ $news->jam ? date('H:i', strtotime($news->jam)) : '' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="swiper-slide">
                        <p class="text-center text-gray-500">Belum ada berita terbaru.</p>
                    </div>
                    @endforelse
                </div>
                
                <!-- Indikator Slider (Pagination Dots) -->
                <div class="swiper-pagination mt-12 relative"></div>

            </div>
        </div>
    </section>

    <!-- 4. Informasi Event -->
    <section id="event" class="py-20 bg-purple-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-purple-secondary mb-12">Kegiatan & Event</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($events as $event)
                    <div class="flex flex-col h-full bg-white rounded-xl shadow-lg overflow-hidden border border-purple-light transition-all duration-300 hover:shadow-2xl hover:-translate-y-1">
                        @if ($event->image)
                            <img src="{{ Storage::url($event->image) }}" alt="{{ $event->nama_event }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-purple-200 flex items-center justify-center">
                                <span class="text-purple-500">Tidak Ada Gambar</span>
                            </div>
                        @endif
                        <div class="p-5 flex flex-col flex-grow">
                            <h3 class="text-xl font-bold text-purple-secondary mb-2 h-14 overflow-hidden">{{ $event->nama_event }}</h3>
                            
                            <div class="flex-grow"></div>
                            
                            <!-- Info Waktu (Hari & Jam) -->
                            <div class="flex justify-between text-sm text-purple-primary pt-4 border-t border-purple-light mt-4">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span>Setiap {{ $event->hari }}</span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ date('H:i', strtotime($event->jam)) }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-gray-500 md:col-span-3">Belum ada event yang dijadwalkan.</p>
                @endforelse
            </div>
        </div>
    </section>

    <!-- 5. Informasi Sejarah (Roadmap) -->
    {{-- <section id="sejarah" class="py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-4xl font-bold text-center text-purple-secondary mb-16">Perjalanan Sejarah GBT Galilea</h2>
            
            <div class="relative wrap overflow-hidden p-10 h-full">
                <!-- Garis Vertikal Utama (Hanya Tampil di Layar Besar) -->
                <div class="border-2-2 absolute border-opacity-20 border-purple-primary h-full border hidden md:block" style="left: 50%"></div>
                
                <!-- Item 1: Awal Perintisan (Kiri di Desktop) -->
                <div class="mb-8 flex justify-between items-center w-full right-timeline">
                    <div class="order-1 w-full md:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-purple-primary shadow-xl w-8 h-8 rounded-full hidden md:flex">
                        <h1 class="mx-auto text-white font-semibold text-lg">1</h1>
                    </div>
                    <div class="order-1 bg-white rounded-lg shadow-xl w-full md:w-5/12 px-6 py-4 border border-purple-light transition duration-300 hover:shadow-2xl">
                        <h3 class="mb-3 font-bold text-xl text-purple-primary">1995: Awal Persekutuan</h3>
                        <img src="{{ asset('gbtgalilea_sejarah_1995.jpg') }}" alt="Foto Awal Persekutuan" class="w-full h-40 object-cover rounded-md mb-3 shadow" onerror="this.style.display='none'">
                        <p class="text-sm leading-snug tracking-wide text-gray-700 text-opacity-100">
                            Dimulai dari sebuah persekutuan doa kecil yang diadakan di rumah keluarga Jemaat A dengan 15 anggota. Visi utama adalah menjangkau kaum muda di daerah Semarang Timur.
                        </p>
                    </div>
                </div>

                <!-- Item 2: Pembelian Lahan (Kanan di Desktop) -->
                <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                    <div class="order-1 w-full md:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-purple-primary shadow-xl w-8 h-8 rounded-full hidden md:flex">
                        <h1 class="mx-auto text-white font-semibold text-lg">2</h1>
                    </div>
                    <div class="order-1 bg-white rounded-lg shadow-xl w-full md:w-5/12 px-6 py-4 border border-purple-light transition duration-300 hover:shadow-2xl">
                        <h3 class="mb-3 font-bold text-xl text-purple-primary">2001: Pembangunan Gedung</h3>
                        <img src="{{ asset('gbtgalilea_sejarah_2001.jpg') }}" alt="Foto Pembangunan Gedung" class="w-full h-40 object-cover rounded-md mb-3 shadow" onerror="this.style.display='none'">
                        <p class="text-sm leading-snug tracking-wide text-gray-700 text-opacity-100">
                            Setelah 6 tahun menumpang, gereja membeli lahan dan memulai pembangunan tahap pertama gedung permanen, difokuskan pada area ibadah utama.
                        </p>
                    </div>
                </div>
                
                <!-- Item 3: Peresmian (Kiri di Desktop) -->
                <div class="mb-8 flex justify-between items-center w-full right-timeline">
                    <div class="order-1 w-full md:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-purple-primary shadow-xl w-8 h-8 rounded-full hidden md:flex">
                        <h1 class="mx-auto text-white font-semibold text-lg">3</h1>
                    </div>
                    <div class="order-1 bg-white rounded-lg shadow-xl w-full md:w-5/12 px-6 py-4 border border-purple-light transition duration-300 hover:shadow-2xl">
                        <h3 class="mb-3 font-bold text-xl text-purple-primary">2005: Peresmian & Pengakuan Sinode</h3>
                        <img src="{{ asset('gbtgalilea_sejarah_2005.jpg') }}" alt="Foto Peresmian" class="w-full h-40 object-cover rounded-md mb-3 shadow" onerror="this.style.display='none'">
                        <p class="text-sm leading-snug tracking-wide text-gray-700 text-opacity-100">
                            Gedung diresmikan dan secara resmi diakui sebagai jemaat GBT (Gereja Bethel Tabernakel) di bawah Sinode Nasional.
                        </p>
                    </div>
                </div>

                <!-- Item 4: Pelayanan Misi (Kanan di Desktop) -->
                <div class="mb-8 flex justify-between flex-row-reverse items-center w-full left-timeline">
                    <div class="order-1 w-full md:w-5/12"></div>
                    <div class="z-20 flex items-center order-1 bg-purple-primary shadow-xl w-8 h-8 rounded-full hidden md:flex">
                        <h1 class="mx-auto text-white font-semibold text-lg">4</h1>
                    </div>
                    <div class="order-1 bg-white rounded-lg shadow-xl w-full md:w-5/12 px-6 py-4 border border-purple-light transition duration-300 hover:shadow-2xl">
                        <h3 class="mb-3 font-bold text-xl text-purple-primary">2015: Fokus Pelayanan Komunitas</h3>
                        <img src="{{ asset('gbtgalilea_sejarah_2015.jpg') }}" alt="Foto Pelayanan Komunitas" class="w-full h-40 object-cover rounded-md mb-3 shadow" onerror="this.style.display='none'">
                        <p class="text-sm leading-snug tracking-wide text-gray-700 text-opacity-100">
                            Gereja meluncurkan program pelayanan masyarakat dan misi peduli pendidikan, menjadi berkat yang nyata bagi lingkungan sekitar.
                        </p>
                    </div>
                </div>
                
            </div>
        </div>
    </section> --}}

    <!-- 6. Fitur Ringkas Khotbah (AI) -->
    <section id="khotbah" class="py-20 bg-purple-light">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-purple-secondary mb-4">Ringkasan Khotbah AI</h2>
            <p class="text-lg text-gray-700 mb-8">
                Tempelkan link video khotbah dari YouTube di bawah ini, dan biarkan AI membantu Anda 
                mendapatkan poin-poin utamanya.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" id="youtube-url" placeholder="Tempelkan link YouTube di sini..." class="flex-grow w-full px-5 py-3 border border-purple-light rounded-lg focus:ring-2 focus:ring-purple-primary focus:outline-none">
                <button id="summarize-btn" class="px-8 py-3 bg-purple-primary text-white font-semibold rounded-lg shadow-md hover:bg-purple-secondary transition duration-300">
                    Rangkum
                </button>
            </div>
            
            <div id="summarizer-loading" class="hidden text-center my-8">
                <svg class="animate-spin h-8 w-8 text-purple-primary mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="text-purple-primary mt-2">Sedang memproses... Ini mungkin perlu waktu 1-2 menit.</p>
            </div>
            
            <div id="summarizer-result" class="hidden text-left mt-8 p-6 bg-purple-light border border-purple-light rounded-xl shadow-inner">
                <h3 class="text-2xl font-bold text-purple-secondary mb-4">Hasil Rangkuman:</h3>
                <div id="result-content" class="text-gray-700 space-y-2" style="white-space: pre-wrap;"></div>
            </div>
            
            <div id="summarizer-error" class="hidden text-left mt-8 p-6 bg-red-100 text-red-700 border border-red-300 rounded-xl">
                <h3 class="text-xl font-bold mb-2">Terjadi Kesalahan</h3>
                <p id="error-content"></p>
            </div>
        </div>
    </section>

    <!-- 7. Livewire Bible Reader -->
    <div class="container mx-auto p-8">
        <!-- Gunakan Alpine.js untuk state management -->
        <div x-data="{ showBibleReader: false }">
            
            <!-- A. Tombol Pemicu (Hanya tampil jika showBibleReader = false) -->
            <div x-show="!showBibleReader" x-transition.opacity
                 class="p-6 border rounded-xl shadow-2xl bg-white max-w-4xl mx-auto my-8 text-center">
                
                <h2 class="text-3xl font-extrabold text-purple-900 mb-6 text-center">📖 ALKITAB ONLINE </h2>
                <p class="text-gray-500 text-lg mb-8">Ingin membaca Alkitab langsung di halaman ini?</p>
                
                <button 
                    @click="showBibleReader = true"
                    class="px-8 py-3 bg-purple-primary text-white font-semibold rounded-lg shadow-md hover:bg-purple-secondary transition duration-300">
                    Ya, Tampilkan Alkitab
                </button>
            </div>

            <!-- B. Komponen Livewire (Hanya tampil JIKA showBibleReader = true) -->
            <div x-show="showBibleReader" x-transition.opacity.duration.500ms>
                
                <!-- 
                  PENTING: 
                  Livewire akan menunda eksekusi 'wire:init' 
                  yang ada di 'bible-reader.blade.php' 
                  sampai 'x-show' ini menjadi 'true'.
                -->
                @livewire('bible-reader')
            </div>

        </div>
    </div>

    <!-- 8. Komponen Footer -->
    <footer class="bg-purple-secondary text-purple-100 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-2xl font-bold text-white mb-4">GBT GALILEA SEMARANG</h3>
                    <p class="text-purple-200">
                        Jl. Kuala Mas Raya No. Kav. 51-54 Panggung Lor,
                        <br>Kec Semarang Utara Semarang, Jawa Tengah
                    </p>
                    <p class="text-purple-200 mt-2">Tel: (024) 358-2377</p>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-white mb-4">Link Cepat</h3>
                    <ul class="space-y-2">
                        <li><a href="#news" class="text-purple-200 hover:text-white transition">Berita</a></li>
                        <li><a href="#event" class="text-purple-200 hover:text-white transition">Event & Kegiatan</a></li>
                        {{-- <li><a href="#sejarah" class="text-purple-200 hover:text-white transition">Sejarah Gereja</a></li> --}}
                        <li><a href="#khotbah" class="text-purple-200 hover:text-white transition">Ringkasan Khotbah</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-xl font-semibold text-white mb-4">Terhubung Dengan Kami</h3>
                    <div class="flex space-x-4">
                       <a href="https://www.youtube.com/@galileafamily" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="text-purple-200 hover:text-white transition" 
                        title="YouTube">
                            
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"> 
                                <path d="M10,15l5.19-3L10,9v6m11.56-7.8c-0.3-1.1-1.2-2-2.3-2.3C17.3,4.6,12,4.6,12,4.6s-5.3,0-7.2,0.3 c-1.1,0.3-2,1.2-2.3,2.3C2.2,6.7,2.2,12,2.2,12s0,5.3,0.3,7.2c0.3,1.1,1.2,2,2.3,2.3C6.7,21.8,12,21.8,12,21.8s5.3,0,7.2-0.3 c-1.1-0.3-2-1.2-2.3-2.3C21.8,17.3,21.8,12,21.8,12S21.8,6.7,21.56,4.8z"/> 
                            </svg>
                        </a>
                        <a href="https://www.instagram.com/gbtgalileasemarang" 
                        target="_blank" 
                        rel="noopener noreferrer" 
                        class="text-purple-200 hover:text-white transition" 
                        title="Instagram">
                            
                            <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24"> 
                                <path d="M7.8,2H16.2C19.4,2 22,4.6 22,7.8V16.2A5.8,5.8 0 0,1 16.2,22H7.8C4.6,22 2,19.4 2,16.2V7.8A5.8,5.8 0 0,1 7.8,2M7.6,4A3.6,3.6 0 0,0 4,7.6V16.4C4,18.39 5.61,20 7.6,20H16.4A3.6,3.6 0 0,0 20,16.4V7.6C20,5.61 18.39,4 16.4,4H7.6M17.25,5.5A1.25,1.25 0 0,1 18.5,6.75A1.25,1.25 0 0,1 17.25,8A1.25,1.25 0 0,1 16,6.75A1.25,1.25 0 0,1 17.25,5.5M12,7A5,5 0 0,1 17,12A5,5 0 0,1 12,17A5,5 0 0,1 7,12A5,5 0 0,1 12,7M12,9A3,3 0 0,0 9,12A3,3 0 0,0 12,15A3,3 0 0,0 15,12A3,3 0 0,0 12,9Z"/> 
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 pt-8 border-t border-purple-700 text-center text-purple-300">
                &copy; {{ date('Y') }} GBT Galilea Semarang. Developed by : Obed Danny.
            </div>
        </div>
    </footer>

    <!-- ================================================== -->
    <!-- == SKRIP BAGIAN BAWAH == -->
    <!-- ================================================== -->

    <!-- Swiper.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // --- Logika Slider Header (Tidak berubah) ---
            const sliderContainer = document.getElementById('slider-container');
            if (sliderContainer) {
                const slides = sliderContainer.querySelectorAll('.slider-item');
                const prevButton = document.getElementById('slider-prev');
                const nextButton = document.getElementById('slider-next');
                let currentSlide = 0;
                const slideCount = slides.length;

                function showSlide(index) {
                    slides.forEach((slide, i) => {
                        slide.classList.toggle('opacity-100', i === index);
                        slide.classList.toggle('opacity-0', i !== index);
                    });
                }

                function nextSlide() {
                    currentSlide = (currentSlide + 1) % slideCount;
                    showSlide(currentSlide);
                }

                function prevSlide() {
                    currentSlide = (currentSlide - 1 + slideCount) % slideCount;
                    showSlide(currentSlide);
                }

                if (slideCount > 0) { 
                    if (slideCount > 1) {
                        setInterval(nextSlide, 7000);
                        prevButton.addEventListener('click', prevSlide);
                        nextButton.addEventListener('click', nextSlide);
                    } else {
                        prevButton.style.display = 'none';
                        nextButton.style.display = 'none';
                    }
                } else {
                    sliderContainer.innerHTML = '<div class="absolute inset-0 bg-purple-secondary flex items-center justify-center text-white"><h1 class="text-4xl font-bold">GBT Galilea Semarang</h1></div>';
                    prevButton.style.display = 'none';
                    nextButton.style.display = 'none';
                }
            }
            
            // --- BARU: Logika News Slider (Swiper.js) ---
            const newsSwiper = new Swiper('.news-slider', {
                // Konfigurasi Swiper
                loop: false, // Loop tidak disarankan jika slide lebih sedikit dari slidesPerView
                spaceBetween: 30, // Jarak antar slide
                
                // Pagination dots (indikator)
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                // Responsif: Tampilkan lebih banyak card di layar besar
                slidesPerView: 1, // Default (mobile)
                breakpoints: {
                    // Saat layar >= 640px
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    // Saat layar >= 1024px
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 30,
                    },
                },
            });

            // --- Logika Summarizer (AI) (Tidak berubah) ---
            const summarizeBtn = document.getElementById('summarize-btn');
            const urlInput = document.getElementById('youtube-url');
            const loadingDiv = document.getElementById('summarizer-loading');
            const resultDiv = document.getElementById('summarizer-result');
            const resultContent = document.getElementById('result-content');
            const errorDiv = document.getElementById('summarizer-error');
            const errorContent = document.getElementById('error-content');

            summarizeBtn.addEventListener('click', handleSummarize);

            async function handleSummarize() {
                const youtubeUrl = urlInput.value.trim();
                
                loadingDiv.classList.add('hidden');
                resultDiv.classList.add('hidden');
                errorDiv.classList.add('hidden');

                if (!youtubeUrl) {
                    showError("Silakan masukkan URL YouTube terlebih dahulu.");
                    return;
                }

                loadingDiv.classList.remove('hidden');

                try {
                    const csrfTokenEl = document.querySelector('meta[name="csrf-token"]');
                    
                    if (!csrfTokenEl) {
                        throw new Error("CSRF token tidak ditemukan. Pastikan meta tag sudah ditambahkan.");
                    }
                    
                    const csrfToken = csrfTokenEl.getAttribute('content');

                    const response = await fetch('/api/summarize-youtube', {
                        method: 'POST',
                        headers: { 
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ url: youtubeUrl })
                    });

                    const contentType = response.headers.get("content-type");
                    if (!contentType || !contentType.includes("application/json")) {
                        const text = await response.text();
                        console.error("Response bukan JSON:", text);
                        throw new Error("Server mengembalikan response tidak valid. Kemungkinan error 419 (CSRF Token). Cek console.");
                    }

                    const result = await response.json();

                    if (!response.ok) {
                        throw new Error(result.error || result.message || 'Terjadi kesalahan');
                    }

                    if (result.success && result.summary) {
                        showResult(result.summary);
                    } else {
                        throw new Error(result.error || "Tidak dapat menemukan rangkuman dalam respons.");
                    }

                } catch (error) {
                    console.error('Error summarizing:', error);
                    showError(error.message || 'Terjadi masalah saat membuat rangkuman.');
                } finally {
                    loadingDiv.classList.add('hidden');
                }
            }

            function showResult(text) {
                let cleanedText = text
                    .replace(/^- /gm, '• ')      // Ganti bullet point (-) menjadi simbol •
                    .replace(/\*\*/g, '')         // Hapus bold (**)
                    .replace(/## /g, '')         // Hapus Heading 2 (##)
                    .replace(/### /g, '');       // Hapus Heading 3 (###)

                cleanedText = cleanedText.replace(/\n\s*\n/g, '\n\n'); 
                
                resultContent.textContent = cleanedText;
                resultDiv.classList.remove('hidden');
                errorDiv.classList.add('hidden');
            }

            function showError(message) {
                errorContent.textContent = message;
                errorDiv.classList.remove('hidden');
                resultDiv.classList.add('hidden');
            }
        });
    </script>

    {{-- Memuat Livewire Scripts dan Tom Select --}}
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

</body>
</html>

