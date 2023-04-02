<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsSaidaEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saida_estoque', function(Blueprint $table) {
            $table->unsignedBigInteger('agendamento_id')->nullable();
            $table->unsignedBigInteger('pessoa_id')->nullable();
            $table->unsignedBigInteger('centros_custos_id')->nullable();

            $table->foreign('agendamento_id', 'fk_saida_agendamento_id')->references('id')->on('agendamentos');
            $table->foreign('pessoa_id', 'fk_saida_pessoa_id')->references('id')->on('pessoas');
            $table->foreign('centros_custos_id', 'fk_saida_centros_custos_id')->references('id')->on('centros_de_custos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saida_estoque', function(Blueprint $table) {
            $table->dropForeign('fk_saida_agendamento_id');
            $table->dropForeign('fk_saida_pessoa_id');
            $table->dropForeign('fk_saida_centros_custos_id');

            $table->dropColumn('agendamento_id');
            $table->dropColumn('pessoa_id');
            $table->dropColumn('centros_custos_id');
        });
    }
}
