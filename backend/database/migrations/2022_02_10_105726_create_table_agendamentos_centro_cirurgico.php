<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('centro_cirurgico_id')->nullable()->references('id')->on('centros_cirurgicos');
            $table->foreignId('sala_cirurgica_id')->nullable()->references('id')->on('salas_cirurgicas');
            $table->foreignId('cirurgia_id')->nullable()->references('id')->on('cirurgias');
            $table->foreignId('prestador_id')->nullable()->references('id')->on('prestadores');
            $table->string('tipo');
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_final');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_centro_cirurgico');
    }
}
