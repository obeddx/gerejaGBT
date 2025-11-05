@extends('admin.layout')

@section('title', 'Data Persembahan')
@section('page-title', 'Data Persembahan')

@section('content')

@if ($errors->any())
    <div class="mb-6 px-6 py-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded-lg shadow" role="alert">
        <p class="font-bold">Oops! Terjadi kesalahan:</p>
        <ul class="list-disc list-inside mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-6 flex justify-between items-center">
    <button id="openCreateModalBtn" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 transition duration-300">
        + Tambah Data Persembahan
    </button>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-purple-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">No</th>
                    
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Jenis Persembahan</th>
                    
                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-purple-800 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($persembahan as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $persembahan->firstItem() + $index }}</td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-900">{{ $item->jenis }}</td>
                       
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button
                                class="openEditModalBtn px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-lg shadow-sm hover:bg-yellow-500 transition text-xs"
                                data-id="{{ $item->id_persembahan }}"
                                data-jenis="{{ $item->jenis }}"
                                data-url="{{ route('admin.persembahan.update', $item->id_persembahan) }}"
                            >
                                ✏️ Edit
                            </button>
                            <button
                                class="openDeleteModalBtn ml-2 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-sm hover:bg-red-600 transition text-xs"
                                data-url="{{ route('admin.persembahan.destroy', $item->id_persembahan) }}"
                                data-jenis="{{ $item->jenis }}"
                            >
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-2">🤷</span>
                                <p class="text-lg font-semibold">Belum ada data persembahan.</p>
                                <p class="text-sm">Silakan tambahkan data baru menggunakan tombol di atas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    {{ $persembahan->links('vendor.pagination.tailwind') }}
</div>


<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="createModalDialog">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-purple-800">Tambah Jenis Persembahan</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <form action="{{ route('admin.persembahan.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Persembahan</label>
                    <input type="text" name="jenis" id="jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ old('jenis') }}" required placeholder="Contoh: Persembahan Syukur">
                </div>
                
            </div>
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" class="closeModalBtn px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 transition">
                    Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="editModalDialog">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-purple-800">Edit Jenis Persembahan</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_jenis" class="block text-sm font-medium text-gray-700 mb-1">Jenis Persembahan</label>
                    <input type="text" name="jenis" id="edit_jenis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                </div>
               
            </div>
            <div class="mt-8 flex justify-end space-x-4">
                <button type="button" class="closeModalBtn px-6 py-2 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2 bg-yellow-500 text-white font-semibold rounded-lg shadow-md hover:bg-yellow-600 transition">
                    Update Data
                </button>
            </div>
        </form>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalDialog">
        <div class="text-center">
            <span class="text-6xl text-red-500">😟</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-4 mb-2">Anda Yakin?</h3>
            <p class="text-gray-600 mb-6">
                Data persembahan "<strong id="deleteJenis"></strong>" akan dihapus secara permanen.
            </p>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex justify-center space-x-4">
                <button type="button" class="closeModalBtn flex-1 px-6 py-3 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batalkan
                </button>
                <button type="submit" class="flex-1 px-6 py-3 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 transition">
                    Ya, Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Logika JavaScript ini sama dengan modul Jemaat,
    // hanya disesuaikan variabelnya untuk Persembahan
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- Referensi Elemen ---
        const createModal = document.getElementById('createModal');
        const createModalDialog = document.getElementById('createModalDialog');
        const openCreateModalBtn = document.getElementById('openCreateModalBtn');

        const editModal = document.getElementById('editModal');
        const editModalDialog = document.getElementById('editModalDialog');
        const editForm = document.getElementById('editForm');
        const editJenis = document.getElementById('edit_jenis');
        
        const openEditModalBtns = document.querySelectorAll('.openEditModalBtn');

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalDialog = document.getElementById('deleteModalDialog');
        const deleteForm = document.getElementById('deleteForm');
        const deleteJenis = document.getElementById('deleteJenis');
        const openDeleteModalBtns = document.querySelectorAll('.openDeleteModalBtn');

        const closeModalBtns = document.querySelectorAll('.closeModalBtn');

        // --- Fungsi Helper Modal ---
        function showModal(modal, dialog) {
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                dialog.classList.remove('scale-95', 'opacity-0');
            }, 10);
        }

        function hideModal(modal, dialog) {
            dialog.classList.add('scale-95', 'opacity-0');
            modal.classList.add('opacity-0');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }

        // --- Event Listener untuk Buka Modal ---

        // 1. Buka Create Modal
        if (openCreateModalBtn) {
            openCreateModalBtn.addEventListener('click', () => {
                showModal(createModal, createModalDialog);
            });
        }

        // 2. Buka Edit Modal
        openEditModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.dataset.url;
                const jenis = btn.dataset.jenis;
               

                editForm.action = url;
                editJenis.value = jenis;
                

                showModal(editModal, editModalDialog);
            });
        });

        // 3. Buka Delete Modal
        openDeleteModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.dataset.url;
                const jenis = btn.dataset.jenis;

                deleteForm.action = url;
                deleteJenis.textContent = jenis;

                showModal(deleteModal, deleteModalDialog);
            });
        });

        // --- Event Listener untuk Tutup Modal ---
        closeModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const modal = btn.closest('.fixed');
                const dialog = modal.querySelector('[id$="Dialog"]');
                hideModal(modal, dialog);
            });
        });

        [createModal, editModal, deleteModal].forEach(modal => {
            if (modal) {
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        const dialog = modal.querySelector('[id$="Dialog"]');
                        hideModal(modal, dialog);
                    }
                });
            }
        });

        // --- Penanganan Error Validasi (Auto-open modal create jika ada error) ---
        @if ($errors->any())
            @if (old('jenis'))
                showModal(createModal, createModalDialog);
            @endif
        @endif

    });
</script>
@endsection