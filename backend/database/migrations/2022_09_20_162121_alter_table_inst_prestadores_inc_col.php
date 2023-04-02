<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInstPrestadoresIncCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->foreignId('modelo_recibo_atendimento_id')->nullable()->references('id')->on('modelos_recibo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_prestadores', function (Blueprint $table) {
            $table->dropColumn('modelo_recibo_atendimento_id')->references('id')->on('modelos_recibo');
        });
    }
}
