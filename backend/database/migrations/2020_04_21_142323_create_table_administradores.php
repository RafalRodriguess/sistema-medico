<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAdministradores extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('administradores', function (Blueprint $table) {
            $table->id();
            $table->string('nome','255');
            $table->string('cpf','15');
            $table->string('email','255');
            $table->string('password','255');
            $table->string('foto','255')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->unsignedBigInteger('perfis_usuario_id');
            $table->foreign('perfis_usuario_id')->references('id')->on('perfis_usuario');
            $table->timestamps();
            $table->softDeletes();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('administradores');
    }
}
