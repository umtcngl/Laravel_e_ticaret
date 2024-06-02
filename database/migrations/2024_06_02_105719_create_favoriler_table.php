<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavorilerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favoriler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kullanici_id')->constrained('kullanicilar')->onDelete('cascade');
            $table->foreignId('urun_id')->constrained('urunler')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favoriler');
    }
}
