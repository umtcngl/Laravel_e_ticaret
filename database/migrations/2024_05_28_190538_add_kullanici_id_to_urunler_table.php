<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKullaniciIdToUrunlerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('urunler', function (Blueprint $table) {
            $table->bigInteger('kullanici_id')->unsigned()->after('kategori_id');
            $table->foreign('kullanici_id')->references('id')->on('kullanicilar')->onDelete('cascade');
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
            $table->dropForeign(['kullanici_id']);
            $table->dropColumn('kullanici_id');
        });
    }
}
