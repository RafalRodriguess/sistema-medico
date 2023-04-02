<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableExcessaoProcedimentosPrestador extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excessao_procedimentos_prestador', function (Blueprint $table) {
            $table->foreignId('prestador_id')->references('id')->on('instituicoes_prestadores');
            $table->foreignId('procedimento_id')->references('id')->on('procedimentos');
            $table->foreignId('prestador_faturado_id')->references('id')->on('instituicoes_prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('excessao_procedimentos_prestador');
    }
}
