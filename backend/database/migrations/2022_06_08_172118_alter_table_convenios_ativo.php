<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableConveniosAtivo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->integer('ativo')->default(1)->comment('0 -> inativo, 1 -> ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->dropColumn('ativo');
        });
    }
}
