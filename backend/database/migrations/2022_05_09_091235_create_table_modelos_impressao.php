<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableModelosImpressao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos_impressao', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('instituicao_prestador_id')->references('id')->on('instituicoes_prestadores');
            $table->string('tamanho_folha');
            $table->string('tamanho_fonte');
            $table->decimal('margem_cabecalho', 5,2)->nullable();
            $table->decimal('margem_direita', 5,2)->nullable();
            $table->decimal('margem_rodape', 5,2)->nullable();
            $table->decimal('margem_esquerda', 5,2)->nullable();
            $table->text('cabecalho')->nullable();
            $table->text('rodape')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modelos_impressao');
    }
}
