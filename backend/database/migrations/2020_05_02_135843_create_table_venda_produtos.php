<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableVendaProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venda_produtos', function (Blueprint $table) {
            $table->foreignId('vendas_id')->references('id')->on('vendas');
            $table->foreignId('produto_id')->references('id')->on('produtos');

            $table->longText('produto');
            $table->integer('quantidade');

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
        Schema::dropIfExists('venda_produtos');
    }
}
