<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunaTableProduto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos', function ($table) {
            $table->string('classificacao_abc',1);
            $table->string('classificacao_xyz',1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos', function ($table) {
            $table->dropColumn(['classificacao_abc', 'classificacao_xyz']);
        });
    }
}
