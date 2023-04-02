<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateInstituicoesAddColmunsWhatsappAgendaDiariaHorario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->string('automacao_whatsapp_horario_agenda_prestador')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('automacao_whatsapp_horario_agenda_prestador');
        });
    }
}
