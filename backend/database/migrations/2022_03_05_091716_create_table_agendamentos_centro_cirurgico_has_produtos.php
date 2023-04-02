<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosCentroCirurgicoHasProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_centro_cirurgico_has_produtos', function (Blueprint $table) {
            $table->foreignId('agendamento_centro_cirurgico_id')->references('id')->on('agendamentos_centro_cirurgico')->index('agendamento_centro_cirurgico_id_foreign_has_produtos');
            $table->foreignId('produto_id')->references('id')->on('produtos')->index('produto_id_foreign_has_agendamentos_centro_cirurgico');
            $table->foreignId('fornecedor_id')->references('id')->on('pessoas')->index('fornecedor_id_foreign_has_agendamentos_centro_cirurgico');
            $table->integer('quantidade');
            $table->text('obs')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_centro_cirurgico_has_produtos');
    }
}
