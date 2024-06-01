<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSepetTable extends Migration
{
    public function up()
    {
        Schema::create('sepet', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->unsignedBigInteger('urun_id');
            $table->foreign('urun_id')->references('id')->on('urunler');
            $table->integer('miktar');
            $table->dateTime('tarih'); // dateTime olarak değiştirildi
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('sepet');
    }
}
