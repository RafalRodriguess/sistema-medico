<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmTableAgendamentosCentroCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->default(null)->references('id')->on('agendamentos');
            $table->foreignId('acomodacao_id')->nullable()->default(null)->references('id')->on('acomodacoes');
            $table->foreignId('unidade_internacao_id')->nullable()->default(null)->references('id')->on('unidades_internacoes');
            $table->foreignId('via_acesso_id')->nullable()->default(null)->references('id')->on('vias_acesso');
            $table->foreignId('anestesista_id')->nullable()->default(null)->references('id')->on('prestadores');
            $table->foreignId('tipo_anestesia_id')->nullable()->default(null)->references('id')->on('tipos_anestesia');
            $table->foreignId('cid_id')->nullable()->default(null)->references('id')->on('cids');
            $table->boolean('pacote')->nullable()->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->dropColumn('paciente_id');
            $table->dropColumn('acomodacao_id');
            $table->dropColumn('unidade_internacao_id');
            $table->dropColumn('via_acesso_id');
            $table->dropColumn('anestesista_id');
            $table->dropColumn('tipo_anestesia_id');
            $table->dropColumn('cid_id');
            $table->dropColumn('pacote');
        });
    }
}
