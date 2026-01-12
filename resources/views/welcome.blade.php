<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Cari jadwal ibadah di Semarang? Kunjungi GBT Galilea, Gereja Kristen Protestan di Jawa Tengah. Ibadah Umum diadakan setiap Minggu jam 8 pagi & 5 sore.">
    <!--<link rel="icon" type="image/png" href="https://gbtgalileachruch.com/gbtgalilea.png" sizes="96x96">-->
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="GBT GALILEA" />
    <link rel="manifest" href="/site.webmanifest" />
    

    @if ($lcpImageUrl)
        <!-- Menarik gambar LCP dengan prioritas tinggi di awal proses parsing -->
        <link rel="preload" as="image" href="{{ $lcpImageUrl }}" fetchpriority="high">
    @endif


    <title>GBT Galilea Semarang</title>
    
        <!-- Preconnect -->
    <!--<link rel="preconnect" href="https://fonts.googleapis.com">-->
    <!--<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
    <link rel="preconnect" href="https://cdn.tailwindcss.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Tailwind CSS -->
    <!--<script src="https://cdn.tailwindcss.com"></script>-->
    <link rel="stylesheet" href="{{ asset('build/assets/app-BvmHU_HH.css') }}">
    <script src="{{ asset('build/assets/app-CAiCLEjY.js') }}" defer></script>
    
    

    
    
    <script src="https://js.pusher.com/beams/2.1.0/push-notifications-cdn.js" async></script>
    
    <!-- Google Font (Inter) -->
    <!--<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">-->
    
    <link href="{{ asset('resources/css/fonts.css') }}" rel="stylesheet">
    
    <!-- Alpine.js (untuk mobile menu) -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    
    <!-- 1. TOM SELECT CSS (Untuk Livewire) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" media="print" onload="this.media='all'">
    
    <!-- 2. Swiper.js CSS (BARU - Wajib untuk slider berita) -->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" media="print" onload="this.media='all'">
    
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
        
            /* Animasi untuk background blob */
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    
    .animate-blob {
        animation: blob 7s infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    
    .animation-delay-4000 {
        animation-delay: 4s;
    }
            /* BARU: Style untuk Hero Slider pagination */
        .hero-slider .swiper-pagination-bullet {
            background-color: #e9d5ff; /* purple-200 */
            opacity: 1;
            width: 10px;
            height: 10px;
        }
        .hero-slider .swiper-pagination-bullet-active {
            background-color: #581c87 !important; /* purple-900 */
            width: 25px; /* Lebih panjang saat aktif */
            border-radius: 5px;
            transition: width 0.3s;
        }
        
        /* Utility CSS */
        .text-shadow-lg {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.6);
        }
        /* BARU: Utilitas untuk memaksa rasio 16:9 */
        .aspect-16-9 {
            padding-bottom: 56.25%; /* Rasio 9 / 16 = 0.5625 (56.25%) */
            height: 0; /* Penting agar padding-bottom yang mengontrol tinggi */
            position: relative;
        }
        .aspect-16-9 > img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
                    <img src="{{ asset('favicon.svg') }}" 
                         alt="Logo GBT Galilea Semarang" 
                         class="w-8 h-8 object-cover mr-2 rounded-md"> 
                    <h1 class="font-bold text-xl text-purple-secondary">GBT GALILEA SEMARANG</h1>
                </div>
                
                <!-- Tengah: Navigasi (Desktop) -->
                <div class="hidden md:flex md:items-center md:space-x-8">
                    <a href="#news" class="font-medium text-purple-primary hover:text-purple-secondary transition">G News</a>
                    <a href="#event" class="font-medium text-purple-primary hover:text-purple-secondary transition">Event</a>
                    <a href="#alkitab" class="font-medium text-purple-primary hover:text-purple-secondary transition">Alkitab</a>
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
            <a href="#alkitab" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">Alkitab</a>
            <a href="#khotbah" @click="open = false" class="block px-4 py-3 text-base font-medium text-purple-primary hover:bg-purple-light">Ringkas Khotbah</a>
        </div>
    </nav>
    
    <!-- Komponen CTA Section (Letakkan setelah </nav> dan sebelum <header>) -->
 <section class="relative bg-gradient-to-br from-purple-50 via-white to-purple-100 py-16 overflow-hidden">
    <!-- Decorative Background Elements -->
    <div class="absolute top-0 left-0 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
    <div class="absolute top-0 right-0 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    <div class="absolute bottom-0 left-1/2 w-64 h-64 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-4000"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            
            <!-- Bagian Kiri: Teks & CTA -->
            <div class="text-center md:text-left">
                <!-- Badge -->
                <div class="inline-flex items-center px-4 py-2 bg-purple-100 rounded-full mb-6">
                    <span class="text-purple-700 text-sm font-semibold">✨ Gereja Kristen di Semarang</span>
                </div>
                
                <!-- Heading -->
                <h2 class="text-4xl md:text-5xl font-bold text-purple-900 mb-4 leading-tight">
                    Bergabunglah Bersama 
                    <span class="text-purple-700">Keluarga Galilea</span>
                </h2>
                
                <!-- Subheading -->
                <p class="text-lg text-purple-700 mb-8 leading-relaxed">
                    Temukan makna hidup yang lebih dalam, bangun relasi yang bermakna, dan bertumbuh dalam iman bersama komunitas yang hangat dan penuh kasih.
                </p>
                
                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start">
                    <a href="#event" 
                       class="group inline-flex items-center justify-center px-8 py-4 bg-purple-700 text-white font-semibold rounded-xl shadow-lg hover:bg-purple-800 hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300">
                        <span>Lihat Jadwal Ibadah</span>
                        <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    
                    <a href="#news" 
                       class="inline-flex items-center justify-center px-8 py-4 bg-white text-purple-700 font-semibold rounded-xl border-2 border-purple-300 hover:bg-purple-50 hover:border-purple-500 shadow-md hover:shadow-lg transform hover:-translate-y-1 transition-all duration-300">
                        <span>Pelajari Lebih Lanjut</span>
                    </a>
                </div>
                
                <!-- Stats -->
                <div class="grid grid-cols-3 gap-6 mt-12 pt-8 border-t border-purple-200">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-900 mb-1">300+</div>
                        <div class="text-sm text-purple-600">Jemaat Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-900 mb-1">5+</div>
                        <div class="text-sm text-purple-600">Program Mingguan</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-900 mb-1">20+</div>
                        <div class="text-sm text-purple-600">Tahun Melayani</div>
                    </div>
                </div>
            </div>
            
            <!-- Bagian Kanan: Visual/Icon Grid -->
            <div class="hidden md:grid grid-cols-2 gap-6">
                <!-- Card 1: Ibadah -->
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-purple-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900 mb-2">Ibadah Minggu</h3>
                    <p class="text-sm text-purple-600">Persekutuan penuh makna setiap minggu</p>
                </div>
                
                <!-- Card 2: Komunitas -->
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-purple-100 hover:shadow-2xl transition-shadow duration-300 mt-8">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900 mb-2">Kelompok Sel</h3>
                    <p class="text-sm text-purple-600">Bertumbuh bersama dalam kelompok kecil</p>
                </div>
                
                <!-- Card 3: Pelayanan -->
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-purple-100 hover:shadow-2xl transition-shadow duration-300">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900 mb-2">Pelayanan</h3>
                    <p class="text-sm text-purple-600">Berbagi kasih kepada sesama</p>
                </div>
                
                <!-- Card 4: Doa -->
                <div class="bg-white p-6 rounded-2xl shadow-xl border border-purple-100 hover:shadow-2xl transition-shadow duration-300 mt-8">
                    <div class="w-14 h-14 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900 mb-2">Doa & Syafaat</h3>
                    <p class="text-sm text-purple-600">Mendoakan kebutuhan jemaat</p>
                </div>
            </div>
            
        </div>
    </div>
