<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDigerAktivitelerTable extends Migration
{
    public function up()
    {
        Schema::create('diger_aktiviteler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->unsignedBigInteger('urun_id')->nullable();
            $table->foreign('urun_id')->references('id')->on('urunler')->onDelete('cascade');
            $table->string('islem');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('diger_aktiviteler');
    }
}
