<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventarioEstoque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('estoque_inventario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estoque_id')->references('id')->on('estoques');
            $table->foreignId('instituicao_id')->unsigned()->nullable()->references('id')->on('instituicoes');
            $table->date('data');
            $table->time('hora');
            $table->boolean('aberta');
            $table->char('tipo_contagem');
            $table->softDeletes();
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
        Schema::dropIfExists('estoque_inventario');
    }
}
