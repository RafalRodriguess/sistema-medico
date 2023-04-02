<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorCompraProdutosSaida extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->dropColumn('valor');
        });

        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->unsignedDouble('valor')->default(0)->comment('valor de venda');
            $table->unsignedDouble('valor_custo')->default(0)->comment('valor de compra');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->dropColumn('valor');
            $table->dropColumn('valor_custo');
        });

        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->float('valor')->default(0)->comment('valor de compra');
        });
    }
}
