<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsFormaPagamentoTableAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->integer('parcelas')->nullable()->default(1)->change();
            $table->integer('free_parcelas')->nullable()->change();
            $table->string('forma_pagamento', 25)->default('cartao_credito');
            $table->string('troco_dinheiro', 25)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->dropColumn("forma_pagamento");
            $table->dropColumn("troco_dinheiro");
        });
    }
}
