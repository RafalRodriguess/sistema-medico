<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAcomodacoes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('acomodacoes', function (Blueprint $table) {
            $table->dropColumn('numeros_leitos_urgencia');
            $table->boolean('extra_virtual')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('acomodacoes', function (Blueprint $table) {
            $table->integer('numeros_leitos_urgencia')->nullable();
            $table->dropColumn('extra_virtual');
        });
    }
}
