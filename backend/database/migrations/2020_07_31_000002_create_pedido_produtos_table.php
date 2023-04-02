<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_produtos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('produto_id')->references('id')->on('produtos');
            $table->foreignId('pedido_id')->references('id')->on('pedidos');

            $table->decimal('valor', 8, 2);


            $table->string('nome','255');
            $table->string('nome_farmaceutico','255')->nullable();
            $table->string('breve_descricao','255');

            $table->integer('quantidade');

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
        Schema::dropIfExists('pedido_produtos');
    }
}
