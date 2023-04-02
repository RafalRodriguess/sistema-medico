<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaidaEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saida_estoque', function (Blueprint $table) {
            $table->id();
            $table->string('observacoes')->nullable();
            $table->foreignId('estoque_baixa_id')->references('id')->on('estoque_baixa')->onDelete('cascade')->comment('baixa de estoque criada');
            $table->foreignId('estoques_id')->references('id')->on('estoques')->onDelete('cascade')->comment('estoque de origem');
            $table->foreignId('usuarios_id')->references('id')->on('instituicao_usuarios')->onDelete('cascade')->comment('usuario que criou');
            $table->foreignId('instituicoes_id')->references('id')->on('instituicoes');
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
        Schema::dropIfExists('saida_estoque');
    }
}
