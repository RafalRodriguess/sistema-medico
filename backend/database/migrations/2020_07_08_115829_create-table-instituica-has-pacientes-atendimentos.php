<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaHasPacientesAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_has_pacientes_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_externo')->nullable();
            $table->dateTime('data', 0)->nullable();
            $table->string('tipo_atendimento',255)->nullable();
            $table->string('nome_prestador',255)->nullable();
            $table->string('nome_convenio',255)->nullable();
            $table->string('especialidade_atendimento',255)->nullable();
            $table->string('origem_atendimento',255)->nullable();
            $table->string('anamnese_cid',255)->nullable();
            $table->text('anamnese_descricao_cid')->nullable();
            $table->longText('anamnese_qp')->nullable();
            $table->longText('procedimento')->nullable();
            // $table->index(["instituicao_has_pacientes_id"], 'fk_instituicoes_has_pacientes_atendimentos1_idx');
            $table->foreignId('instituicao_has_pacientes_id')->references('id')->on('instituicao_has_pacientes')->index('fk_instituicoes_has_pacientes_atendimentos1_idx');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicao_has_pacientes_atendimentos');
    }
}
