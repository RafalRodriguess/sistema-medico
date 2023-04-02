<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableEstoques extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('estoques', function (Blueprint $table) {
            $table->id();
            $table->string("descricao");
            $table->string("tipo")->comment("estoque para estoque - sub_estoque para sub estoque");
            $table->foreignId('centro_custo_id')->references('id')->on('centros_de_custos');
            $table->foreignId('instituicao_id')->references('id')->on("instituicoes");
            
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
        Schema::dropIfExists('estoques');
    }
}
