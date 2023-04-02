<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAddPacienteForeingkeyAgendamentoCentroCusto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->foreignId('paciente_id')->nullable()->default(null)->after('hora_final')->references('id')->on('pessoas');
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
