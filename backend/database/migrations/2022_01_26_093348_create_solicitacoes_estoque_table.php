<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacoesEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('destino')->comment('1 = Paciente, 2 = Setor, 3 = Estoque');
            $table->unsignedBigInteger('instituicoes_id');
            $table->unsignedBigInteger('estoque_origem_id');
            $table->boolean('urgente')->default(false);
            $table->string('observacoes')->nullable();
            $table->unsignedBigInteger('setores_exame_id')->nullable();
            $table->unsignedBigInteger('unidades_internacoes_id')->nullable();
            // $table->unsignedBigInteger('instituicao_has_pacientes_id')->nullable();
            $table->unsignedBigInteger('agendamento_atendimentos_id')->nullable();
            $table->unsignedBigInteger('instituicoes_prestadores_id')->nullable();
            $table->unsignedBigInteger('estoque_destino_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('instituicoes_id')->references('id')->on('instituicoes')->onDelete('cascade');
            $table->foreign('estoque_origem_id')->references('id')->on('estoques')->onDelete('cascade');
            //$table->foreign('agendamento_atendimentos_id')->references('id')->on('agendamento_atendimentos')->onDelete('set null');
            $table->foreign('instituicoes_prestadores_id')->references('id')->on('instituicoes_prestadores')->onDelete('set null');
            $table->foreign('setores_exame_id')->references('id')->on('setores_exame')->onDelete('set null');
            $table->foreign('unidades_internacoes_id')->references('id')->on('unidades_internacoes')->onDelete('set null');
            $table->foreign('estoque_destino_id')->references('id')->on('estoques')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacoes_estoque');
    }
}
