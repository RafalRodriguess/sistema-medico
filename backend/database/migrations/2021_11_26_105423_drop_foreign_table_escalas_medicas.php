<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropForeignTableEscalasMedicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalas_medicas', function (Blueprint $table) {
            $table->dropForeign('escalas_medicas_prestador_id_foreign');
            $table->dropColumn('prestador_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalas_medicas', function (Blueprint $table) {
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
        });
    }
}
