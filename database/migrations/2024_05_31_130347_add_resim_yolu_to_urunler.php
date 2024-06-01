<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddResimYoluToUrunler extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->string('resim_yolu')->nullable()->default(null)->after('stok');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->dropColumn('resim_yolu');
        });
    }
}
