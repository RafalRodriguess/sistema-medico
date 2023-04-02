<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableInternacoesIncAtendimentoId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            $table->foreignId('atendimento_id')->nullable()->references('id')->on('agendamento_atendimentos');
            $table->dropForeign('internacoes_especialidade_alta_id_foreign');
            $table->dropColumn('especialidade_alta_id');
            $table->dropForeign('internacoes_motivo_alta_id_foreign');
            $table->dropColumn('motivo_alta_id');
            $table->dropForeign('internacoes_setor_alta_id_foreign');
            $table->dropColumn('setor_alta_id');
            $table->dropForeign('internacoes_procedimento_alta_id_foreign');
            $table->dropColumn('procedimento_alta_id');
            $table->dropColumn('infeccao_alta');
            $table->dropColumn('declaracao_obito_alta');
            $table->dropColumn('obs_alta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('internacoes', function (Blueprint $table) {
            //
        });
    }
}
