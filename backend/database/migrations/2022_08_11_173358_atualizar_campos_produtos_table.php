<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AtualizarCamposProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produtos', function(Blueprint $table) {
            $table->string('classificacao_abc',1)->nullable()->change();
            $table->string('classificacao_xyz',1)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('produtos', function(Blueprint $table) {
            $table->string('classificacao_abc',1)->nullable(false)->change();
            $table->string('classificacao_xyz',1)->nullable(false)->change();
        });
    }
}
