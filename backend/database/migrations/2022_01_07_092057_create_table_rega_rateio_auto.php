<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableRegaRateioAuto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rateio_auto_plano_conta', function (Blueprint $table) {
            $table->foreignId('plano_conta_id')->references('id')->on('planos_contas');
            $table->foreignId('centro_custos_id')->references('id')->on('centros_de_custos');
            $table->decimal('percentual', 5,2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rateio_auto_plano_conta');
    }
}
