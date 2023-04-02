<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddValidadeEntradaEstoqueProdutos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_entradas_produtos', function(Blueprint $table) {
            $table->timestamp('validade')->nullable();
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
            $table->dropColumn('validade');
        });
    }
}
