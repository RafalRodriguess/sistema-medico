<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAgendamentoAtendimentosUrgenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_atendimentos_urgencia', function (Blueprint $table) {
            $table->id();
            $table->string('same');
            $table->unsignedBigInteger('agendamento_atendimentos_id');
            $table->unsignedBigInteger('senhas_triagem_id');
            $table->unsignedBigInteger('especialidades_id');
            $table->unsignedBigInteger('instituicoes_prestadores_id');
            $table->unsignedBigInteger('origens_id');
            $table->unsignedBigInteger('local_procedencia_id')->comment('uma origem');
            $table->unsignedBigInteger('destino_id')->comment('uma origem');
            $table->unsignedBigInteger('instituicao_pacientes_id');
            $table->unsignedBigInteger('atendimentos_id')->nullable()->comment('carater de atendimento');
            $table->timestamp('data_hora')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->string('cid');
            $table->text('observacoes')->nullable();
            $table->timestamps();

            $table->foreign('agendamento_atendimentos_id', 'fk_agen_aten_urg_agendamento_atendimentos')->references('id')->on('agendamento_atendimentos');
            $table->foreign('senhas_triagem_id', 'fk_agen_aten_urg_senhas_triagem')->references('id')->on('senhas_triagem');
            $table->foreign('instituicoes_prestadores_id', 'fk_agen_aten_urg_instituicoes_prestadores')->references('id')->on('instituicoes_prestadores');
            $table->foreign('especialidades_id', 'fk_agen_aten_urg_especialidades')->references('id')->on('especialidades');
            $table->foreign('origens_id', 'fk_agen_aten_urg_origens')->references('id')->on('origens');
            $table->foreign('local_procedencia_id', 'fk_agen_aten_urg_local_procedencia')->references('id')->on('origens');
            $table->foreign('destino_id', 'fk_agen_aten_urg_destino')->references('id')->on('origens');
            $table->foreign('instituicao_pacientes_id', 'fk_agen_aten_urg_instituicao_paciente')->references('id')->on('instituicao_has_pacientes')->onDelete('cascade');
            $table->foreign('atendimentos_id')->references('id')->on('atendimentos')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_atendimentos_urgencia');
    }
}
