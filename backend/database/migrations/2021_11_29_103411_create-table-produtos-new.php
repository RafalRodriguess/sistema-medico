<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableProdutosNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id(); 
            $table->string("descricao");  
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            $table->boolean("kit");  
            $table->boolean("mestre");  
            $table->string("tipo");  
            $table->boolean("generico");  
            $table->foreignId('unidade_id')->references('id')->on("unidades");
            $table->foreignId('especie_id')->references('id')->on("especies"); 
            $table->foreignId('classe_id')->references('id')->on("classes"); 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('produtos');
    }
}
