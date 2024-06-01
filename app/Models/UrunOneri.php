<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UrunOneri extends Model
{
    protected $table = 'urun_onerileri';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'oneri_tarihi',
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
