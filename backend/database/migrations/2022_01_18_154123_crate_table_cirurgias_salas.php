<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTableCirurgiasSalas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cirurgias_salas', function (Blueprint $table) {
            $table->foreignId('sala_id')->references('id')->on("salas_cirurgicas");
            $table->foreignId('cirurgia_id')->references('id')->on("cirurgias");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cirurgias_salas');
    }
}
