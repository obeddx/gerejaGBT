<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Manajemen Gereja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- 1. TAMBAHKAN ALPINE.JS (Untuk state open/close) -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        purple: {
                            50: '#faf5ff', 100: '#f3e8ff', 200: '#e9d5ff',
                            300: '#d8b4fe', 400: '#c084fc', 500: '#a855f7',
                            600: '#9333ea', 700: '#7e22ce', 800: '#6b21a8', 900: '#581c87',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            color: #7e22ce;
        }
    </style>
</head>
<!-- 2. Tambahkan state x-data ke body -->
<body class="bg-white" x-data="{ sidebarOpen: false }">
    
    <!-- Sidebar -->
    <!-- 3. Tambahkan class transform, transition, dan Alpine.js (:class) -->
    <div class="fixed inset-y-0 left-0 w-64 bg-gradient-to-b from-purple-700 to-purple-900 shadow-2xl z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out"
         :class="{'translate-x-0': sidebarOpen}">
        
        <div class="flex flex-col h-full">
            <!-- Logo dan Tombol Close (X) -->
            <!-- 4. Modifikasi header sidebar untuk tombol Close -->
            <div class="flex items-center justify-between h-20 border-b border-purple-600 px-4">
                <div class="flex items-center">
                    <img src="{{ asset('gbtgalilea.png') }}" 
                         alt="Logo GBT Galilea Semarang" 
                         class="w-8 h-8 object-cover mr-2 rounded-md"> 
                    <span class="font-bold text-xl text-white">GBT GALILEA</span>
                </div>
                <!-- Tombol Close (X) - Hanya tampil di mobile -->
                <button @click.prevent="sidebarOpen = false" class="text-purple-200 hover:text-white lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.dashboard') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">📊</span>
                    <span class="font-medium">Dashboard</span>
                </a>
                <a href="{{ route('admin.news.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.news.*') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">📰</span> <!-- Emoji diubah -->
                    <span class="font-medium">Galilea News</span>
                </a>
                <a href="{{ route('admin.jemaat.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.jemaat.*') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">👥</span>
                    <span class="font-medium">Data Jemaat</span>
                </a>
                <a href="{{ route('admin.event.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.event.*') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">📅</span>
                    <span class="font-medium">Event</span>
                </a>
                <a href="{{ route('admin.persembahan.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.persembahan.*') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">💰</span>
                    <span class="font-medium">Jenis Persembahan</span>
                </a>
                <a href="{{ route('admin.rekap.index') }}" class="flex items-center px-4 py-3 text-white rounded-lg hover:bg-purple-600 transition {{ request()->routeIs('admin.rekap.*') ? 'bg-purple-600' : '' }}">
                    <span class="mr-3">📋</span>
                    <span class="font-medium">Rekap Persembahan</span>
                </a>
            </nav>
            
            <!-- Logout -->
            <div class="px-4 py-4 border-t border-purple-600">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="flex items-center w-full px-4 py-3 text-white rounded-lg hover:bg-red-600 transition">
                        <span class="mr-3">🚪</span>
                        <span class="font-medium">Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 5. Tambahkan Overlay (latar belakang gelap saat sidebar terbuka di mobile) -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"></div>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Header -->
        <header class="bg-white border-b-2 border-purple-100 shadow-sm">
            <div class="flex items-center justify-between px-4 sm:px-8 py-4">
                
                <!-- 6. Tombol Hamburger (Hanya tampil di mobile) -->
                <button @click.prevent="sidebarOpen = true" class="text-purple-700 hover:text-purple-900 lg:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>

                <!-- Judul Halaman -->
                <h2 class="text-2xl font-bold text-purple-800">@yield('page-title')</h2>
                
                <!-- Info Admin -->
                <div class="flex items-center space-x-4">
                    <span class="text-purple-600">👤 Admin</span>
                </div>
            </div>
        </header>

        <!-- Content -->
        <main class="p-4 sm:p-8"> <!-- Padding disesuaikan sedikit untuk mobile -->
            @if(session('success'))
                <div class="mb-6 px-6 py-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded-lg shadow">
                    <p class="font-semibold">✓ {{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 px-6 py-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow">
                    <p class="font-semibold">✗ {{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>



    
