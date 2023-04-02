<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAvaliacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avaliacoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->foreignId('agendamento_id')->references('id')->on('agendamentos');
            $table->foreignId('medico_id')->nullable()->references('id')->on('prestadores');
            $table->foreignId('especialidade_id')->nullable()->references('id')->on('especialidades');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
            $table->longText('avaliacao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avaliacoes');
    }
}
