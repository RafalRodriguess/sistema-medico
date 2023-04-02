<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstoqueBaixaProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_baixa_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('baixa_id')->references('id')->on('estoque_baixa');
            $table->foreignId('produto_id')->references('id')->on('produtos');
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
        Schema::dropIfExists('estoque_baixa_produtos');
    }
}
