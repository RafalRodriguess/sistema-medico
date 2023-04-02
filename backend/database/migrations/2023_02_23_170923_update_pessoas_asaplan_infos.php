<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdatePessoasAsaplanInfos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->string('asaplan_filial')->nullable();
            $table->string('asaplan_tipo')->nullable();
            $table->string('asaplan_chave_plano')->nullable();
            $table->string('asaplan_situacao_plano')->nullable();
            $table->string('asaplan_id_titular')->nullable();
            $table->string('asaplan_nome_titular')->nullable();
            $table->string('asaplan_ultima_atualizacao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pessoas', function (Blueprint $table) {
            $table->dropColumn('asaplan_filial');
            $table->dropColumn('asaplan_tipo');
            $table->dropColumn('asaplan_chave_plano');
            $table->dropColumn('asaplan_situacao_plano');
            $table->dropColumn('asaplan_id_titular');
            $table->dropColumn('asaplan_nome_titular');
            $table->dropColumn('asaplan_ultima_atualizacao');
        });
    }
}
