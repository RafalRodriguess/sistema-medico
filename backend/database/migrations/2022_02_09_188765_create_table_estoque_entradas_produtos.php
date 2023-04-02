<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstoqueEntradasProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_entradas_produtos', function (Blueprint $table) {
            $table->increments('id');
            // $table->foreignId('id_entrada')->references('id')->on('estoque_entradas');
            $table->unsignedBigInteger('id_entrada');
            $table->foreignId('id_produto')->references('id')->on('produtos');
            $table->decimal('quantidade');
            $table->char('lote');
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
        Schema::dropIfExists('estoque_entradas_produtos');
    }
}
