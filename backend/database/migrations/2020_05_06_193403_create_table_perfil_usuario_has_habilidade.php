<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTablePerfilUsuarioHasHabilidade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_perfis_usuario_has_habilidades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('perfis_usuario_id')->references('id')->on('perfis_usuario');
            $table->foreignId('habilidade_id')->references('id')->on('admin_habilidades');
            $table->boolean('habilitado')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_perfis_usuario_has_habilidades');
    }
}
