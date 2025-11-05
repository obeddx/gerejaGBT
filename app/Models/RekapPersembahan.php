<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekapPersembahan extends Model
{
    use HasFactory;

    protected $table = 'rekap_persembahan';
    protected $primaryKey = 'id_rekap';
    
    protected $fillable = [
        'id_event',
        'id_persembahan',
        'tgl_persembahan',
        'nominal'
    ];

    protected $casts = [
        'tgl_persembahan' => 'date',
        'nominal' => 'decimal:2'
    ];

    /**
     * Relasi dengan Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class, 'id_event');
    }

    /**
     * Relasi dengan Persembahan
     */
    public function persembahan()
    {
        return $this->belongsTo(Persembahan::class, 'id_persembahan');
    }
}