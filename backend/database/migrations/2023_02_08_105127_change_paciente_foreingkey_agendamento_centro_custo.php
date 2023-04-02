<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePacienteForeingkeyAgendamentoCentroCusto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->dropForeign('agendamentos_centro_cirurgico_paciente_id_foreign');
            $table->dropIndex('agendamentos_centro_cirurgico_paciente_id_foreign');
            $table->dropColumn('paciente_id');
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
            //
        });
    }
}
