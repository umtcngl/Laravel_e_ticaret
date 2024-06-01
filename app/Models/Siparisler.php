<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siparisler extends Model
{
    protected $table = 'siparisler';

    protected $fillable = [
        'kullanici_id',
        'siparis_tarihi',
        'toplam_tutar',
        'durum',
    ];

    protected $attributes = [
        'durum' => 'beklemede',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    public function siparisDetaylari()
    {
        return $this->hasMany(SiparisDetay::class, 'siparis_id');
    }
}
