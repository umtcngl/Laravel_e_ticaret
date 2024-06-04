<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigerAktiviteler extends Model
{
    protected $table = 'diger_aktiviteler';

    protected $fillable = [
        'kullanici_id', 'urun_id', 'islem',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
