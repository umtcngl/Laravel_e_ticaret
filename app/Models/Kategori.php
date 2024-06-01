<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategoriler';

    protected $fillable = [
        'kategoriAdi',
        'aciklama',
        'icon_yolu',
        'icon',
    ];

    public function urunler()
    {
        return $this->hasMany(Urun::class, 'kategori_id');
    }
}
