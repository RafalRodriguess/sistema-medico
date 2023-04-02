<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableHorarioFuncionamentoComercial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios_funcionamento_comerciais', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('dia_semana');
            $table->time('horario_inicio')->nullable()->default(null);
            $table->time('horario_fim')->nullable()->default(null);
            $table->boolean('full_time')->nullable()->default(false);
            $table->foreignId('comercial_id')->references('id')->on('comerciais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horarios_funcionamento_comerciais');
    }
}
