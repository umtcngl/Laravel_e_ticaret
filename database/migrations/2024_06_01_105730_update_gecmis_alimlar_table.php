<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateGecmisAlimlarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gecmis_alimlar', function (Blueprint $table) {
            $table->unsignedBigInteger('satici_id')->nullable()->after('kullanici_id');
            $table->foreign('satici_id')->references('id')->on('kullanicilar')->onDelete('SET NULL');
            $table->decimal('toplam_tutar', 10, 2)->after('tarih')->nullable(); // Toplam tutar sütunu ekleniyor
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gecmis_alimlar', function (Blueprint $table) {
            $table->dropForeign(['satici_id']);
            $table->dropColumn('satici_id');
            $table->dropColumn('toplam_tutar');
        });
    }
}
