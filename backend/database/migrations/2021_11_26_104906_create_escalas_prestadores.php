<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEscalasPrestadores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('escalas_prestadores', function (Blueprint $table) {
            $table->id();
            $table->string('entrada');
            $table->string('saida');
            $table->string('observacao')->nullable();
            $table->foreignId('prestador_id')->references('id')->on('prestadores');
            $table->foreignId('escala_medica_id')->references('id')->on('escalas_medicas');
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
        Schema::dropIfExists('escalas_prestadores');
    }
}
