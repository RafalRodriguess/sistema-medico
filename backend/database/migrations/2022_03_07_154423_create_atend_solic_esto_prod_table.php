<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtendSolicEstoProdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_estoque_prod_atendidos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_de_barras')->nullable();
            $table->unsignedBigInteger('solicitacoes_estoque_id');
            $table->unsignedBigInteger('estoque_entradas_produtos_id')->references('id')->on('estoque_entradas_produtos');
            $table->unsignedDouble('quantidade', 8, 2);

            $table->foreign('solicitacoes_estoque_id', 'fk_atend_solic_to_solic_est')->references('id')->on('solicitacoes_estoque')->onDelete('cascade');
            $table->foreign('estoque_entradas_produtos_id', 'fk_atend_solic_to_est_entr_prod')->references('id')->on('estoque_entradas_produtos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacao_estoque_prod_atendidos');
    }
}
