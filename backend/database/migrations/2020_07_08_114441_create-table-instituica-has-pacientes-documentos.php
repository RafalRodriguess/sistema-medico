<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaHasPacientesDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_has_pacientes_documentos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('id_externo')->nullable();
            $table->enum('grupo', ['exame', 'receita', 'atestado'])->nullable();
            $table->string('tipo', 45)->nullable();
            $table->string('codigo_pedido', 85)->nullable();
            $table->dateTime('data_pedido', 0)->nullable();
            $table->bigInteger('codigo_atendimento')->nullable();
            $table->string('nome_convenio', 255)->nullable();
            $table->string('nome_prestador', 255)->nullable();
            $table->bigInteger('codigo_exame')->nullable();
            $table->longText('descricao', 255)->nullable();
            $table->text('url', 255)->nullable();
            //$table->text('medicao_modelo_old')->nullable();
            $table->text('medicacao_qtd', 255)->nullable();
            $table->text('medicacao_posologia', 255)->nullable();
            // $table->index(["instituicao_has_pacientes_id"], 'fk_instituicoes_has_pacientes_documentos1_idx');
            $table->foreignId('instituicao_has_pacientes_id')->references('id')->on('instituicao_has_pacientes')->index('fk_instituicoes_has_pacientes_documentos1_idx');
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
        Schema::dropIfExists('instituicao_has_pacientes_documentos');
    }
}
