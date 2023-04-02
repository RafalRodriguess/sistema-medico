<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalasCirurgicas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas_cirurgicas', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('sigla');
            $table->string('tempo_minimo_preparo')->nullable();
            $table->string('tempo_minimo_utilizacao')->nullable();
            $table->integer('tipo');
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
        Schema::dropIfExists('salas_cirurgicas');
    }
}
