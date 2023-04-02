<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaoAgendasAusente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_agendas_ausente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->date('data');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->integer('dia_todo')->default(0)->comment('1 -> sim, 0 -> não');
            $table->string('motivo')->nullable();
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
        Schema::dropIfExists('instituicao_agendas_ausente');
    }
}
