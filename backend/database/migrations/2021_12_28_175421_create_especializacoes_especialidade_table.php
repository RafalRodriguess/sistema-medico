<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEspecializacoesEspecialidadeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('especializacoes_especialidade', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('especializacoes_id');
            $table->unsignedBigInteger('especialidades_id');

            $table->foreign('especializacoes_id')->references('id')->on('especializacoes')->onDelete('cascade');
            $table->foreign('especialidades_id')->references('id')->on('especialidades')->onDelete('cascade');
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
        Schema::dropIfExists('especializacoes_especialidade');
    }
}
