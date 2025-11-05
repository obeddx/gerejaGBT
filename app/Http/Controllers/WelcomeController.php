<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\News;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    /**
     * Menampilkan halaman welcome utama.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        // Ambil semua event, utamakan yang terbaru
        // Kita juga bisa filter hanya yang punya gambar jika slider adalah prioritas
        $events = Event::latest()->get();
        $newss = News::latest()->take(6)->get(); // Ambil 6 berita terbaru
        
        // Kirim data events ke view 'welcome'
        return view('welcome', compact('events', 'newss'));
    }
}
