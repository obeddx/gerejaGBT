<div class="w-full min-h-screen bg-white-50 py-4 px-3 sm:px-6 lg:px-8">
    <div class="max-w-5xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 lg:p-8">
            <!-- Header -->
            <div class="mb-6 sm:mb-8 text-center">
                <div class="inline-block p-2 sm:p-3 bg-purple-100 rounded-full mb-3 sm:mb-4">
                    <svg class="w-8 h-8 sm:w-10 lg:w-12 sm:h-10 lg:h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-800 mb-2 px-2">
                    Ringkasan Khotbah AI
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-gray-600 px-2">
                    Upload file PDF khotbah untuk mendapatkan ringkasan yang mudah dipahami
                </p>
            </div>

            <!-- Form Upload -->
            <div class="mb-4 sm:mb-6">
                <label class="block text-xs sm:text-sm font-semibold text-gray-700 mb-2 sm:mb-3">
                    📄 Upload File Khotbah (PDF)
                </label>
                
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-48 sm:h-56 lg:h-64 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gradient-to-br from-gray-50 to-blue-50 hover:from-blue-50 hover:to-purple-50 transition-all duration-300">
                        <div class="flex flex-col items-center justify-center px-4 py-4 sm:py-5 lg:py-6">
                            @if ($file)
                                <div class="bg-green-100 rounded-full p-3 sm:p-4 mb-3 sm:mb-4">
                                    <svg class="w-8 h-8 sm:w-10 lg:w-12 sm:h-10 lg:h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="mb-2 text-base sm:text-lg text-green-700 font-bold text-center">
                                    ✓ File Berhasil Diupload
                                </p>
                                <p class="text-xs sm:text-sm text-gray-700 font-semibold text-center break-all px-2">
                                    {{ $file->getClientOriginalName() }}
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    Ukuran: {{ number_format($file->getSize() / 1024, 2) }} KB
                                </p>
                            @else
                                <svg class="w-10 h-10 mb-3 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="mb-2 text-sm sm:text-base text-gray-700 text-center px-2">
                                    <span class="font-bold text-purple-600">Klik untuk upload</span> atau drag and drop
                                </p>
                                <p class="text-xs sm:text-sm text-gray-500 text-center">
                                    File PDF (Maksimal 10MB)
                                </p>
                            @endif
                        </div>
                        <input 
                            type="file" 
                            wire:model="file" 
                            class="hidden" 
                            accept=".pdf"
                        />
                    </label>
                </div>

                @error('file')
                    <p class="mt-2 sm:mt-3 text-xs sm:text-sm text-red-600 font-medium">⚠️ {{ $message }}</p>
                @enderror

                <!-- Progress Bar saat Upload -->
                @if ($uploadProgress > 0 && $uploadProgress < 100)
                    <div class="mt-3 sm:mt-4">
                        <div class="w-full bg-gray-200 rounded-full h-2.5 sm:h-3 shadow-inner">
                            <div class="bg-gradient-to-r from-purple-500 to-blue-500 h-2.5 sm:h-3 rounded-full transition-all duration-300 shadow-lg" style="width: {{ $uploadProgress }}%"></div>
                        </div>
                        <p class="text-xs sm:text-sm text-gray-600 mt-1 sm:mt-2 text-center font-medium">Uploading... {{ $uploadProgress }}%</p>
                    </div>
                @endif
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 mb-4 sm:mb-6">
                <button 
                    wire:click="summarize" 
                    wire:loading.attr="disabled"
                    @if(!$file) disabled @endif
                    class="w-full sm:flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 disabled:from-gray-400 disabled:to-gray-400 disabled:cursor-not-allowed text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
                >
                    <span wire:loading.remove wire:target="summarize" class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        <span class="truncate">Ringkas Khotbah Sekarang</span>
                    </span>
                    <span wire:loading wire:target="summarize" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 sm:h-5 sm:w-5 text-white flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Sedang Memproses...
                    </span>
                </button>

                @if($file || $summary)
                  <button
                        wire:click="resetForm"
                         class="w-full sm:flex-1 bg-gray-500 text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        <span class="truncate">Reset</span>
                    </button>
                @endif
            </div>

            <!-- Error Message -->
            @if($error)
                <div class="mb-4 sm:mb-6 bg-red-50 border-l-4 border-red-500 p-4 sm:p-5 rounded-lg shadow-md animate-shake">
                    <div class="flex items-start">
                        <div class="bg-red-100 rounded-full p-1.5 sm:p-2 mr-3 sm:mr-4 flex-shrink-0">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm sm:text-base font-bold text-red-800 mb-1">Terjadi Kesalahan</h3>
                            <p class="text-xs sm:text-sm text-red-700 break-words">{{ $error }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Loading State -->
            <div wire:loading wire:target="summarize" class="mb-4 sm:mb-6">
                <div class="bg-gradient-to-r from-purple-50 to-blue-50 border-l-4 border-purple-500 p-4 sm:p-5 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <svg class="animate-spin h-6 w-6 sm:h-8 sm:w-8 text-purple-600 mr-3 sm:mr-4 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-purple-800 font-bold text-sm sm:text-base">AI sedang bekerja...</p>
                            <p class="text-purple-600 text-xs sm:text-sm mt-1">Mengekstrak teks dan membuat ringkasan khotbah untuk Anda</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Result -->
            @if($summary)
                <div class="bg-gradient-to-br from-purple-50 via-blue-50 to-indigo-50 rounded-xl sm:rounded-2xl p-4 sm:p-6 lg:p-8 border-2 border-purple-200 shadow-2xl animate-fade-in">
                    <!-- Header Section -->
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6 pb-3 sm:pb-4 border-b-2 border-purple-200">
                        <h2 class="text-lg sm:text-xl lg:text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-blue-600 flex items-center gap-2 sm:gap-3">
                            <span class="bg-purple-100 p-1.5 sm:p-2 rounded-lg flex-shrink-0">📝</span>
                            <span>Ringkasan Khotbah</span>
                        </h2>
                        <button 
                            wire:click="downloadSummary"
                            class="w-full sm:w-auto bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-bold py-2.5 sm:py-3 px-4 sm:px-5 rounded-lg sm:rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"
                        >
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </button>
                    </div>

                    @if($fileName)
                        <div class="mb-4 sm:mb-6 bg-white/50 rounded-lg p-3 sm:p-4 border border-purple-200">
                            <p class="text-xs sm:text-sm text-gray-600 flex flex-wrap items-center gap-2">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <span class="font-semibold text-purple-700 flex-shrink-0">Sumber:</span>
                                <span class="text-gray-700 break-all">{{ $fileName }}</span>
                            </p>
                        </div>
                    @endif

                    <!-- Summary Content with Better Formatting -->
                    <div class="bg-white rounded-lg sm:rounded-xl p-4 sm:p-6 lg:p-8 shadow-lg overflow-hidden" id="summary-content">
                        <div class="prose prose-sm sm:prose-base lg:prose-lg max-w-none break-words">
                            {!! $this->formatSummary($summary) !!}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <!--<div class="mt-4 sm:mt-6 flex flex-col sm:flex-row gap-2 sm:gap-3">-->
                    <!--    <button -->
                    <!--        onclick="copySummary()"-->
                    <!--        class="w-full sm:flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 sm:py-3 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2 text-sm sm:text-base"-->
                    <!--    >-->
                    <!--        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">-->
                    <!--            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>-->
                    <!--        </svg>-->
                    <!--        Salin Teks-->
                    <!--    </button>-->
                    <!--</div>-->
                </div>
            @endif

            <!-- Info Footer -->
            <div class="mt-6 sm:mt-8 text-center">
                <div class="inline-block bg-yellow-50 border border-yellow-200 rounded-lg p-3 sm:p-4 max-w-full">
                    <p class="text-xs sm:text-sm text-yellow-800 flex flex-wrap items-center gap-2 justify-center">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold flex-shrink-0">Tips:</span>
                        <span class="break-words">Pastikan file PDF berisi teks yang dapat dibaca</span>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes fade-in {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fade-in 0.5s ease-out;
    }

    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-10px); }
        75% { transform: translateX(10px); }
    }

    .animate-shake {
        animation: shake 0.5s ease-in-out;
    }

    /* Custom Prose Styling - Responsive */
    .prose h1, .prose h2, .prose h3 {
        color: #7c3aed;
        font-weight: 700;
        margin-top: 1.2em;
        margin-bottom: 0.5em;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .prose strong {
        color: #7c3aed;
        font-weight: 700;
        word-wrap: break-word;
    }

    .prose ul, .prose ol {
        margin-top: 0.8em;
        margin-bottom: 0.8em;
        padding-left: 1.5em;
    }

    .prose li {
        margin-top: 0.4em;
        margin-bottom: 0.4em;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .prose p {
        line-height: 1.7;
        margin-bottom: 0.8em;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    .prose blockquote {
        border-left: 4px solid #7c3aed;
        padding-left: 1em;
        font-style: italic;
        color: #4b5563;
        background: #f3f4f6;
        padding: 0.8em 1em;
        border-radius: 0.5em;
        margin: 1em 0;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Mobile specific improvements */
    @media (max-width: 640px) {
        .prose {
            font-size: 0.9rem;
        }
        
        .prose h1 {
            font-size: 1.5rem;
        }
        
        .prose h2 {
            font-size: 1.3rem;
        }
        
        .prose h3 {
            font-size: 1.1rem;
        }

        .prose ul, .prose ol {
            padding-left: 1.2em;
        }
    }

    /* Ensure no horizontal overflow */
    * {
        max-width: 100%;
    }

    /* Better text wrapping for long words */
    .break-words {
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        hyphens: auto;
    }
</style>
@endpush

@push('scripts')
<script>
    // Copy summary to clipboard
    function copySummary() {
        const summaryText = document.querySelector('#summary-content').innerText;
        navigator.clipboard.writeText(summaryText).then(() => {
            // Alert dengan style yang lebih baik untuk mobile
            if (window.innerWidth < 640) {
                alert('✓ Berhasil disalin!');
            } else {
                alert('✓ Ringkasan berhasil disalin ke clipboard!');
            }
        }).catch(err => {
            alert('Gagal menyalin. Silakan pilih teks secara manual.');
        });
    }

    // Update progress bar saat upload
    Livewire.on('upload-progress', (progress) => {
        @this.uploadProgress = progress;
    });
</script>
@endpush