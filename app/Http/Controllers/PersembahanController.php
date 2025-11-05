<?php

namespace App\Http\Controllers;

use App\Models\Persembahan;
use Illuminate\Http\Request;

class PersembahanController extends Controller
{
    /**
     * Menampilkan daftar persembahan (halaman utama).
     */
    public function index()
    {
        $persembahan = Persembahan::latest()->paginate(10);
        return view('admin.persembahan.index', compact('persembahan'));
    }

    /**
     * Metode create() tidak lagi diperlukan
     * karena form-nya ada di modal di halaman index.
     */
    // public function create()
    // {
    //     return view('admin.persembahan.create');
    // }

    /**
     * Menyimpan data persembahan baru dari modal.
     */
    public function store(Request $request)
    {
        // Saya tambahkan validasi untuk tanggal dan jumlah
        $request->validate([
            'jenis' => 'required|string|max:100',
            
        ]);

        Persembahan::create($request->all());

        return redirect()->route('admin.persembahan.index')->with('success', 'Data persembahan berhasil ditambahkan!');
    }

    /**
     * Metode edit() tidak lagi diperlukan
     * karena form-nya ada di modal di halaman index.
     */
    // public function edit(Persembahan $persembahan)
    // {
    //     return view('admin.persembahan.edit', compact('persembahan'));
    // }

    /**
     * Mengupdate data persembahan dari modal.
     */
    public function update(Request $request, Persembahan $persembahan)
    {
        // Saya tambahkan validasi untuk tanggal dan jumlah
        $request->validate([
            'jenis' => 'required|string|max:100',
            
        ]);

        $persembahan->update($request->all());

        return redirect()->route('admin.persembahan.index')->with('success', 'Data persembahan berhasil diupdate!');
    }

    /**
     * Menghapus data persembahan dari modal konfirmasi.
     */
    public function destroy(Persembahan $persembahan)
    {
        $persembahan->delete();
        return redirect()->route('admin.persembahan.index')->with('success', 'Data persembahan berhasil dihapus!');
    }
}