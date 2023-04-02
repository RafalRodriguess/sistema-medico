<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAtendimentoPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atendimentos_paciente', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->foreignId('usuario_atendeu')->references('id')->on('instituicao_usuarios');
            $table->foreignId('motivo_atendimento_id')->references('id')->on('motivos_atendimento');
            $table->foreignId('agendamento_id')->nullable()->default(null)->references('id')->on('agendamentos');
            $table->text('descricao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('atendimentos_paciente');
    }
}
