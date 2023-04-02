<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivosAlta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivos_altas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao_motivo_alta');
            $table->integer('tipo');
            $table->integer('motivo_transferencia_id')->nullable();
            $table->integer('codigo_alta_sus');
            $table->foreignId('instituicao_id')->unsigned()->references('id')->on('instituicoes');
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
        Schema::dropIfExists('motivos_alta');
    }
}
