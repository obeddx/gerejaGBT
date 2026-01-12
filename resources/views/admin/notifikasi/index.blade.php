@extends('admin.layout')
@section('title', 'Kirim Notifikasi')
@section('page-title', 'Kirim Notifikasi')
@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-purple-50 py-8 px-4">
        <div class="max-w-2xl mx-auto">
            {{-- Menampilkan pesan sukses --}}
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-md animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-green-700 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            {{-- Menampilkan pesan error --}}
            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-md animate-fade-in">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-red-700 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- Menampilkan error validasi --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-md animate-fade-in">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-red-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="flex-1">
                            <p class="text-red-700 font-semibold mb-2">Terdapat beberapa kesalahan:</p>
                            <ul class="list-disc list-inside text-red-600 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Card Form --}}
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transform transition-all duration-300 hover:shadow-3xl">
                {{-- Header Card --}}
                <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-6">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <h4 class="text-2xl font-bold text-white">Formulir Notifikasi</h4>
                    </div>
                    <p class="text-purple-100 mt-2">Kirim notifikasi push ke pengguna aplikasi</p>
                </div>

                {{-- Body Card --}}
                <div class="px-8 py-8">
                    <form action="{{ route('admin.notifikasi.send') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        {{-- Title Field --}}
                        <div class="form-group">
                            <label for="title" class="block text-purple-800 font-semibold mb-2 text-sm uppercase tracking-wide">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Judul Notifikasi
                                </span>
                            </label>
                            <input 
                                type="text" 
                                id="title" 
                                name="title" 
                                class="w-full px-4 py-3 border-2 border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-purple-900 placeholder-purple-300" 
                                value="{{ old('title') }}" 
                                placeholder="Masukkan judul notifikasi..."
                                required>
                        </div>

                        {{-- Body Field --}}
                        <div class="form-group">
                            <label for="body" class="block text-purple-800 font-semibold mb-2 text-sm uppercase tracking-wide">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Isi Pesan
                                </span>
                            </label>
                            <textarea 
                                id="body" 
                                name="body" 
                                rows="5" 
                                class="w-full px-4 py-3 border-2 border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-purple-900 placeholder-purple-300 resize-none" 
                                placeholder="Tulis isi notifikasi di sini..."
                                required>{{ old('body') }}</textarea>
                        </div>

                        {{-- Icon Field --}}
                        <div class="form-group">
                            <label for="icon" class="block text-purple-800 font-semibold mb-2 text-sm uppercase tracking-wide">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    URL Icon
                                </span>
                            </label>
                            <input 
                                type="text" 
                                id="icon" 
                                name="icon" 
                                class="w-full px-4 py-3 border-2 border-purple-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 text-purple-900 placeholder-purple-300" 
                                value="{{ old('icon', asset('favicon.svg')) }}" 
                                placeholder="https://example.com/icon.png">
                            <p class="mt-2 text-sm text-purple-600 flex items-start">
                                <svg class="w-4 h-4 mr-1 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Defaultnya adalah icon 'gbtgalilea.png' dari folder public
                            </p>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4">
                            <button 
                                type="submit" 
                                class="w-full bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white font-bold py-4 px-6 rounded-lg shadow-lg transform transition-all duration-300 hover:scale-105 hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-purple-300 flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                Kirim Notifikasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Custom Styles --}}
    <style>
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }
        
        .shadow-3xl {
            box-shadow: 0 25px 50px -12px rgba(124, 58, 237, 0.25);
        }
    </style>
@endsection