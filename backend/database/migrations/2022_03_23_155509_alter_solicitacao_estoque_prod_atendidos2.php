<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSolicitacaoEstoqueProdAtendidos2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacao_estoque_prod_atendidos', function (Blueprint $table) {
            $table->unsignedBigInteger('produtos_id');
            $table->foreign('produtos_id', 'fk_produtos_id')->references('id')->on('produtos')->onDelete('cascade');
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
            $table->dropForeign('fk_produtos_id');
            $table->dropColumn('produtos_id');
        });
    }
}
