<?php

namespace App\Http\Controllers;

use App\Models\Jemaat;
use App\Models\Event;
use App\Models\Persembahan;
use App\Models\RekapPersembahan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalJemaat = Jemaat::count();
        $totalEvent = Event::count();
        $totalPersembahan = RekapPersembahan::sum('nominal');
        $totalRekap = RekapPersembahan::count();

        return view('admin.dashboard', compact('totalJemaat', 'totalEvent', 'totalPersembahan', 'totalRekap'));
    }
}