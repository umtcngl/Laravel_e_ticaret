<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiparislerTable extends Migration
{
    public function up()
    {
        Schema::create('siparisler', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kullanici_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar');
            $table->dateTime('siparis_tarihi');
            $table->decimal('toplam_tutar', 10, 2);
            $table->enum('durum', ['beklemede', 'hazırlanıyor', 'kargoya verildi', 'teslim edildi'])->default('beklemede');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('siparisler');
    }
}
