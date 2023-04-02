<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAgendamentosAddColmunsTeleatendimentoLinks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->string('teleatendimento_link_prestador')->nullable();
            $table->string('teleatendimento_link_paciente')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn('teleatendimento_link_prestador');
            $table->dropColumn('teleatendimento_link_paciente');
        });
    }
}
