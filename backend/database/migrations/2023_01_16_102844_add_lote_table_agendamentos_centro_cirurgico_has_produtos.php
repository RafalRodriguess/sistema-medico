<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoteTableAgendamentosCentroCirurgicoHasProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico_has_produtos', function (Blueprint $table) {
            $table->foreignId('lote_id')->nullable()->default(null)->references('id')->on('estoque_entradas_produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_centro_cirurgico_has_produtos', function (Blueprint $table) {
            //
        });
    }
}
