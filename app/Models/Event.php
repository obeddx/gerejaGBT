<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'event';
    protected $primaryKey = 'id_event';
    
    protected $fillable = [
        'nama_event',
        'hari',
        'jam',
        'image'
    ];

    /**
     * Relasi many to many dengan Persembahan melalui RekapPersembahan
     */
    public function persembahan()
    {
        return $this->belongsToMany(
            Persembahan::class,
            'rekap_persembahan',
            'id_event',
            'id_persembahan'
        )->withPivot('tgl_persembahan')
         ->withTimestamps();
    }

    /**
     * Relasi one to many dengan RekapPersembahan
     */
    public function rekapPersembahan()
    {
        return $this->hasMany(RekapPersembahan::class, 'id_event');
    }
}