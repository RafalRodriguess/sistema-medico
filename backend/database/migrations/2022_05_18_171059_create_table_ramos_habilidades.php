<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRamosHabilidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ramos_habilidades', function (Blueprint $table) {
            $table->foreignId('ramo_id')->references('id')->on('ramos');
            $table->foreignId('habilidade_id')->references('id')->on('instituicao_habilidades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::dropIfExists('ramos_habilidades');
    }
}
