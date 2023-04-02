<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendaAusente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicao_agendas_ausente', function (Blueprint $table) {
            $table->time('hora_inicio')->nullable()->change();
            $table->time('hora_fim')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicao_agendas_ausente', function (Blueprint $table) {
            //
        });
    }
}
