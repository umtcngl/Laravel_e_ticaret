<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiparisDetay extends Model
{
    protected $table = 'siparis_detaylari';

    protected $fillable = [
        'siparis_id',
        'urun_id',
        'miktar',
    ];

    public function siparis()
    {
        return $this->belongsTo(Siparisler::class, 'siparis_id');
    }

    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
