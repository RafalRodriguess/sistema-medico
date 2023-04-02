<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableInstituicaoUsuarioHasHabilidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instituicao_usuario_has_habilidades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // $table->integer('instituicao_id')->unsigned();
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
            $table->foreignId('habilidade_id')->references('id')->on('instituicao_habilidades');
            $table->boolean('habilitado')->nullable()->default(true);
            $table->foreignId('instituicao_id')->references('id')->on('instituicoes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instituicao_usuario_has_habilidades');
    }
}
