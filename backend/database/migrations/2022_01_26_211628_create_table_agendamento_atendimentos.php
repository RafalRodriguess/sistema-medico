<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAgendamentoAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendamento_atendimentos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('agendamento_id')->references('id')->on('agendamentos');
            $table->foreignId('pessoa_id')->nullable()->references('id')->on('pessoas');
            $table->dateTime('data_hora');
            $table->tinyInteger('tipo')->comment('1 - ambulatorio, 2 - urgencia_emergencia, 3 - internação');
            $table->tinyInteger('status')->default(1)->comment('1 - em_atendimento, 2 - finalizado');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendamento_atendimentos');
    }
}
