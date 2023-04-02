<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuarioCartoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_cartoes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('id_pagarme', 255);
            $table->string('ultimos_digitos', 4);
            $table->string('bandeira', 30);

            $table->string('nome','255');
            $table->string('rua','255');
            $table->string('numero','10');
            $table->string('bairro','100');
            $table->string('cidade','100');
            $table->string('estado','100');
            $table->string('cep','10');

            $table->foreignId('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_cartoes');
    }
}
