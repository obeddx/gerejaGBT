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
        // Ambil semua event, diurutkan dari yang terbaru
        $events = Event::latest()->get();
        $newss = News::oldest()->take(6)->get(); // Ambil 6 berita terbaru
        
        // ----------------------------------------------------
        // LOGIKA BARU UNTUK LCP PRELOAD
        // ----------------------------------------------------
        $lcpImageUrl = null;

        // Cari event terbaru PERTAMA yang memiliki gambar (LCP Element)
        foreach ($events as $event) {
            if (!empty($event->image)) {
                // Buat path asset lengkap
                $lcpImageUrl = asset('public/' . $event->image);
                
                // Asumsi LCP adalah event pertama yang memiliki gambar, hentikan loop
                break; 
            }
        }
        
        // Kirim data events, newss, dan URL LCP ke view 'welcome'
        return view('welcome', compact('events', 'newss', 'lcpImageUrl'));
    }
}