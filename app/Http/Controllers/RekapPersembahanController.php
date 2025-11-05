<?php

namespace App\Http\Controllers;

use App\Models\RekapPersembahan;
use App\Models\Event;
use App\Models\Persembahan;
use Illuminate\Http\Request; // <-- Pastikan Request di-import
use Carbon\Carbon; // <-- Import Carbon untuk filter waktu

class RekapPersembahanController extends Controller
{
    /**
     * Menampilkan daftar rekap (dengan filter) DAN memuat data untuk modal.
     */
    public function index(Request $request) // <-- Tambahkan Request $request
    {
        // Mulai query builder
        $query = RekapPersembahan::with(['event', 'persembahan']);

        // --- 1. Filter berdasarkan Event ---
        if ($request->filled('id_event')) {
            $query->where('id_event', $request->id_event);
        }

        // --- 2. Filter berdasarkan Waktu (Hari ini, Minggu ini, dll.) ---
        if ($request->filled('waktu')) {
            $waktu = $request->waktu;
            if ($waktu == 'hari_ini') {
                $query->whereDate('tgl_persembahan', Carbon::today());
            } elseif ($waktu == 'minggu_ini') {
                $query->whereBetween('tgl_persembahan', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
            } elseif ($waktu == 'bulan_ini') {
                $query->whereBetween('tgl_persembahan', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()]);
            } elseif ($waktu == 'tahun_ini') {
                $query->whereYear('tgl_persembahan', Carbon::now()->year);
            }
        }

        // --- 3. Filter berdasarkan Tanggal Tunggal ---
        // Filter ini akan menimpa filter 'waktu' jika diisi
        if ($request->filled('tanggal_tunggal')) {
            $query->whereDate('tgl_persembahan', $request->tanggal_tunggal);
        }

        // --- Hitung Total Nominal DARI HASIL FILTER ---
        // Kita clone query agar perhitungan sum tidak mengganggu paginasi
        $totalNominal = $query->clone()->sum('nominal');

        // --- Ambil Data & Paginasi ---
        // Tambahkan latest() dan appends() agar paginasi tetap membawa filter
        $rekap = $query->latest()->paginate(10)->appends($request->query());
        
        // Ambil data untuk dropdown di modal dan filter
        $events = Event::all();
        $persembahan = Persembahan::all();
        
        // Kirim semua data ke view index
        return view('admin.rekap.index', compact('rekap', 'events', 'persembahan', 'totalNominal'));
    }


    /**
     * Menyimpan data rekap baru dari modal.
     * (Validasi ini saya biarkan sesuai kode asli Anda)
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_event' => 'required|exists:event,id_event',
            'id_persembahan' => 'required|exists:persembahan,id_persembahan',
            'nominal' => 'required|numeric|min:0',
            'tgl_persembahan' => 'required|date'
        ]);

        RekapPersembahan::create($request->all());

        return redirect()->route('admin.rekap.index')->with('success', 'Data rekap persembahan berhasil ditambahkan!');
    }

    /**
     * Mengupdate data rekap dari modal.
     * (Validasi ini saya biarkan sesuai kode asli Anda)
     */
    public function update(Request $request, RekapPersembahan $rekap)
    {
        $request->validate([
            'id_event' => 'required|exists:event,id_event',
            'id_persembahan' => 'required|exists:persembahan,id_persembahan',
            'nominal' => 'required|numeric|min:0',
            'tgl_persembahan' => 'required|date'
        ]);

        $rekap->update($request->all());

        return redirect()->route('admin.rekap.index')->with('success', 'Data rekap persembahan berhasil diupdate!');
    }

    /**
     * Menghapus data rekap.
     */
    public function destroy(RekapPersembahan $rekap)
    {
        $rekap->delete();
        return redirect()->route('admin.rekap.index')->with('success', 'Data rekap persembahan berhasil dihapus!');
    }
}