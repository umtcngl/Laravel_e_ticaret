<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrunOnerileriTable extends Migration
{
    public function up()
    {
        Schema::create('urun_onerileri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->unsignedBigInteger('urun_id');
            $table->foreign('urun_id')->references('id')->on('urunler');
            $table->dateTime('oneri_tarihi');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('urun_onerileri');
    }
}
