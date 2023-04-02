<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableProcedimentosConveniosHasProcedimentosExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('procedimentos_convenios_has_procedimentos_extra');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('procedimentos_convenios_has_procedimentos_extra', function (Blueprint $table) {
            $table->foreignId('procedimento_instituicao_convenio_id')->references('id')->on('procedimentos_instituicoes_convenios')->index('procedimento_instituicao_convenio_id_has_procedimentos_extra');
            $table->foreignId('grupo_faturamento_id')->references('id')->on('grupos_faturamento')->index('grupo_faturamento_id_has_procedimentos_extra');
            $table->foreignId('procedimento_id')->references('id')->on('procedimentos')->index('procedimento_id_has_procedimentos_extra');
            $table->integer('quantidade');
        });
    }
}
