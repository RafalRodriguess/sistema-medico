<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicEstProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacoes_estoque_prod', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('solicitacoes_estoque_id');
            $table->unsignedBigInteger('produtos_id');
            $table->unsignedInteger('quantidade');

            $table->foreign('solicitacoes_estoque_id')->references('id')->on('solicitacoes_estoque');
            $table->foreign('produtos_id')->references('id')->on('produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solic_est_produtos');
    }
}
