<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorEntradasEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->float('valor')->default(0)->comment('valor de compra');
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
        });
    }
}
