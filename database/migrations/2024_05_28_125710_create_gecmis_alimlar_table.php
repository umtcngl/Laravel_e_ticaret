<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGecmisAlimlarTable extends Migration
{
    public function up()
    {
        Schema::create('gecmis_alimlar', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->unsignedBigInteger('urun_id');
            $table->foreign('urun_id')->references('id')->on('urunler');
            $table->integer('miktar');
            $table->dateTime('tarih');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gecmis_alimlar');
    }
}
