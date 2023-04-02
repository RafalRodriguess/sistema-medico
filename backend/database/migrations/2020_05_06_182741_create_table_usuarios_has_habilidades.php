<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuariosHasHabilidades extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_administradores_has_habilidades', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('administrador_id')->references('id')->on('administradores');
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
        Schema::dropIfExists('admin_administradores_has_habilidades');
    }
}
