<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosFuncionamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios_funcionamento', function (Blueprint $table) {
            $table->id();
            $table->string('segunda_feira_inicio')->nullable();
            $table->string('segunda_feira_fim')->nullable();
            $table->string('terca_feira_inicio')->nullable();
            $table->string('terca_feira_fim')->nullable();
            $table->string('quarta_feira_inicio')->nullable();
            $table->string('quarta_feira_fim')->nullable();
            $table->string('quinta_feira_inicio')->nullable();
            $table->string('quinta_feira_fim')->nullable();
            $table->string('sexta_feira_inicio')->nullable();
            $table->string('sexta_feira_fim')->nullable();
            $table->string('sabado_inicio')->nullable();
            $table->string('sabado_fim')->nullable();
            $table->string('domingo_inicio')->nullable();
            $table->string('domingo_fim')->nullable();
            $table->foreignId('centro_cirurgico_id')->references('id')->on('centros_cirurgicos');
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
        Schema::dropIfExists('horarios_funcionamento');
    }
}
