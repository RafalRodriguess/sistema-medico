<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddValorSaidasEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('saida_estoque_produtos', function(Blueprint $table) {
            $table->float('valor')->default(0)->comment('valor de venda');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('saida_estoque_produtos', function(Blueprint $table) {
            $table->dropColumn('valor');
        });
    }
}
