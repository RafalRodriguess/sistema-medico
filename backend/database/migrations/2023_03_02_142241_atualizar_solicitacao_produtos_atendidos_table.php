<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AtualizarSolicitacaoProdutosAtendidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::transaction(function() {
            DB::table('solicitacao_estoque_prod_atendidos')->delete();
    
            Schema::table('solicitacao_estoque_prod_atendidos', function(Blueprint $table) {
                $table->dropForeign('fk_produtos_id');
                $table->dropColumn('lote');
                $table->dropColumn('produtos_id');

                $table->timestamps();
    
                $table->unsignedBigInteger('id_entrada_produto');
                $table->foreign('id_entrada_produto', 'fk_entrada_produto')
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
