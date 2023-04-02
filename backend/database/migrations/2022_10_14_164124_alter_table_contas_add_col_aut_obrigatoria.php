<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableContasAddColAutObrigatoria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('convenios', function (Blueprint $table) {
            $table->integer('aut_obrigatoria')->default(0);
            $table->integer('divisao_tipo_guia')->default(0);
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
            $table->dropColumn('aut_obrigatoria');
            $table->dropColumn('divisao_tipo_guia');
        });
    }
}
