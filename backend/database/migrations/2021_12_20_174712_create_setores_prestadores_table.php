<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetoresPrestadoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setores_exame_prestadores', function (Blueprint $table) {
            $table->unsignedBigInteger('setores_exame_id');
            $table->unsignedBigInteger('prestadores_id');

            $table->primary(['setores_exame_id', 'prestadores_id'], 'setores_exame_prestadores_pk');

            $table->foreign('setores_exame_id')->references('id')->on('setores_exame');
            $table->foreign('prestadores_id')->references('id')->on('prestadores');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('setores_prestadores');
    }
}
