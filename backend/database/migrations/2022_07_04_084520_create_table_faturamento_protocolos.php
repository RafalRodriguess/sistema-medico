<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableFaturamentoProtocolos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faturamento_protocolos', function (Blueprint $table) {
            $table->id();
            $table->integer('cod_externo')->index()->comment("codigo no sistema de terceiro")->nullable();
            $table->string('descricao');
            $table->tinyInteger('tipo')->comment("1 - manual, 2 - sancoop")->nullable();
            $table->tinyInteger('status')->comment("0 - criado, 1 - finalizado, 2 - pendencia")->default(0);
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faturamento_protocolos');
    }
}
