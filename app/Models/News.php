<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model.
     * * @var string
     */
    protected $table = 'news'; // Laravel biasanya otomatis menanganinya, tapi ini untuk kejelasan

    /**
     * Primary key yang digunakan oleh tabel.
     *
     * @var string
     */
    protected $primaryKey = 'id_news'; // Wajib ada karena Anda tidak menggunakan 'id'

    /**
     * Atribut yang dapat diisi secara massal (mass assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'judul',
        'keterangan',
        'gambar',
        'tanggal',
        'jam',
    ];

    /**
     * Tipe data asli untuk atribut.
     * Berguna untuk auto-casting.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal' => 'date', // Otomatis mengubah 'tanggal' menjadi objek Carbon
    ];
}
