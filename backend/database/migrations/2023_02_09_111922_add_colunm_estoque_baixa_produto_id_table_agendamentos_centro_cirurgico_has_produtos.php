<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmEstoqueBaixaProdutoIdTableAgendamentosCentroCirurgicoHasProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico_has_produtos', function (Blueprint $table) {
            $table->foreignId('saida_estoque_produto_id')->nullable()->default(null)->references('id')->on('saida_estoque_produtos')->index('saida_estoque_produtos_index_centro_cirurgico_produtos');
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
