<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstPrestEspecializacoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inst_prest_especializacoes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_prestadores_id');
            $table->unsignedBigInteger('especializacoes_id');

            $table->foreign('instituicoes_prestadores_id')->references('id')->on('instituicoes_prestadores');
            $table->foreign('especializacoes_id')->references('id')->on('especializacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inst_prest_especializacoes');
    }
}
