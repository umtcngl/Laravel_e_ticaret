<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sepet extends Model
{
    protected $table = 'sepet';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'miktar',
        'tarih',
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
