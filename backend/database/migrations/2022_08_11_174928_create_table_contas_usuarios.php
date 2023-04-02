<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableContasUsuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_usuarios', function (Blueprint $table) {
            $table->foreignId('conta_id')->references('id')->on("contas");
            $table->foreignId('usuario_id')->references('id')->on("instituicao_usuarios");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_usuarios');
    }
}
