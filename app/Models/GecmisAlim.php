<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GecmisAlim extends Model
{
    protected $table = 'gecmis_alimlar';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'miktar',
        'tarih',
        'toplam_tutar',
        'satici_id',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }

    public function satici()
    {
        return $this->belongsTo(Kullanici::class, 'satici_id');
    }
    public function siparisDetaylari()
    {
        return $this->hasMany(SiparisDetay::class, 'siparis_id', 'id');
    }
}
