<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Yorumlar extends Model
{
    protected $table = 'yorumlar';

    protected $fillable = [
        'kullanici_id',
        'urun_id',
        'icerik',
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
