<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddEditColunsAgendamentosCentroCirurgico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_centro_cirurgico', function (Blueprint $table) {
            $table->string('tipo_paciente')->nullable()->default('paciente')->comment('paciente - ambulatorio - urgencia - internacao');
            $table->foreignId('agendamento_id')->nullable()->default(null)->references('id')->on('agendamentos');
            $table->foreignId('urgencia_id')->nullable()->default(null)->references('id')->on('agendamentos_atendimentos_urgencia');
            $table->foreignId('internacao_id')->nullable()->default(null)->references('id')->on('internacoes');
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
