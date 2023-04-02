<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmSaidaEstoqueIdTableAgendamentosCentroCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->foreignId('saida_estoque_id')->nullable()->default(null)->references('id')->on('saida_estoque')->index('saida_estoque_index_foreingKey_centro_cirurgico');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            //
        });
    }
}
