@extends('admin.layout')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 **sm:grid-cols-2** lg:grid-cols-3 gap-6 mb-8">
    
    <div class="bg-gradient-to-br from-purple-500 to-purple-700 rounded-xl shadow-lg p-6 text-white flex justify-between items-center transform hover:scale-105 transition-all duration-300">
        <div>
            <p class="text-purple-100 text-sm font-medium mb-1">Total Jemaat</p>
            <h3 class="text-4xl font-bold">{{ $totalJemaat }}</h3>
        </div>
        <div class="text-6xl opacity-60">👥</div> 
    </div>

    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl shadow-lg p-6 text-white flex justify-between items-center transform hover:scale-105 transition-all duration-300">
        <div>
            <p class="text-blue-100 text-sm font-medium mb-1">Total Event</p>
            <h3 class="text-4xl font-bold">{{ $totalEvent }}</h3>
        </div>
        <div class="text-6xl opacity-60">📅</div> 
    </div>

    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white flex items-center justify-between transform hover:scale-105 transition-all duration-300">
    
        <!-- Kontainer Kiri (Teks dan Nominal) -->
        <!-- min-w-0 adalah kunci dalam flexbox agar konten panjang bisa di-wrap/kompres -->
        <div class="flex-grow mr-4 min-w-0"> 
            <p class="text-green-100 text-sm font-medium mb-1">Total Persembahan (Bulan Ini)</p>
            
            <!-- Nominal: Menggunakan ukuran font responsif -->
            <h3 class="text-4xl md:text-4xl font-bold break-words">
                Rp {{ number_format($totalPersembahan, 0, ',', '.') }}
            </h3>
        </div>
        
        <!-- Kontainer Kanan (Ikon) -->
        <!-- flex-shrink-0 memastikan ikon selalu mempertahankan ukurannya -->
        <div class="text-4xl md:text-6xl opacity-60 flex-shrink-0">
            💰
        </div> 
    </div>

    {{-- <div class="bg-gradient-to-br from-yellow-500 to-yellow-700 rounded-xl shadow-lg p-6 text-white flex justify-between items-center transform hover:scale-105 transition-all duration-300">
        <div>
            <p class="text-yellow-100 text-sm font-medium mb-1">Kegiatan Mendatang</p>
            <h3 class="text-4xl font-bold">...</h3>
        </div>
        <div class="text-6xl opacity-60">🔔</div> 
    </div> --}}
</div>

<div class="bg-white p-6 rounded-xl shadow-lg">
    <h4 class="text-xl font-semibold text-purple-800 mb-4">Grafik Mingguan</h4>
    <div class="h-64 flex items-center justify-center text-gray-400 border border-dashed rounded-lg">
        [Area Grafik atau Data Lainnya]
    </div>
</div>

@endsection