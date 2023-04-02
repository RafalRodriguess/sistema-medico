<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutoPerguntas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produto_perguntas', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->timestamps();
            $table->string('titulo',255);
            $table->boolean('obrigatorio')->nullable();
            $table->string('tipo',20);
            $table->integer('quantidade_maxima')->nullable();
            $table->integer('quantidade_minima')->nullable();
            $table->foreignId('produto_id')->references('id')->on('produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produto_perguntas');
    }
}
