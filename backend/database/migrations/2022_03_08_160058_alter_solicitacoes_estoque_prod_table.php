<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSolicitacoesEstoqueProdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacoes_estoque_prod', function (Blueprint $table) {
            $table->foreignId('motivos_divergencia_id')->nullable()->references('id')->on('motivos_divergencia')->onDelete('set null');
            $table->boolean('confirma_item')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solicitacoes_estoque_prod', function (Blueprint $table) {
            $table->dropForeign('motivos_divergencia_id');
            $table->dropColumn('confirma_item');
        });
    }
}
