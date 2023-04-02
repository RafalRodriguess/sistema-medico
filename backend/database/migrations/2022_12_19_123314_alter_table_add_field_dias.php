<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAddFieldDias extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('maquinas_cartoes', function (Blueprint $table) {
            $table->integer('dias_parcela_debito')->default(0);
            $table->integer('dias_parcela_credito')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('maquinas_cartoes', function (Blueprint $table) {
            //
        });
    }
}
