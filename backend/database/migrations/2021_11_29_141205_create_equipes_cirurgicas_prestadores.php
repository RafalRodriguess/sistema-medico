<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEquipesCirurgicasPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('equipes_cirurgicas_prestadores', function (Blueprint $table) {
            $table->integer('tipo');
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
            $table->foreignId('equipe_cirurgica_id')->references('id')->on('equipes_cirurgicas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('equipes_cirurgicas_prestadores');
    }
}
