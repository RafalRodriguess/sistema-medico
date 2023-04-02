<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentosEspera extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamentos_lista_espera', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->foreignId('convenio_id')->nullable()->default(null)->references('id')->on('convenios');
            $table->foreignId('prestador_id')->nullable()->default(null)->references('id')->on('prestadores');
            $table->foreignId('especialidade_id')->nullable()->default(null)->references('id')->on('especialidades');
            $table->text('obs')->nullable()->default(null);
            $table->tinyInteger('status')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamentos_lista_espera');
    }
}
