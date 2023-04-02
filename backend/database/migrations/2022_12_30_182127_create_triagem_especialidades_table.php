<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTriagemEspecialidadesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('triagem_especialidades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('triagem_id')->references('id')->on('senhas_triagem')->onDelete('cascade');
            $table->foreignId('especialidades_id')->references('id')->on('especialidades')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('triagem_especialidades');
    }
}
