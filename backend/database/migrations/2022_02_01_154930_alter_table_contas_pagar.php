<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasPagar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            $table->foreignId('cartao_credito_id')->nullable()->references('id')->on('cartoes_credito');
            $table->date('data_compra_cartao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_pagar', function (Blueprint $table) {
            //
        });
    }
}
