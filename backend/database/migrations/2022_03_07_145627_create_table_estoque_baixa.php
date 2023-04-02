<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstoqueBaixa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_baixa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estoque_id')->references('id')->on('estoques');
            $table->foreignId('setor_id')->references('id')->on('setores');
            $table->foreignId('motivo_baixa_id')->references('id')->on('motivo_baixa');
            $table->date('data_emissao');
            $table->time('data_hora_baixa');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
            $table->foreignId('instituicao_id')->unsigned()->nullable()->references('id')->on('instituicoes');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('estoque_baixa');
    }
}
