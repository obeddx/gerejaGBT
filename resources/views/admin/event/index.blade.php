@extends('admin.layout')

@section('title', 'Data Event')
@section('page-title', 'Data Event')

@section('content')

<!-- Area untuk menampilkan error validasi -->
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

<!-- Tombol Tambah Event -->
<div class="mb-6 flex justify-between items-center">
    <button id="openCreateModalBtn" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 transition duration-300">
        + Tambah Data Event
    </button>
</div>

<!-- Tabel Data Event -->
<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-purple-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Gambar</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Nama Event</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Hari</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Jam</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-purple-800 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($events as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $events->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if ($item->image)
                                <img src="{{ Storage::url($item->image) }}" alt="{{ $item->nama_event }}" class="w-16 h-16 object-cover rounded-lg shadow">
                            @else
                                <span class="text-xs text-gray-400">T/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-900">{{ $item->nama_event }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->hari }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $item->jam }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button
                                class="openEditModalBtn px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-lg shadow-sm hover:bg-yellow-500 transition text-xs"
                                data-id="{{ $item->id_event }}"
                                data-nama_event="{{ $item->nama_event }}"
                                data-hari="{{ $item->hari }}"
                                data-jam="{{ $item->jam }}"
                                data-image_url="{{ $item->image ? Storage::url($item->image) : '' }}"
                                data-url="{{ route('admin.event.update', $item->id_event) }}"
                            >
                                ✏️ Edit
                            </button>
                            <button
                                class="openDeleteModalBtn ml-2 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-sm hover:bg-red-600 transition text-xs"
                                data-url="{{ route('admin.event.destroy', $item->id_event) }}"
                                data-nama_event="{{ $item->nama_event }}"
                            >
                                🗑️ Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <span class="text-4xl mb-2">🤷</span>
                                <p class="text-lg font-semibold">Belum ada data event.</p>
                                <p class="text-sm">Silakan tambahkan data baru menggunakan tombol di atas.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination Links -->
<div class="mt-8">
    {{ $events->links('vendor.pagination.tailwind') }}
</div>


<!-- ====================================================================== -->
<!-- == MODAL SECTION == -->
<!-- ====================================================================== -->

<!-- 1. Create Modal -->
<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="createModalDialog">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-purple-800">Tambah Event Baru</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <!-- PENTING: tambahkan enctype untuk file upload -->
        <form action="{{ route('admin.event.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="nama_event" class="block text-sm font-medium text-gray-700 mb-1">Nama Event</label>
                    <input type="text" name="nama_event" id="nama_event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ old('nama_event') }}" required>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select name="hari" id="hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                            <option value="Senin" {{ old('hari') == 'Senin' ? 'selected' : '' }}>Senin</option>
                            <option value="Selasa" {{ old('hari') == 'Selasa' ? 'selected' : '' }}>Selasa</option>
                            <option value="Rabu" {{ old('hari') == 'Rabu' ? 'selected' : '' }}>Rabu</option>
                            <option value="Kamis" {{ old('hari') == 'Kamis' ? 'selected' : '' }}>Kamis</option>
                            <option value="Jumat" {{ old('hari') == 'Jumat' ? 'selected' : '' }}>Jumat</option>
                            <option value="Sabtu" {{ old('hari') == 'Sabtu' ? 'selected' : '' }}>Sabtu</option>
                            <option value="Minggu" {{ old('hari') == 'Minggu' ? 'selected' : '' }}>Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label for="jam" class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                        <input type="time" name="jam" id="jam" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ old('jam') }}" required>
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1">Gambar (Opsional)</label>
                    <input type="file" name="image" id="image" class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
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