</section>

    <!-- 2. Komponen Slider Foto Header -->
   

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
                                <img src="{{ asset('public/' . $news->gambar) }}" alt="{{ $news->judul }}" class="w-full h-48 object-cover" loading="lazy">
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
                        <p class="text-center text-gray-500">Belum ada G News terbaru.</p>
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
                            <img src="{{ asset('public/' . $event->image) }}" loading="lazy" alt="{{ $event->nama_event }}" class="w-full h-48 object-cover">
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

    <section id="khotbah" class="py-2 lg:py-8 bg-purple-light">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 class="text-4xl font-bold text-center text-purple-secondary mb-12">Fitur Ringkas Khotbah</h2>
          @livewire('image-generator')
        </div>
    </section>
    <!-- 7. Livewire Bible Reader -->
    <div id="alkitab" class="py-2 lg:py-8 bg-purple-light">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-bold text-center text-purple-secondary mb-12">Alkitab</h2>
        <!-- Gunakan Alpine.js untuk state management -->
        <div x-data="{ showBibleReader: false }">
            
            <!-- A. Tombol Pemicu (Hanya tampil jika showBibleReader = false) -->
            <div x-show="!showBibleReader" x-transition.opacity
                 class="p-6 border rounded-xl shadow-2xl bg-white max-w-4xl mx-auto my-8 text-center">
                
                <!--<h2 class="text-3xl font-extrabold text-purple-900 mb-6 text-center">📖 ALKITAB ONLINE </h2>-->
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
                        <li><a href="#news" class="text-purple-200 hover:text-white transition">G News</a></li>
                        <li><a href="#event" class="text-purple-200 hover:text-white transition">Event & Kegiatan</a></li>
                        <li><a href="#alkitab" class="text-purple-200 hover:text-white transition">Alkitab</a></li>
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
                            
                        <svg class="w-8 h-8" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <!-- Background merah YouTube -->
                          <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814z" fill="currentColor"/>
                          
                          <!-- Play button putih -->
                          <path d="M9.545 15.568V8.432L15.818 12l-6.273 3.568z" fill="#581c87"/>
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
                &copy; {{ date('Y') }} GBT Galilea Semarang. Developed by : Obed Danny
            </div>
        </div>
    </footer>


    <!-- Swiper.js JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer=""></script>
    <script>
        // Pusher notification - dijalankan setelah load
        window.addEventListener('load', function() {
            if (typeof PusherPushNotifications !== 'undefined') {
                const beamsClient = new PusherPushNotifications.Client({
                    instanceId: '7aae1eaf-1d26-4770-b838-2f67af29bec2',
                });

                beamsClient.start()
                    .then(() => beamsClient.addDeviceInterest('hello'))
                    .then(() => console.log('Successfully registered and subscribed!'))
                    .catch(console.error);
            }
            
            // Service Worker registration
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/service-worker.js').catch(console.error);
            }
        });
    </script>
    

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const newsSwiper = new Swiper('.news-slider', {
                
                // 1. Mode Slider: Tampilkan berapa banyak slide per view
                // Jika Anda ingin menampilkan 3 berita sekaligus
                slidesPerView: 1, // Default di mobile/kecil
                spaceBetween: 24, // Jarak antar card (sama seperti p-6 di Tailwind)
        
                // 2. Pagination (Dot indicators)
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },
        
                // 3. Responsive Breakpoints (Untuk tampilan desktop/tablet)
                // Ini adalah BAGIAN KRUSIAL agar lebih dari 1 card muncul di layar lebar
                breakpoints: {
                    // Ketika lebar layar >= 640px (sm)
                    640: {
                        slidesPerView: 2,
                        spaceBetween: 30,
                    },
                    // Ketika lebar layar >= 1024px (lg)
                    1024: {
                        slidesPerView: 3, // Tampilkan 3 berita sekaligus di desktop
                        spaceBetween: 30,
                    },
                },
                
                // 4. Tambahan (Looping jika perlu)
                // loop: true, // Opsional: Untuk mengulang slider
                
            });
        });
    </script>


    

       

    {{-- Memuat Livewire Scripts dan Tom Select --}}
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js" defer></script>

</body>
</html>

