<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConveniosExcecoesRetornoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('convenios_excecoes_retorno', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convenios_id');
            $table->unsignedBigInteger('procedimentos_id');

            $table->foreign('convenios_id')->references('id')->on('convenios')->onDelete('cascade');
            $table->foreign('procedimentos_id')->references('id')->on('procedimentos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('convenios_excecoes_retorno');
    }
}
