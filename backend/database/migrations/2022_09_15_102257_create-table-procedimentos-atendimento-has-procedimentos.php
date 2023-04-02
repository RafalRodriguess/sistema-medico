<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProcedimentosAtendimentoHasProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_atendimentos_has_procedimentos', function (Blueprint $table) {
            $table->foreignId('procedimento_atendimento_id')->references('id')->on('procedimentos_atendimentos')->index('procedimento_atendimento_idx_procedimento_atendimento_id');
            $table->foreignId('grupo_faturamento_id')->references('id')->on('grupos_faturamento')->index('procedimento_atendimento_idx_grupo_faturamento_id');
            $table->bigInteger('procedimento_cod');
            $table->foreignId('procedimento_id')->references('id')->on('procedimentos')->index('procedimento_atendimento_idx_procedimento_id');
            $table->bigInteger('quantidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimentos_atendimentos_has_procedimentos');
    }
}
