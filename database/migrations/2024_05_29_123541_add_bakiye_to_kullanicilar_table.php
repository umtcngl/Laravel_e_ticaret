<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBakiyeToKullanicilarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check if the column exists before adding it
        if (!Schema::hasColumn('kullanicilar', 'bakiye')) {
            Schema::table('kullanicilar', function (Blueprint $table) {
                $table->decimal('bakiye', 10, 2)->default(0);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kullanicilar', function (Blueprint $table) {
            $table->dropColumn('bakiye');
        });
    }
}
