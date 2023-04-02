<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('comercial_id')->references('id')->on('comerciais');
            $table->foreignId('usuarios_id')->references('id')->on('usuarios');
            $table->foreignId('endereco_entregas_id')->references('id')->on('endereco_entregas');
            $table->foreignId('cartoes_id')->references('id')->on('usuario_cartoes')->nullable();

            $table->decimal('valor_total', 8, 2);
            $table->decimal('valor_entrega', 8, 2)->nullable();

            $table->tinyInteger('parcelas')->default(1);
            $table->float('valor_parcela', 4, 2);
            $table->tinyInteger('free_parcela');

            $table->string('forma_entrega', 25);

            $table->dateTime('data_entrega', 0);
            $table->dateTime('prazo_entrega', 0);

            $table->string('status_pedido', 25);
            $table->string('status_pagamento', 40)->nullable();

            $table->text('observacao')->nullable();
            $table->string('codigo_transacao','100')->nullable();

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
        Schema::dropIfExists('pedidos');
    }
}
