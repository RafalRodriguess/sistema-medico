<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAuditoriaAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('auditoria_agendamentos', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['agendado','confirmado','cancelado','pendente','finalizado','excluir','ausente'])->nullable();
            $table->dateTime('data');
            $table->string('log');
            $table->text('informacao')->nullable();
            $table->foreignId('agendamento_id')->references('id')->on('agendamentos');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
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
        Schema::dropIfExists('auditoria_agendamentos');
    }
}
