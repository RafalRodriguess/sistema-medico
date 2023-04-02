<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSaidaEstoqueProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saida_estoque_produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('saida_estoque_id')->references('id')->on('saida_estoque')->onDelete('cascade');
            $table->string('codigo_de_barras')->nullable();
            $table->foreignId('estoque_baixa_produtos_id')->references('id')->on('estoque_baixa_produtos')->onDelete('cascade');
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
        Schema::dropIfExists('saida_estoque_produtos');
    }
}
