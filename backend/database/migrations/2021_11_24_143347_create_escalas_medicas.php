<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalasMedicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalas_medicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('especialidade_id')->references('id')->on('especialidades');
            $table->string('data');
            $table->string('regra');
            $table->foreignId('origem_id')->references('id')->on('origens');
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
            $table->string('horario_inicio');
            $table->string('horario_termino');
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
        Schema::dropIfExists('escalas_medicas');
    }
}
