<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerfilUsuarioInstituicao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfis_usuarios_instituicoes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('perfis_usuarios_instituicoes_habilidades', function (Blueprint $table) {
            $table->foreignId('usuario_id')->references('id')->on('instituicao_usuarios');
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
        Schema::dropIfExists('perfis_usuarios_instituicoes');
        Schema::dropIfExists('perfis_usuarios_instituicoes_habilidades');
    }
}
