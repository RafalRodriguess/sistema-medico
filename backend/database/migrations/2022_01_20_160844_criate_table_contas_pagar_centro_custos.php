<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CriateTableContasPagarCentroCustos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contas_pagar_centros_custos', function (Blueprint $table) {
            $table->foreignId('conta_pagar_id')->references('id')->on('contas_pagar');
            $table->foreignId('centro_custo_id')->references('id')->on('centros_de_custos');
            $table->decimal('valor', 8, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contas_pagar_centros_custos');
    }
}
