<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableGruposInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grupos_instituicoes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();

            $table->foreignId('grupo_id')->references('id')->on('grupos_procedimentos');
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');

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
        Schema::dropIfExists('grupos_instituicoes');
    }
}
