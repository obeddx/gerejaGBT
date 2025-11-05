<?php

namespace App\Http\Controllers;

use App\Models\Jemaat;
use Illuminate\Http\Request;

class JemaatController extends Controller
{
    /**
     * Menampilkan daftar jemaat.
     */
    public function index()
    {
        $jemaat = Jemaat::latest()->paginate(10);
        // Halaman index sekarang juga bertanggung jawab untuk
        // menampilkan modal (yang sudah ada di file blade)
        return view('admin.jemaat.index', compact('jemaat'));
    }

    /**
     * Metode create() tidak lagi diperlukan
     * karena form-nya ada di modal di halaman index.
     */
    // public function create()
    // {
    //     return view('admin.jemaat.create');
    // }

    /**
     * Menyimpan data jemaat baru dari modal.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string'
        ]);

        Jemaat::create($request->all());

        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.jemaat.index')->with('success', 'Data jemaat berhasil ditambahkan!');
    }

    /**
     * Metode edit() tidak lagi diperlukan
     * karena form-nya ada di modal di halaman index.
     * Data untuk modal edit diambil via data-attributes di tombol edit.
     */
    // public function edit(Jemaat $jemaat)
    // {
    //     return view('admin.jemaat.edit', compact('jemaat'));
    // }

    /**
     * Mengupdate data jemaat dari modal.
     */
    public function update(Request $request, Jemaat $jemaat)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string'
        ]);

        $jemaat->update($request->all());

        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.jemaat.index')->with('success', 'Data jemaat berhasil diupdate!');
    }

    /**
     * Menghapus data jemaat dari modal konfirmasi.
     */
    public function destroy(Jemaat $jemaat)
    {
        $jemaat->delete();
        // Redirect kembali ke index dengan pesan sukses
        return redirect()->route('admin.jemaat.index')->with('success', 'Data jemaat berhasil dihapus!');
    }
}
