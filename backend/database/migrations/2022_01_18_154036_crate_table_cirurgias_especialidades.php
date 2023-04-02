<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CrateTableCirurgiasEspecialidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cirurgias_especialidades', function (Blueprint $table) {
            $table->foreignId('especialidade_id')->references('id')->on('especialidades');
            $table->foreignId('cirurgia_id')->references('id')->on('cirurgias');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('cirurgias_especialidades');
    }
}
