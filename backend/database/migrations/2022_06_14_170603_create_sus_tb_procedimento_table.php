<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusTbProcedimentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_tb_procedimento', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_PROCEDIMENTO', 10);
$table->string('NO_PROCEDIMENTO', 250);
$table->string('TP_COMPLEXIDADE', 1);
$table->string('TP_SEXO', 1);
$table->unsignedSmallInteger('QT_MAXIMA_EXECUCAO');
$table->unsignedSmallInteger('QT_DIAS_PERMANENCIA');
$table->unsignedSmallInteger('QT_PONTOS');
$table->unsignedSmallInteger('VL_IDADE_MINIMA');
$table->unsignedSmallInteger('VL_IDADE_MAXIMA');
$table->unsignedBigInteger('VL_SH');
$table->unsignedBigInteger('VL_SA');
$table->unsignedBigInteger('VL_SP');
$table->string('CO_FINANCIAMENTO', 2);
$table->string('CO_RUBRICA', 6);
$table->unsignedSmallInteger('QT_TEMPO_PERMANENCIA');
$table->char('DT_COMPETENCIA',6);


            $table->foreign('instituicoes_id', 'fk_sus_tb_procedimento_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_tb_procedimento');
    }
}
