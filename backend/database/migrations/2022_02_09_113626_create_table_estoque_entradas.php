<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstoqueEntradas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoque_entradas', function (Blueprint $table) {
            $table->increments('id');
            $table->foreignId('id_tipo_documento')->references('id')->on('tipos_documentos');
            $table->foreignId('id_estoque')->references('id')->on('estoques');
            $table->boolean('consignado');
            $table->boolean('contabiliza');
            $table->string('numero_documento');
            $table->string('serie');
            $table->foreignId('id_fornecedor')->references('id')->on('pessoas');

            $table->date('data_emissao');
            $table->time('data_hora_entrada');
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
        Schema::dropIfExists('estoque_entradas');
    }
}
