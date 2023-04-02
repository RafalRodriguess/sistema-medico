<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLaudosPaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('laudos_paciente', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('paciente_id')->references('id')->on('pessoas');
            $table->foreignId('agendamento_id')->references('id')->on('agendamentos');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
            $table->json('laudo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('laudos_paciente');
    }
}
