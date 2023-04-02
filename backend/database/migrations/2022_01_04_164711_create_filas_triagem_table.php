<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilasTriagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filas_triagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instituicoes_id');
            $table->string('descricao');
            $table->string('identificador', 2);
            $table->unsignedBigInteger('origens_id');
            $table->boolean('ativo')->default(1);
            $table->timestamps();

            $table->foreign('instituicoes_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filas_triagem');
    }
}
