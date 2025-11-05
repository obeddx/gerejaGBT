<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- TAMBAHKAN IMPORT INI

class EventController extends Controller
{
    public function index()
    {
        $events = Event::latest()->paginate(10);
        return view('admin.event.index', compact('events'));
    }

    /**
     * Metode create() tidak diperlukan lagi (pakai modal)
     */
    // public function create() ...

    public function store(Request $request)
    {
        $request->validate([
            'nama_event' => 'required|string|max:150',
            'hari' => 'required|string|max:20',
            'jam' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096'
        ]);

        $data = $request->all();

        // --- Logika Handle Upload Image ---
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/events');
            $data['image'] = $path; // Simpan path-nya ke data
        }
        // --- End Logika ---

        Event::create($data); // Buat event dengan data yang sudah ada path gambarnya

        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil ditambahkan!');
    }

    /**
     * Metode edit() tidak diperlukan lagi (pakai modal)
     */
    // public function edit(Event $event) ...

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'nama_event' => 'required|string|max:150',
            'hari' => 'required|string|max:20',
            'jam' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096' // image boleh null
        ]);

        $data = $request->all();

        // --- Logika Handle Update Image ---
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama jika ada
            if ($event->image) {
                Storage::delete($event->image);
            }
            
            // 2. Upload gambar baru
            $path = $request->file('image')->store('public/events');
            $data['image'] = $path; // Simpan path baru ke data
        }
        // --- End Logika ---

        $event->update($data); // Update event

        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil diupdate!');
    }

    public function destroy(Event $event)
    {
        // --- Logika Hapus Image ---
        if ($event->image) {
            Storage::delete($event->image);
        }
        // --- End Logika ---

        $event->delete();
        return redirect()->route('admin.event.index')->with('success', 'Data event berhasil dihapus!');
    }
}
