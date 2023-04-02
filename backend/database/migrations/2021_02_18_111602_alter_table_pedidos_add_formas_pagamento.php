<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePedidosAddFormasPagamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            $table->integer('parcelas')->nullable()->default(1)->change();
            $table->float('valor_parcela', 4, 2)->nullable()->change();
            $table->integer('free_parcela')->nullable()->change();
            $table->string('forma_pagamento', 25)->default('cartao_credito')->after('prazo_minimo');
            $table->string('troco_dinheiro', 25)->nullable()->after('prazo_minimo');
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
            $table->dropColumn("forma_pagamento");
            $table->dropColumn("troco_dinheiro");
        });
    }
}
