<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\JemaatController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PersembahanController;
use App\Http\Controllers\RekapPersembahanController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\NotificationController;

// Redirect root ke login
Route::get('/admin', function () {
    return redirect()->route('admin.login');
});
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Route::post('/api/summarize-youtube', [App\Http\Controllers\YouTubeSummarizerController::class, 'summarize']);

// Auth Routes (tanpa middleware)
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.post');

// Admin Routes (dengan middleware)
Route::prefix('admin')->middleware('admin.auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');
    
    // Jemaat Routes
    Route::resource('jemaat', JemaatController::class)
        ->except(['create', 'edit', 'show']) // Hapus rute yang tidak perlu
        ->names([
            'index' => 'admin.jemaat.index',
            // 'create' => 'admin.jemaat.create', // Dihapus
            'store' => 'admin.jemaat.store',
            // 'edit' => 'admin.jemaat.edit', // Dihapus
            'update' => 'admin.jemaat.update',
            'destroy' => 'admin.jemaat.destroy',
            // 'show' => 'admin.jemaat.show', // Dihapus
        ]);
        
    
    // Event Routes
    Route::resource('event', EventController::class)
        ->except(['create', 'edit', 'show']) // Hapus rute yang tidak perlu
        ->names([
            'index' => 'admin.event.index',
            'store' => 'admin.event.store',
            'update' => 'admin.event.update',
            'destroy' => 'admin.event.destroy',
        ]);

    Route::resource('news', NewsController::class)
        ->except(['create', 'edit', 'show']) // Hapus rute yang tidak perlu
        ->names([
            'index' => 'admin.news.index',
            'store' => 'admin.news.store',
            'update' => 'admin.news.update',
            'destroy' => 'admin.news.destroy',
        ]);
    
    // Persembahan Routes
    Route::resource('persembahan', PersembahanController::class)
        ->except(['create', 'edit', 'show']) // Hapus rute yang tidak perlu
        ->names([
            'index' => 'admin.persembahan.index',
            'store' => 'admin.persembahan.store',
            'update' => 'admin.persembahan.update',
            'destroy' => 'admin.persembahan.destroy',
        ]);
    
    // Rekap Persembahan Routes
   Route::resource('rekap', RekapPersembahanController::class)
        ->except(['create', 'edit', 'show']) // Hapus rute yang tidak perlu
        ->names([
            'index' => 'admin.rekap.index',
            'store' => 'admin.rekap.store',
            'update' => 'admin.rekap.update',
            'destroy' => 'admin.rekap.destroy',
        ]);
    // Route untuk menampilkan form
    Route::get('/notifikasi', [NotificationController::class, 'create'])->name('admin.notifikasi.create');
    
    // Route untuk memproses pengiriman form
    Route::post('/notifikasi/send', [NotificationController::class, 'send'])->name('admin.notifikasi.send');
});