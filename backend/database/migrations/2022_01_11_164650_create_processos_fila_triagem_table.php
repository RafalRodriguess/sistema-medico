<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessosFilaTriagemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('processos_fila_triagem', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('processos_triagem_id');
            $table->unsignedBigInteger('filas_triagem_id');
            $table->unsignedBigInteger('ordem');
            $table->timestamps();

            $table->foreign('processos_triagem_id')->references('id')->on('processos_triagem')->onDelete('cascade');
            $table->foreign('filas_triagem_id')->references('id')->on('filas_triagem')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('processos_fila_triagem');
    }
}
