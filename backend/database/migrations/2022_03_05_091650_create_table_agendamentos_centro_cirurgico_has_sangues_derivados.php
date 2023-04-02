<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgicoHasSanguesDerivados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico_has_sangues_derivados', function (Blueprint $table) {
            $table->foreignId('agendamento_centro_cirurgico_id')->references('id')->on('agendamentos_centro_cirurgico')->index('agendamento_centro_cirurgico_id_foreign_has_sangues_derivados');
            $table->foreignId('sangue_derivado_id')->references('id')->on('sangues_derivados')->index('sangue_derivado_id_foreign_has_agendamentos_centro_cirurgico');
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
        Schema::dropIfExists('agendamentos_centro_cirurgico_has_sangues_derivados');
    }
}
