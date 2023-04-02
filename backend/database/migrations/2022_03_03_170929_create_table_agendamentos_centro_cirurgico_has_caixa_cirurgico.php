<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgicoHasCaixaCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico_has_caixas_cirurgicas', function (Blueprint $table) {
            $table->foreignId('agendamento_centro_cirurgico_id')->references('id')->on('agendamentos_centro_cirurgico')->index('agendamento_centro_cirurgico_id_foreign_has_caixas_cirurgicas');
            $table->foreignId('caixa_cirurgica_id')->references('id')->on('caixas_cirurgicos')->index('caixa_cirurgica_id_foreign_agendamentos_centro_cirurgico_has');
            $table->integer('quantidade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_centro_cirurgico_has_caixas_cirurgicas');
    }
}
