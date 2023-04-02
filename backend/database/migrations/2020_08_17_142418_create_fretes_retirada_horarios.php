<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFretesRetiradaHorarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fretes_retirada_horarios', function (Blueprint $table) {

            $table->id();
            $table->foreignId('retirada_id')->references('id')->on('fretes_retirada');
            $table->enum('dia', ['segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado', 'domingo'])->nullable(); 
            $table->string('inicio', '5')->nullable();
            $table->string('fim', '5')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('fretes_retirada_horarios');
    }
}
