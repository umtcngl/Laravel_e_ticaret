<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favori extends Model
{
    use HasFactory;

    // Modelin hangi tabloyu temsil ettiğini belirtiyoruz
    protected $table = 'favoriler';

    // Mass assignment için hangi alanların doldurulabilir olduğunu belirtiyoruz
    protected $fillable = [
        'kullanici_id',
        'urun_id',
    ];

    // Kullanıcı ilişkisinin tanımlanması
    public function kullanici()
    {
        return $this->belongsTo(Kullanici::class, 'kullanici_id');
    }

    // Ürün ilişkisinin tanımlanması
    public function urun()
    {
        return $this->belongsTo(Urun::class, 'urun_id');
    }
}
