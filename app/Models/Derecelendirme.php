<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Derecelendirme extends Model
{
    protected $table = 'derecelendirmeler';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'puan',
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
