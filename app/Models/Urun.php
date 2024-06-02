<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Urun extends Model
{
    protected $table = 'urunler';

    protected $fillable = [
        'urunAdi',
        'aciklama',
        'fiyat',
        'stok',
        'kategori_id',
        'kullanici_id',
        'resim_yolu', // Yeni eklenen sütun
    ];

    public function favoriler()
    {
        return $this->hasMany(Favori::class, 'urun_id');
    }
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    public function gecmisAlimlar()
    {
        return $this->hasMany(GecmisAlim::class, 'urun_id');
    }

    public function kullaniciAktiviteleri()
    {
        return $this->hasMany(KullaniciAktivite::class, 'urun_id');
    }

    public function sepet()
    {
        return $this->hasMany(Sepet::class, 'urun_id');
    }

    public function siparisler()
    {
        return $this->hasMany(Siparisler::class, 'urun_id');
    }

    public function urunOnerileri()
    {
        return $this->hasMany(UrunOneri::class, 'urun_id');
    }

    public function yorumlar()
    {
        return $this->hasMany(Yorumlar::class, 'urun_id');
    }
}
