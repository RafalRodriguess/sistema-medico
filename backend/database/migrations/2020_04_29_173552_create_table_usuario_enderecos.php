<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuarioEnderecos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_enderecos', function (Blueprint $table) {
            $table->id();
            $table->string('rua', '255');
            $table->string('numero', '25');
            $table->string('cep', '25');
            $table->string('bairro', '255');
            $table->string('cidade', '255');
            $table->string('estado', '255');
            $table->string('complemento', '255')->nullable();
            $table->string('referencia', '255')->nullable();
            $table->unsignedBigInteger('usuario_id');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_enderecos');
    }
}
