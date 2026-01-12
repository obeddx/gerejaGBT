<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.event.index', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:150',
            'hari' => 'required|string|max:20',
            'jam' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
        ]);

        $data = $request->all();

        // --- Upload Image ke public/uploads/events ---
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            
            // Debug info
            \Log::info('File upload detected', [
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime' => $file->getMimeType()
            ]);
            
            // Cek apakah folder exists, kalau tidak buat
            $uploadPath = public_path('uploads/events');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
                \Log::info('Created directory: ' . $uploadPath);
            }
            
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            
            try {
                // Pindahkan file ke public/uploads/events
                $file->move($uploadPath, $filename);
                
                // Simpan path relatif ke database
                $data['image'] = 'uploads/events/' . $filename;
                
                \Log::info('File uploaded successfully: ' . $filename);
            } catch (\Exception $e) {
                \Log::error('File upload failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Gagal upload gambar: ' . $e->getMessage());
            }
        } else {
            \Log::warning('No file uploaded');
        }

        Event::create($data);

        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil ditambahkan!');
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_event' => 'required|string|max:150',
            'hari' => 'required|string|max:20',
            'jam' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096'
        ]);

        $data = $request->all();

        // --- Update Image ---
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama jika ada
            if ($event->image && file_exists(public_path($event->image))) {
                unlink(public_path($event->image));
            }
            
            // 2. Upload gambar baru
            $file = $request->file('image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/events'), $filename);
            
            // 3. Simpan path baru
            $data['image'] = 'uploads/events/' . $filename;
        }

        $event->update($data);

        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil diupdate!');
    }

    public function destroy(Event $event)
    {
        // --- Hapus Image dari public ---
        if ($event->image && file_exists(public_path($event->image))) {
            unlink(public_path($event->image));
        }

        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil dihapus!');
    }
}