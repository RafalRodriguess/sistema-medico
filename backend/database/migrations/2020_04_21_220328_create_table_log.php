<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_administracao', function (Blueprint $table) {
            $table->id();
            $table->longText('mensagem');
            $table->integer('classe_id');
            $table->integer('elemento_id');
            $table->unsignedBigInteger('administrador_id');
            $table->foreign('administrador_id')->references('id')->on('administradores');
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
        Schema::dropIfExists('log_administracao');
    }
}
