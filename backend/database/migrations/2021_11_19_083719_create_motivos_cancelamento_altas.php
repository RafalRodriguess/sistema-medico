<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotivosCancelamentoAltas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motivos_cancelamento_altas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao_motivo_cancelamento_alta');
            $table->integer('tipo');
            $table->boolean('ativo');
            $table->foreignId('instituicao_id')->unsigned()->references('id')->on('instituicoes');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motivos_cancelamento_altas');
    }
}
