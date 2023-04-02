<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSolicitacaoComprasProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solicitacao_compras_produtos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();

            $table->foreignId('solicitacao_compras_id')
            ->references('id')
            ->on('solicitacao_compras');

            $table->foreignId('produto_id')
            ->references('id')
            ->on('produtos');

            $table->foreignId('pessoa_id')
            ->nullable()
            ->default(null)
            ->references('id')
            ->on('pessoas');

            $table->integer('qtd_solicitada')->nullable()->default(0);
            $table->integer('oferta_max')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('solicitacao_compras_produtos');
    }
}
