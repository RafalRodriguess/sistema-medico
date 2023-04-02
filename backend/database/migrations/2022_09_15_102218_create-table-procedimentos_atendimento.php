<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProcedimentosAtendimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimentos_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('convenio_id')->references('id')->on('convenios');
            $table->foreignId('plano_id')->nullable()->default(null)->references('id')->on('convenios_planos');
            $table->string('tipo_atendimento')->comment('urgencia/emergencia, ambulatorial, internacao');
            $table->foreignId('origem_id')->nullable()->default(null)->references('id')->on('origens');
            $table->foreignId('unidade_internacao_id')->nullable()->default(null)->references('id')->on('unidades_internacoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimentos_atendimentos');
    }
}
