<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEstoqueBaixaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estoque_baixa', function (Blueprint $table) {
            $table->unsignedBigInteger('setor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estoque_baixa', function (Blueprint $table) {
            $table->unsignedBigInteger('setor_id')->nullable(false)->change();
        });
    }
}
