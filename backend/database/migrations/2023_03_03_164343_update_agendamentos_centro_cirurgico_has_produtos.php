<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateAgendamentosCentroCirurgicoHasProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {
            DB::table('agendamentos_centro_cirurgico')->update(['saida_estoque_id' => null]);
            DB::table('agendamentos_centro_cirurgico_has_produtos')->delete();

            Schema::table('agendamentos_centro_cirurgico_has_produtos', function (Blueprint $table) {
                $table->dropForeign('agendamentos_centro_cirurgico_has_produtos_lote_id_foreign');
                $table->dropForeign('fornecedor_id_foreign_has_agendamentos_centro_cirurgico');
                $table->dropForeign('produto_id_foreign_has_agendamentos_centro_cirurgico');
    
                $table->dropColumn([
                    'fornecedor_id',
                    'lote_id',
                    'produto_id'
                ]);
    
                $table->unsignedBigInteger('id_entrada_produto');
                $table->foreign('id_entrada_produto', 'fk_ag_cirurg_entrada_produto')
                    ->references('id')
                    ->on('estoque_entradas_produtos')
                    ->onDelete('cascade')
                    ->onUpdate('no action');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
