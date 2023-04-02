<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSolicitacaoEstoqueProdAtendidos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacao_estoque_prod_atendidos', function (Blueprint $table) {
            $table->dropForeign('fk_atend_solic_to_est_entr_prod');
            $table->dropColumn('estoque_entradas_produtos_id');
            $table->string('lote');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacao_estoque_prod_atendidos', function (Blueprint $table) {
            $table->dropColumn('lote');
            $table->foreignId('estoque_entradas_produtos_id', 'fk_atend_solic_to_est_entr_prod')->references('id')->on('estoque_entradas_produtos')->onDelete('cascade');
        });
    }
}
