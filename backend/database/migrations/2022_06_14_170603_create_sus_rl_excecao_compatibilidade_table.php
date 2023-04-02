<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSusRlExcecaoCompatibilidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sus_rl_excecao_compatibilidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('CO_PROCEDIMENTO_RESTRICAO', 10);
$table->string('CO_PROCEDIMENTO_PRINCIPAL', 10);
$table->string('CO_REGISTRO_PRINCIPAL', 2);
$table->string('CO_PROCEDIMENTO_COMPATIVEL', 10);
$table->string('CO_REGISTRO_COMPATIVEL', 2);
$table->string('TP_COMPATIBILIDADE', 1);
$table->char('DT_COMPETENCIA',6);


            $table->foreign('instituicoes_id', 'fk_sus_rl_excecao_compatibilidade_instituicao')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sus_rl_excecao_compatibilidade');
    }
}
