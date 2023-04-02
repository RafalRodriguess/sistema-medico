<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutoPerguntaAlternativas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_pergunta_alternativas', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->string('alternativa',255);
            $table->decimal('preco', 8,2)->nullable();
            $table->integer('quantidade_maxima_itens')->nullable();
            $table->foreignId('produto_pergunta_id')->references('id')->on('produto_perguntas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_pergunta_alternativas');
    }
}
