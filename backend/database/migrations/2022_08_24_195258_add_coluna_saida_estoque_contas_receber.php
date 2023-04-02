<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunaSaidaEstoqueContasReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function(Blueprint $table) {
            $table->unsignedBigInteger('saidas_estoque_id')->nullable();

            $table->foreign('saidas_estoque_id', 'fk_contas_receber_saida_estoque_id')->references('id')->on('saida_estoque')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_receber', function(Blueprint $table) {
            $table->dropForeign('fk_contas_receber_saida_estoque_id');
            $table->dropColumn('saidas_estoque_id');
        });
    }
}
