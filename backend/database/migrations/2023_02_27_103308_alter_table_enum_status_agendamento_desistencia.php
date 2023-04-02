<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableEnumStatusAgendamentoDesistencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {        
        Schema::table('agendamentos', function (Blueprint $table) {
            Schema::table('agendamentos', function (Blueprint $table) {
                DB::statement("ALTER TABLE agendamentos MODIFY COLUMN status ENUM('agendado', 'confirmado', 'cancelado', 'pendente', 'finalizado', 'excluir', 'ausente', 'em_atendimento', 'finalizado_medico', 'desistencia')");
            });

            $table->string('motivo_desistencia')->nullable()->comment('nao_pode_esperar,    precisou_sair, outro');
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
            //
        });
    }
}
