<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHorarioAtendimentoInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horario_atendimento_instituicao', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('dia_semana');
            $table->time('horario_inicio')->nullable()->default(null);
            $table->time('horario_fim')->nullable()->default(null);
            $table->boolean('full_time')->nullable()->default(false);
            $table->boolean('fechado')->nullable()->default(false);
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horario_atendimento_instituicao');
    }
}
