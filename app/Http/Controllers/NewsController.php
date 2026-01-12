<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index()
    {
        $newss = News::latest()->paginate(10);
        return view('admin.news.index', compact('newss'));
    }

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

        // --- Upload Gambar ke public/uploads/news ---
        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            
            // Cek apakah folder exists, kalau tidak buat
            $uploadPath = public_path('uploads/news');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            try {
                // Pindahkan file ke public/uploads/news
                $file->move($uploadPath, $filename);
                
                // Simpan path relatif ke database
                $data['gambar'] = 'uploads/news/' . $filename;
            } catch (\Exception $e) {
                \Log::error('File upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload gambar: ' . $e->getMessage());
            }
        }

        News::create($data);

        return redirect()->route('admin.news.index')->with('success', 'Data news berhasil ditambahkan!');
    }

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

        // --- Update Gambar ---
        if ($request->hasFile('gambar')) {
            // 1. Hapus gambar lama jika ada
            if ($news->gambar && file_exists(public_path($news->gambar))) {
                unlink(public_path($news->gambar));
            }
            
            // 2. Upload gambar baru
            $file = $request->file('gambar');
            
            // Cek apakah folder exists, kalau tidak buat
            $uploadPath = public_path('uploads/news');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }
            
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            try {
                $file->move($uploadPath, $filename);
                
                // 3. Simpan path baru
                $data['gambar'] = 'uploads/news/' . $filename;
            } catch (\Exception $e) {
                \Log::error('File upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload gambar: ' . $e->getMessage());
            }
        }

        $news->update($data);

        return redirect()->route('admin.news.index')->with('success', 'Data News berhasil diupdate!');
    }

    public function destroy(News $news)
    {
        // --- Hapus Gambar dari public ---
        if ($news->gambar && file_exists(public_path($news->gambar))) {
            unlink(public_path($news->gambar));
        }

        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Data news berhasil dihapus!');
    }
}