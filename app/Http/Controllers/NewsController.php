<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN IMPORT INI

class NewsController extends Controller
{
    public function index()
    {
        $newss = News::latest()->paginate(10);
        return view('admin.news.index', compact('newss'));
    }

    /**
     * Metode create() tidak diperlukan lagi (pakai modal)
     */
    // public function create() ...

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'keterangan' => 'required|string|max:350',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable',
            
        ]);

        $data = $request->all();

        // --- Logika Handle Upload Image ---
        if ($request->hasFile('gambar')) {
            $path = $request->file('gambar')->store('public/news');
            $data['gambar'] = $path; // Simpan path-nya ke data
        }
        // --- End Logika ---

        News::create($data); // Buat event dengan data yang sudah ada path gambarnya

        return redirect()->route('admin.news.index')->with('success', 'Data news berhasil ditambahkan!');
    }

    /**
     * Metode edit() tidak diperlukan lagi (pakai modal)
     */
    // public function edit(Event $event) ...

    public function update(Request $request, News $news)
    {
        $request->validate([
            'judul' => 'required|string|max:150',
            'keterangan' => 'required|string|max:350',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tanggal' => 'nullable|date',
            'jam' => 'nullable',
        ]);

        $data = $request->all();

        // --- Logika Handle Update Image ---
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($news->gambar) {
                Storage::delete($news->gambar);
            }
            
            // 2. Upload gambar baru
            $path = $request->file('gambar')->store('public/news');
            $data['gambar'] = $path; // Simpan path baru ke data
        }
        // --- End Logika ---

        $news->update($data); // Update event

        return redirect()->route('admin.news.index')->with('success', 'Data News berhasil diupdate!');
    }

    public function destroy(News $news)
    {
        // --- Logika Hapus Image ---
        if ($news->gambar) {
            Storage::delete($news->gambar);
        }
        // --- End Logika ---

        $news->delete();
        return redirect()->route('admin.news.index')->with('success', 'Data news berhasil dihapus!');
    }
}
