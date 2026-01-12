<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use \Pusher\PushNotifications\PushNotifications;


class NotificationController extends Controller
{
    /**
     * Menampilkan halaman form notifikasi.
     */
    public function create()
    {
        return view('admin.notifikasi.index');
    }

    /**
     * Mengirim notifikasi menggunakan Pusher Beams.
     */
    public function send(Request $request)
    {
        // 1. Validasi input form
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'icon' => 'nullable|url|max:255',
        ]);

        // 2. Ambil kredensial dari file .env
        // PASTIKAN Anda sudah menambahkannya di file .env Anda!
        $instanceId = env('PUSHER_BEAMS_INSTANCE_ID');
        $secretKey = env('PUSHER_BEAMS_SECRET_KEY');

        if (!$instanceId || !$secretKey) {
            return back()->with('error', 'Konfigurasi Pusher Beams (Instance ID / Secret Key) belum diatur di .env');
        }

        try {
            // 3. Inisialisasi Beams Client
            $beamsClient = new \Pusher\PushNotifications\PushNotifications(array(
              "instanceId" => $instanceId,
              "secretKey" => $secretKey,
            ));

            // 4. Siapkan payload notifikasi
            $notificationData = [
                "title" => $request->input('title'),
                "body" => $request->input('body'),
                "deep_link" => "https://gbtgalileachruch.com/", // Ganti dengan deep link Anda
            ];

            // Tambahkan icon jika diisi
            if ($request->filled('icon')) {
                $notificationData['icon'] = $request->input('icon');
            }

            $publishResponse = $beamsClient->publishToInterests(
              array("hello"), // Mengirim ke interest "hello"
              array("web" => array("notification" => $notificationData))
            );

            // 5. Berikan feedback ke user
            if (isset($publishResponse->publishId)) {
                return back()->with('success', 'Notifikasi berhasil dikirim! Publish ID: ' . $publishResponse->publishId);
            } else {
                return back()->with('error', 'Gagal mengirim notifikasi. Respon: ' . json_encode($publishResponse));
            }

        } catch (\Exception $e) {
            // Tangani jika ada error koneksi atau lainnya
            return back()->with('error', 'Terjadi error: ' . $e->getMessage());
        }
    }
}