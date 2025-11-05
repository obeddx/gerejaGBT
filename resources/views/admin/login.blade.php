<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Manajemen Gereja</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">
    <div class="max-w-md w-full">
        <!-- Card -->
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-purple-800 px-8 py-10 text-center">
                <div class="text-6xl mb-4">⛪</div>
                <h1 class="text-3xl font-bold text-white mb-2">Manajemen Gereja</h1>
                <p class="text-purple-200">Sistem Informasi Administrasi</p>
            </div>

            <!-- Form -->
            <div class="px-8 py-10">
                @if(session('error'))
                    <div class="mb-6 px-4 py-3 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                        <p class="font-medium">{{ session('error') }}</p>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 px-4 py-3 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                        <p class="font-medium">{{ session('success') }}</p>
                    </div>
                @endif

                <form action="{{ route('admin.login.post') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div>
                        <label for="username" class="block text-sm font-semibold text-purple-800 mb-2">Username</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username"
                            class="w-full px-4 py-3 border-2 border-purple-200 rounded-lg focus:outline-none focus:border-purple-500 text-purple-800 transition"
                            placeholder="Masukkan username"
                            required
                        >
                        @error('username')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-purple-800 mb-2">Password</label>
                        <input 
                            type="password" 
                            name="password" 
                            id="password"
                            class="w-full px-4 py-3 border-2 border-purple-200 rounded-lg focus:outline-none focus:border-purple-500 text-purple-800 transition"
                            placeholder="Masukkan password"
                            required
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 to-purple-800 text-white font-bold py-3 rounded-lg hover:from-purple-700 hover:to-purple-900 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        🔐 Login
                    </button>
                </form>

                {{-- <div class="mt-8 text-center text-sm text-purple-600">
                    <p>Default credentials:</p>
                    <p class="font-semibold">Username: admin | Password: admin123</p>
                </div> --}}
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-white">
            <p class="text-sm opacity-90">© 2025 Sistem Manajemen Gereja. Develop By : Obed Danny.</p>
        </div>
    </div>
</body>
</html>