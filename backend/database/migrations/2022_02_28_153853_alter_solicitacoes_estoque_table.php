<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSolicitacoesEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solicitacoes_estoque', function (Blueprint $table) {
            $table->timestamp('data_atendimento')->nullable();
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
            $table->dropColumn('data_atendimento');
        });
    }
}
