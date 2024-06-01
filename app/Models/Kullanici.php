<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kullanici extends Authenticatable
{
    use Notifiable;

    protected $table = 'kullanicilar';

    protected $fillable = [
        'kullaniciAdi',
        'sifre',
        'rol',
        'bakiye',
    ];

    protected $hidden = [
        'sifre',
    ];

    public function adresler()
    {
        return $this->hasMany(Adres::class, 'kullanici_id');
    }

    public function derecelendirmeler()
    {
        return $this->hasMany(Derecelendirme::class, 'kullanici_id');
    }

    public function gecmisAlimlar()
    {
        return $this->hasMany(GecmisAlim::class, 'kullanici_id');
    }

    public function kullaniciAktiviteleri()
    {
        return $this->hasMany(KullaniciAktivite::class, 'kullanici_id');
    }

    public function sepet()
    {
        return $this->hasMany(Sepet::class, 'kullanici_id');
    }

    public function siparisler()
    {
        return $this->hasMany(Siparisler::class, 'kullanici_id');
    }

    public function yorumlar()
    {
        return $this->hasMany(Yorumlar::class, 'kullanici_id');
    }

    public function urunOnerileri()
    {
        return $this->hasMany(UrunOneri::class, 'kullanici_id');
    }

    public function getAuthPassword()
    {
        return $this->sifre;
    }
}
