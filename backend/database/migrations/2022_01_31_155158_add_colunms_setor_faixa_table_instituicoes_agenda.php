<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsSetorFaixaTableInstituicoesAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes_agenda', function (Blueprint $table) {
            $table->foreignId('setor_id')->after('duracao_atendimento')->nullable()->references('id')->on('setores_exame');
            $table->string('faixa_etaria', 50)->after('duracao_atendimento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes_agenda', function (Blueprint $table) {
            $table->dropColumn('setor_id');
            $table->dropColumn('faixa_etaria');
        });
    }
}
