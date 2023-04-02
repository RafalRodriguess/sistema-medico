<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivosCancelamentoExameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivos_cancelamento_exame', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->enum('tipo', ['administrativo', 'médico', 'paciente', 'transferência']);
            $table->boolean('ativo');
            $table->unsignedBigInteger('procedimento_instituicao_id');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('procedimento_instituicao_id')->references('id')->on('procedimentos_instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivos_cancelamento_exame');
    }
}
