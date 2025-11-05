@extends('admin.layout')

@section('title', 'Rekap Persembahan')
@section('page-title', 'Rekap Persembahan')

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

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
    <div>
        <button id="openCreateModalBtn" class="w-full md:w-auto px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 transition duration-300">
            + Tambah Rekap Persembahan
        </button>
    </div>
    
    <div class="bg-gradient-to-br from-green-500 to-green-700 rounded-xl shadow-lg p-6 text-white flex justify-between items-center">
        <div>
            <p class="text-green-100 text-sm font-medium mb-1">Total Nominal (Hasil Filter)</p>
            <h3 class="text-4xl font-bold">Rp {{ number_format($totalNominal, 0, ',', '.') }}</h3>
        </div>
        <div class="text-6xl opacity-60">💰</div> 
    </div>
</div>

<div class="bg-white rounded-xl shadow-lg p-6 mb-6">
    <h4 class="text-lg font-semibold text-purple-800 mb-4">Filter Rekap Persembahan</h4>
    <form action="{{ route('admin.rekap.index') }}" method="GET">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="filter_id_event" class="block text-sm font-medium text-gray-700 mb-1">Event</label>
                <select name="id_event" id="filter_id_event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="">-- Semua Event --</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id_event }}" {{ request('id_event') == $event->id_event ? 'selected' : '' }}>
                            {{ $event->nama_event }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="filter_waktu" class="block text-sm font-medium text-gray-700 mb-1">Rentang Waktu</label>
                <select name="waktu" id="filter_waktu" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                    <option value="" {{ request('waktu') == '' ? 'selected' : '' }}>-- Semua Waktu --</option>
                    <option value="hari_ini" {{ request('waktu') == 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                    <option value="minggu_ini" {{ request('waktu') == 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                    <option value="bulan_ini" {{ request('waktu') == 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="tahun_ini" {{ request('waktu') == 'tahun_ini' ? 'selected' : '' }}>Tahun Ini</option>
                </select>
            </div>

            <div>
                <label for="filter_tanggal_tunggal" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Spesifik</label>
                <input type="date" name="tanggal_tunggal" id="filter_tanggal_tunggal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ request('tanggal_tunggal') }}">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white font-semibold rounded-lg shadow-md hover:bg-purple-700 transition">
                    🔍 Filter
                </button>
                <a href="{{ route('admin.rekap.index') }}" class="flex-1 text-center px-4 py-2 bg-gray-300 text-gray-800 font-semibold rounded-lg hover:bg-gray-400 transition">
                    Reset
                </a>
            </div>
        </div>
    </form>
</div>

<div class="bg-white rounded-xl shadow-lg overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-purple-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">No</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Event</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Jenis Persembahan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-purple-800 uppercase tracking-wider">Nominal</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-purple-800 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($rekap as $index => $item)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $rekap->firstItem() + $index }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($item->tgl_persembahan)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-purple-900">
                            {{ $item->event->nama_event ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $item->persembahan->jenis ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-800">
                            Rp {{ number_format($item->nominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-center">
                            <button
                                class="openEditModalBtn px-4 py-2 bg-yellow-400 text-yellow-900 font-semibold rounded-lg shadow-sm hover:bg-yellow-500 transition text-xs"
                                data-id="{{ $item->id_rekap }}"
                                data-id_event="{{ $item->id_event }}"
                                data-id_persembahan="{{ $item->id_persembahan }}"
                                data-tgl_persembahan="{{ $item->tgl_persembahan ? \Carbon\Carbon::parse($item->tgl_persembahan)->format('Y-m-d') : '' }}"
                                data-nominal="{{ $item->nominal }}"
                                data-url="{{ route('admin.rekap.update', $item->id_rekap) }}"
                            >
                                ✏️ Edit
                            </button>
                            <button
                                class="openDeleteModalBtn ml-2 px-4 py-2 bg-red-500 text-white font-semibold rounded-lg shadow-sm hover:bg-red-600 transition text-xs"
                                data-url="{{ route('admin.rekap.destroy', $item->id_rekap) }}"
                                data-tanggal="{{ \Carbon\Carbon::parse($item->tgl_persembahan)->format('d M Y') }}"
                                data-event="{{ $item->event->nama_event ?? 'N/A' }}"
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
                                <p class="text-lg font-semibold">Tidak ada data rekap yang cocok.</p>
                                <p class="text-sm">Coba ubah filter Anda atau tambahkan data baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-8">
    {{-- Paginasi ini sekarang akan membawa query filter secara otomatis --}}
    {{ $rekap->links('vendor.pagination.tailwind') }}
</div>


<div id="createModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden transition-opacity duration-300">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-lg mx-4 p-8 transform transition-all duration-300 scale-95 opacity-0" id="createModalDialog">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-purple-800">Tambah Rekap Persembahan</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <form action="{{ route('admin.rekap.store') }}" method="POST">
            @csrf
            <div class="space-y-4">
                
                <div>
                    <label for="tgl_persembahan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Persembahan</label>
                    <input type="date" name="tgl_persembahan" id="tgl_persembahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ old('tgl_persembahan', date('Y-m-d')) }}" required>
                </div>

                <div>
                    <label for="id_event" class="block text-sm font-medium text-gray-700 mb-1">Pilih Event</label>
                    <select name="id_event" id="id_event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">-- Pilih Event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id_event }}" {{ old('id_event') == $event->id_event ? 'selected' : '' }}>
                                {{ $event->nama_event }} ({{ $event->hari }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="id_persembahan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Persembahan</label>
                    <select name="id_persembahan" id="id_persembahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">-- Pilih Jenis Persembahan --</option>
                        @foreach ($persembahan as $item)
                            <option value="{{ $item->id_persembahan }}" {{ old('id_persembahan') == $item->id_persembahan ? 'selected' : '' }}>
                                {{ $item->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nominal" class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" id="nominal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" value="{{ old('nominal') }}" required placeholder="Contoh: 100000">
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
            <h3 class="text-2xl font-bold text-purple-800">Edit Rekap Persembahan</h3>
            <button class="closeModalBtn text-gray-500 hover:text-gray-800 text-3xl">&times;</button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                
                <div>
                    <label for="edit_tgl_persembahan" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Persembahan</label>
                    <input type="date" name="tgl_persembahan" id="edit_tgl_persembahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                </div>

                <div>
                    <label for="edit_id_event" class="block text-sm font-medium text-gray-700 mb-1">Pilih Event</label>
                    <select name="id_event" id="edit_id_event" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">-- Pilih Event --</option>
                        @foreach ($events as $event)
                            <option value="{{ $event->id_event }}">
                                {{ $event->nama_event }} ({{ $event->hari }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_id_persembahan" class="block text-sm font-medium text-gray-700 mb-1">Jenis Persembahan</label>
                    <select name="id_persembahan" id="edit_id_persembahan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
                        <option value="">-- Pilih Jenis Persembahan --</option>
                        @foreach ($persembahan as $item)
                            <option value="{{ $item->id_persembahan }}">
                                {{ $item->jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_nominal" class="block text-sm font-medium text-gray-700 mb-1">Nominal (Rp)</label>
                    <input type="number" name="nominal" id="edit_nominal" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500" required>
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
                Rekap persembahan untuk event "<strong id="deleteEvent"></strong>" pada tanggal <strong id="deleteTanggal"></strong> akan dihapus.
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
    document.addEventListener('DOMContentLoaded', function () {
        
        // --- Referensi Elemen ---
        const createModal = document.getElementById('createModal');
        const createModalDialog = document.getElementById('createModalDialog');
        const openCreateModalBtn = document.getElementById('openCreateModalBtn');

        const editModal = document.getElementById('editModal');
        const editModalDialog = document.getElementById('editModalDialog');
        const editForm = document.getElementById('editForm');
        const editEventId = document.getElementById('edit_id_event');
        const editPersembahanId = document.getElementById('edit_id_persembahan');
        const editTglPersembahan = document.getElementById('edit_tgl_persembahan');
        const editNominal = document.getElementById('edit_nominal');
        const openEditModalBtns = document.querySelectorAll('.openEditModalBtn');

        const deleteModal = document.getElementById('deleteModal');
        const deleteModalDialog = document.getElementById('deleteModalDialog');
        const deleteForm = document.getElementById('deleteForm');
        const deleteEvent = document.getElementById('deleteEvent');
        const deleteTanggal = document.getElementById('deleteTanggal');
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
                const eventId = btn.dataset.id_event;
                const persembahanId = btn.dataset.id_persembahan;
                const tgl = btn.dataset.tgl_persembahan;
                const nominal = btn.dataset.nominal;

                // Isi form
                editForm.action = url;
                editTglPersembahan.value = tgl;
                editNominal.value = nominal;
                
                // Set selected untuk dropdown
                editEventId.value = eventId;
                editPersembahanId.value = persembahanId;

                showModal(editModal, editModalDialog);
            });
        });

        // 3. Buka Delete Modal
        openDeleteModalBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const url = btn.dataset.url;
                const eventNama = btn.dataset.event;
                const tanggal = btn.dataset.tanggal;

                deleteForm.action = url;
                deleteEvent.textContent = eventNama;
                deleteTanggal.textContent = tanggal;

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
            @if (old('id_event') || old('id_persembahan') || old('nominal'))
                showModal(createModal, createModalDialog);
            @endif
        @endif

    });
</script>
@endsection