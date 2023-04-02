<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignInventarioEstoque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_inventario_produtos', function(Blueprint $table) {
            $table->foreign('produto_id', 'fk_inventario_produto')->references('id')->on('produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoque_inventario_produtos', function(Blueprint $table) {
            $table->dropForeign('fk_inventario_produto');
        });
    }
}
