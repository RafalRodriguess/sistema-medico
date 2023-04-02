<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableModeloConclusoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modelos_conclusoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_prestador_id')->references('id')->on('instituicoes_prestadores');
            $table->string('descricao');
            $table->foreignId('motivo_conclusao_id')->references('id')->on('motivos_conclusoes');
            $table->text('texto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modelos_conclusoes');
    }
}
