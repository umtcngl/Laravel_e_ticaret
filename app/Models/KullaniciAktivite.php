<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KullaniciAktivite extends Model
{
    protected $table = 'kullanici_aktiviteleri';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'sayfa_url',
        'sure_saniye',
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
