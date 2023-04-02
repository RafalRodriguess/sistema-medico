<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateProcedimentoAtendimentoUrgenciaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('procedimento_atendimento_urgencia', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('atendimento_urgencia_id');
            $table->foreign('atendimento_urgencia_id', 'fk_at_urg_procedimento__at_urgencia')
                ->references('id')
                ->on('agendamentos_atendimentos_urgencia')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->unsignedBigInteger('id_procedimento');
            $table->foreign('id_procedimento', 'fk_at_urg_procedimento__procedimento')
                ->references('id')
                ->on('procedimentos_instituicoes')
                ->onDelete('cascade')
                ->onUpdate('no action');

            $table->unsignedBigInteger('id_convenio');
            $table->foreign('id_convenio', 'fk_at_urg_procedimento__convenio')
                ->references('id')
                ->on('convenios')
                ->onDelete('cascade')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('procedimento_atendimento_urgencia');
    }
}
