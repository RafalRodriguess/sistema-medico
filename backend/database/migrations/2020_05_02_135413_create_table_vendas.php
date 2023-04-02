<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->longText('endereco');
            $table->unsignedTinyInteger('status');
            $table->foreignId('comercial_id')->references('id')->on('comerciais');
            $table->foreignId('usuario_id')->references('id')->on('usuarios');
            $table->foreignId('endereco_id')->references('id')->on('usuario_enderecos');
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
        Schema::dropIfExists('vendas');
    }
}
