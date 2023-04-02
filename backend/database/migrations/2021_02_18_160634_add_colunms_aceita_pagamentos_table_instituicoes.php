<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsAceitaPagamentosTableInstituicoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->tinyInteger('cartao_entrega')->nullable()->default(0)->after('taxa_tectotum');
            $table->tinyInteger('dinheiro')->nullable()->default(0)->after('taxa_tectotum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('instituicoes', function (Blueprint $table) {
            $table->dropColumn('cartao_entrega');
            $table->dropColumn('dinheiro');
        });
    }
}
