<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstituicoesTransferencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicoes_transferencia', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('codigo_sus')->nullable();
            $table->string('estado');
            $table->string('cidade');
            $table->string('bairro');
            $table->string('rua');
            $table->integer('numero');
            $table->string('complemento')->nullable();
            $table->string('cep');
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
        Schema::dropIfExists('instituicoes_transferencia');
    }
}
