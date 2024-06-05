<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePuanColumnTypeInYorumlarTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('yorumlar', function (Blueprint $table) {
            $table->decimal('puan', 4, 1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('yorumlar', function (Blueprint $table) {
            $table->integer('puan')->nullable()->change();
        });
    }
}
