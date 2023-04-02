<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInternacoesLeitos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('internacoes_leitos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('internacao_id')->references('id')->on('internacoes');
            $table->foreignId('acomodacao_id')->references('id')->on('acomodacoes');
            $table->foreignId('unidade_id')->references('id')->on('unidades_internacoes');
            $table->foreignId('leito_id')->references('id')->on('unidades_leitos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('internacoes_leitos');
    }
}