<!-- 2. Edit Modal -->
<div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="editModalDialog">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-purple-800">Edit Data Event</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <!-- PENTING: tambahkan enctype untuk file upload -->
        <form id="editForm" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <div>
                    <label for="edit_nama_event" class="block text-sm font-medium text-gray-700 mb-1">Nama Event</label>
                    <input type="text" name="nama_event" id="edit_nama_event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="edit_hari" class="block text-sm font-medium text-gray-700 mb-1">Hari</label>
                        <select name="hari" id="edit_hari" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                            <option value="Senin">Senin</option>
                            <option value="Selasa">Selasa</option>
                            <option value="Rabu">Rabu</option>
                            <option value="Kamis">Kamis</option>
                            <option value="Jumat">Jumat</option>
                            <option value="Sabtu">Sabtu</option>
                            <option value="Minggu">Minggu</option>
                        </select>
                    </div>
                    <div>
                        <label for="edit_jam" class="block text-sm font-medium text-gray-700 mb-1">Jam</label>
                        <input type="time" name="jam" id="edit_jam" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Saat Ini</label>
                    <img id="currentImage" src="" alt="Gambar Event" class="w-24 h-24 object-cover rounded-lg shadow mb-2 hidden">
                    <span id="noCurrentImage" class="text-sm text-gray-400 hidden">Tidak ada gambar.</span>
                </div>

                <div>
                    <label for="edit_image" class="block text-sm font-medium text-gray-700 mb-1">Ubah Gambar (Opsional)</label>
                    <input type="file" name="image" id="edit_image" class="w-full px-3 py-2 border border-gray-300 rounded-lg file:mr-3 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah gambar.</p>
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

<!-- 3. Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="deleteModalDialog">
        <div class="text-center">
            <span class="text-6xl text-red-500">😟</span>
            <h3 class="text-2xl font-bold text-gray-800 mt-4 mb-2">Anda Yakin?</h3>
            <p class="text-gray-600 mb-6">
                Data event "<strong id="deleteNamaEvent"></strong>" akan dihapus secara permanen.
            </p>
        </div>
        <!-- Form action akan di-set oleh JavaScript -->
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

<!-- ====================================================================== -->
<!-- == JAVASCRIPT SECTION == -->
<!-- ====================================================================== -->

<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- Referensi Elemen ---
        const createModal = document.getElementById('createModal');
        const createModalDialog = document.getElementById('createModalDialog');
        const openCreateModalBtn = document.getElementById('openCreateModalBtn');

        const editModal = document.getElementById('editModal');
        const editModalDialog = document.getElementById('editModalDialog');
        const editForm = document.getElementById('editForm');
        const editNamaEvent = document.getElementById('edit_nama_event');
        const editHari = document.getElementById('edit_hari');
        const editJam = document.getElementById('edit_jam');
        const currentImage = document.getElementById('currentImage');
        const noCurrentImage = document.getElementById('noCurrentImage');
        const openEditModalBtns = document.querySelectorAll('.openEditModalBtn');

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalDialog = document.getElementById('deleteModalDialog');
        const deleteForm = document.getElementById('deleteForm');
        const deleteNamaEvent = document.getElementById('deleteNamaEvent');
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
                const namaEvent = btn.dataset.nama_event;
                const hari = btn.dataset.hari;
                const jam = btn.dataset.jam;
                const imageUrl = btn.dataset.image_url;

                // Isi form
                editForm.action = url;
                editNamaEvent.value = namaEvent;
                editJam.value = jam;
                
                // Set selected untuk Hari
                Array.from(editHari.options).forEach(option => {
                    option.selected = (option.value === hari);
                });

                // Tampilkan gambar saat ini
                if (imageUrl) {
                    currentImage.src = imageUrl;
                    currentImage.classList.remove('hidden');
                    noCurrentImage.classList.add('hidden');
                } else {
                    currentImage.classList.add('hidden');
                    noCurrentImage.classList.remove('hidden');
                }

                // Bersihkan input file
                document.getElementById('edit_image').value = '';

                showModal(editModal, editModalDialog);
            });
        });

        // 3. Buka Delete Modal
        openDeleteModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.dataset.url;
                const namaEvent = btn.dataset.nama_event;

                deleteForm.action = url;
                deleteNamaEvent.textContent = namaEvent;

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
        // Catatan: Jika validasi GAGAL, file gambar harus dipilih Ulang.
        // Ini adalah perilaku standar browser untuk form non-AJAX.
        @if ($errors->any())
            @if (old('nama_event') || old('hari') || old('jam'))
                showModal(createModal, createModalDialog);
            @endif

            // Menangani error update lebih sulit tanpa AJAX
            // karena kita tidak tahu ID event mana yang error.
            // Saat ini, error update akan ditampilkan di atas tabel.
        @endif

    });
</script>
@endsection
