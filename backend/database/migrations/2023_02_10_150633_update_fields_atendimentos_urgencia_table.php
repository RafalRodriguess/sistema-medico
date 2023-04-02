<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateFieldsAtendimentosUrgenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('agendamentos_atendimentos_urgencia')->delete();

        Schema::table('agendamentos_atendimentos_urgencia', function (Blueprint $table) {
            $table->dropForeign('fk_agen_aten_urg_instituicao_paciente');
            $table->dropForeign('fk_agen_aten_urg_instituicoes_prestadores');
            $table->dropForeign('fk_agen_aten_urg_agendamento_atendimentos');

            $table->dropColumn('instituicao_pacientes_id');
            $table->dropColumn('agendamento_atendimentos_id');
            $table->dropColumn('instituicoes_prestadores_id');
        });

        Schema::table('agendamentos_atendimentos_urgencia', function (Blueprint $table) {
            $table->unsignedBigInteger('agendamento_id')->nullable();
            $table->unsignedBigInteger('id_prestador')->nullable();
            $table->unsignedBigInteger('instituicao_id');
            $table->unsignedBigInteger('carteirinha_id')->nullable();
            
            $table->foreign('instituicao_id', 'fk_ag_aten_urgencia_instituicao')
                ->references('id')
                ->on('instituicoes')
                ->onDelete('cascade')
                ->onUpdate('no action');
            $table->foreign('carteirinha_id', 'fk_ag_aten_urgencia_carteirinha')
                ->references('id')
                ->on('pessoas_carteiras_planos_convenio')
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->foreign('id_prestador')
                ->references('id')
                ->on('prestadores')
                ->onDelete('set null')
                ->onUpdate('no action');
            $table->foreign('agendamento_id', 'fk_agen_aten_urg_atendimentos')
                ->references('id')
                ->on('agendamento_atendimentos')
                ->onDelete('set null')
                ->onUpdate('no action');
        });

        Schema::table('agendamentos_atendimentos_urgencia', function (Blueprint $table) {
            $table->string('cid')->nullable()->change();
            $table->string('same')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_atendimentos_urgencia', function (Blueprint $table) {
            $table->string('cid')->nullable(false)->change();
            $table->string('same')->nullable(false)->change();
        });
    }
}
