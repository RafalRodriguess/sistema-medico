<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstabelecimentoUsuarioHasHabilidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comercial_usuario_has_habilidades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('usuario_id')->references('id')->on('comercial_usuarios');
            $table->foreignId('habilidade_id')->references('id')->on('comercial_habilidades');
            $table->boolean('habilitado')->nullable()->default(true);
            $table->foreignId('comercial_id')->references('id')->on('comerciais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comercial_usuario_has_habilidades');
    }
}
