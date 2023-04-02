<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePedidosEntrega extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            
            $table->dateTime('entrega_cliente', 0)->nullable()->default(null)->after('status_pedido');
            $table->dateTime('entrega_comercial', 0)->nullable()->default(null)->after('status_pedido');
            $table->dateTime('entrega_sistema', 0)->nullable()->default(null)->after('status_pedido');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->dropColumn('entrega_cliente');
            $table->dropColumn('entrega_comercial');
            $table->dropColumn('entrega_sistema');
        });
    }
}
