<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlteracoesEstoqueBaixaProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {	
            Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
                $table->dropForeign('saida_estoque_index_foreingKey_centro_cirurgico');
                $table->foreign('saida_estoque_id', 'saida_estoque_index_foreingKey_centro_cirurgico')
                    ->references('id')
                    ->on('saida_estoque')
                    ->index('saida_estoque_index_foreingKey_centro_cirurgico')
                    ->onDelete('set null')
                    ->onUpdate('no action');
            });
            Schema::table('agendamentos_centro_cirurgico_has_produtos', function(Blueprint $table) {
                $table->dropForeign('saida_estoque_produtos_index_centro_cirurgico_produtos');
                $table->foreign('saida_estoque_produto_id', 'saida_estoque_produtos_index_centro_cirurgico_produtos')
                    ->references('id')
                    ->on('saida_estoque_produtos')
                    ->index('saida_estoque_produtos_index_centro_cirurgico_produtos')
                    ->onDelete('set null')
                    ->onUpdate('no action');
            });


            DB::table('saida_estoque_produtos')->delete();
            DB::table('saida_estoque')->delete();
            DB::table('estoque_baixa_produtos')->delete();
            DB::table('estoque_baixa')->delete();
    
            Schema::table('estoque_baixa_produtos', function(Blueprint $table) {
                $table->dropForeign('estoque_baixa_produtos_produto_id_foreign');
    
                $table->dropColumn('produto_id');
                $table->dropColumn('lote');
    
                $table->foreignId('id_entrada_produto')
                    ->index('fk_id_entrada_produto_baixa')
                    ->references('id')
                    ->on('estoque_entradas_produtos')
                    ->onDelete('restrict')
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
