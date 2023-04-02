<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaoHasUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_has_usuarios', function (Blueprint $table) {
            // $table->integer('instituicao_id')->unsigned();

            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
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
        Schema::dropIfExists('instituicao_has_usuarios');
    }
}
