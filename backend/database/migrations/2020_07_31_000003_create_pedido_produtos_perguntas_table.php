<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidoProdutosPerguntasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_produtos_perguntas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pedido_produtos_id')->references('id')->on('pedido_produtos');
            $table->foreignId('pergunta_id')->references('id')->on('produto_perguntas');
            $table->foreignId('alternativa_id')->references('id')->on('produto_pergunta_alternativas')->nullable();

            $table->string('texto_pergunta','255');
            $table->string('texto_resposta','255');

            $table->decimal('valor', 8, 2)->nullable();
            $table->integer('quantidade')->nullable();
            $table->string('tipo_pergunta',50);




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
        Schema::dropIfExists('pedido_produtos_perguntas');
    }
}
