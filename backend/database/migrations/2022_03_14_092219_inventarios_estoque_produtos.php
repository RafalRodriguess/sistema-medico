<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class InventariosEstoqueProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('estoque_inventario_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estoque_inventario_id')->references('id')->on('estoque_inventario');
            $table->foreignId('produto_id')->references('id')->on('produtos');
            $table->decimal('quantidade');
            $table->decimal('quantidade_inventario');
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
          Schema::dropIfExists('estoque_inventario_produtos');
    }
}
