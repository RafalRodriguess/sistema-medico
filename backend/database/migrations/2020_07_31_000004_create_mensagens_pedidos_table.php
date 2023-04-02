<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMensagensPedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mensagens_pedidos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pedido_id')->references('id')->on('pedidos');
            $table->enum('remetente', ['comercial', 'cliente']);
            $table->string('mensagem','255');


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
        Schema::dropIfExists('mensagens_pedidos');
    }
}
