<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSolicitacoesEstoqueTable2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacoes_estoque', function (Blueprint $table) {
            $table->foreignId('estoque_baixa_id')->nullable()->references('id')->on('estoque_baixa')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacoes_estoque', function (Blueprint $table) {
            $table->dropForeign('estoque_baixa_id');
        });
    }
}
