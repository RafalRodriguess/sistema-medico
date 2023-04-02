<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTablePedidosProdutoPerguntaAlternativaId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pedido_produtos_perguntas', function (Blueprint $table) {
            $table->foreignId('alternativa_id')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pedido_produtos_perguntas', function (Blueprint $table) {
            $table->dropColumn('alternativa_id');
        });
    }
}
