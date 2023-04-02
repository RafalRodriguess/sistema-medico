<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->foreignId('cartao_id')->nullable()->default(null)->references('id')->on('usuario_cartoes');
            $table->tinyInteger('parcelas')->after('valor_total')->default(1);
            $table->decimal('porcento_parcela', 4,2)->after('parcelas');
            $table->tinyInteger('free_parcelas')->after('porcento_parcela')->default(null);
            $table->string('status_pagamento', 40)->after('free_parcelas')->nullable()->default(null);
            $table->string('codigo_transacao', 100)->after('status_pagamento')->nullable()->default(null);
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
            $table->dropColumn('cartao_id');
            $table->dropColumn('parcelas');
            $table->dropColumn('porcento_parcela');
            $table->dropColumn('free_parcelas');
            $table->dropColumn('status_pagamento');
            $table->dropColumn('codigo_transacao');
        });
    }
}
