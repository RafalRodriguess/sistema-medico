<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuantidadeEstoqueProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->unsignedDecimal('quantidade_estoque', 14, 2)->comment('quantidade contabilizada em estoque deste lote')->default(0);
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
            $table->dropColumn('quantidade_estoque');
        });
    }
}
