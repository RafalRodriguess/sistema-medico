<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgicoHasEquipamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico_has_equipamentos', function (Blueprint $table) {
            $table->foreignId('agendamento_centro_cirurgico_id')->references('id')->on('agendamentos_centro_cirurgico')->index('agendamento_centro_cirurgico_id_foreign');
            $table->foreignId('equipamento_id')->references('id')->on('equipamentos')->index('equipamento_id_foreign');
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
        Schema::dropIfExists('agendamentos_centro_cirurgico_has_equipamentos');
    }
}
