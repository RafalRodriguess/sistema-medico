<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgicoHasCirurgias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico_has_cirurgias', function (Blueprint $table) {
            $table->foreignId('agendamento_centro_cirurgico_id')->references('id')->on('agendamentos_centro_cirurgico')->index('agendamento_centro_cirurgico_id_foreign_has_cirurgias');
            $table->foreignId('cirurgia_id')->references('id')->on('cirurgias')->index('cirurgia_id_foreign_centro_cirurgico_has_cirurgias');
            $table->foreignId('via_acesso_id')->references('id')->on('vias_acesso')->index('via_acesso_id_foreign_centro_cirurgico_has_cirurgias');
            $table->foreignId('convenio_id')->references('id')->on('convenios')->index('convenio_id_foreign_centro_cirurgico_has_cirurgias');
            $table->foreignId('cirurgiao_id')->references('id')->on('prestadores')->index('cirurgiao_id_foreign_centro_cirurgico_has_cirurgias');
            $table->tinyInteger('pacote');
            $table->string('tempo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_centro_cirurgico_has_cirurgias');
    }
}
