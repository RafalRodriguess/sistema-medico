<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunsTimeTableAgendamentosCentroCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->time('sala_cirurgica_entrada')->nullable()->default(null);
            $table->time('sala_cirurgica_saida')->nullable()->default(null);
            $table->time('anestesia_inicio')->nullable()->default(null);
            $table->time('anestesia_fim')->nullable()->default(null);
            $table->time('cirurgia_inicio')->nullable()->default(null);
            $table->time('cirurgia_fim')->nullable()->default(null);
            $table->time('limpeza_inicio')->nullable()->default(null);
            $table->time('limpeza_fim')->nullable()->default(null);
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
            $table->dropColumn('sala_cirurgica_entrada');
            $table->dropColumn('sala_cirurgica_saida');
            $table->dropColumn('anestesia_inicio');
            $table->dropColumn('anestesia_fim');
            $table->dropColumn('cirurgia_inicio');
            $table->dropColumn('cirurgia_fim');
            $table->dropColumn('limpeza_inicio');
            $table->dropColumn('limpeza_fim');
        });
    }
}
