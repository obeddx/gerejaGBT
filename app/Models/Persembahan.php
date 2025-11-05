<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persembahan extends Model
{
    use HasFactory;

    protected $table = 'persembahan';
    protected $primaryKey = 'id_persembahan';
    
    protected $fillable = [
        'jenis'
        
    ];

    // protected $casts = [
    //     'nominal' => 'decimal:2'
    // ];

    /**
     * Relasi many to many dengan Event melalui RekapPersembahan
     */
    public function event()
    {
        return $this->belongsToMany(
            Event::class,
            'rekap_persembahan',
            'id_persembahan',
            'id_event'
        )->withPivot('tgl_persembahan')
         ->withTimestamps();
    }

    /**
     * Relasi one to many dengan RekapPersembahan
     */
    public function rekapPersembahan()
    {
        return $this->hasMany(RekapPersembahan::class, 'id_persembahan');
    }
}