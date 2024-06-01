<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKullaniciAktiviteleriTable extends Migration
{
    public function up()
    {
        Schema::create('kullanici_aktiviteleri', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->unsignedBigInteger('urun_id')->nullable();
            $table->foreign('urun_id')->references('id')->on('urunler');
            $table->string('sayfa_url');
            $table->integer('sure_saniye');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kullanici_aktiviteleri');
    }
}
