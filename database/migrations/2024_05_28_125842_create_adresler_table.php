<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdreslerTable extends Migration
{
    public function up()
    {
        Schema::create('adresler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->string('adres');
            $table->string('sehir');
            $table->string('posta_kodu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('adresler');
    }
}
