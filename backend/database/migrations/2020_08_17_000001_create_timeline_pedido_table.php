<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimelinePedidoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedido_timelines', function (Blueprint $table) {
            $table->id();

            $table->integer('usuario_id')->unsigned()->nullable();
            $table->string('usuario_type')->nullable();

            $table->index(['usuario_id', 'usuario_type']);

            $table->string("descricao", 255);
            $table->timestamp('data_mudanca');
            $table->json('mudancas');
            $table->foreignId('pedidos_id')->references('id')->on('pedidos');

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
        Schema::dropIfExists('horarios_funcionamento_comerciais');
    }
}
