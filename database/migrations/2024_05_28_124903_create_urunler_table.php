<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrunlerTable extends Migration
{
    public function up()
    {
        Schema::create('urunler', function (Blueprint $table) {
            $table->id();
            $table->string('urunAdi');
            $table->text('aciklama')->nullable();
            $table->decimal('fiyat', 10, 2);
            $table->integer('stok');
            $table->unsignedBigInteger('kategori_id'); // Kategori ilişkisi için
            $table->timestamps();

            // Kategori ilişkisi için yabancı anahtar
            $table->foreign('kategori_id')->references('id')->on('kategoriler');
        });
    }

    public function down()
    {
        Schema::dropIfExists('urunler');
    }
}
