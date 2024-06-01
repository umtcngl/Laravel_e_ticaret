<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adres extends Model
{
    protected $table = 'adresler';

    protected $fillable = [
        'kullanici_id',
        'adres',
        'sehir',
        'posta_kodu',
    ];

    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }
}
