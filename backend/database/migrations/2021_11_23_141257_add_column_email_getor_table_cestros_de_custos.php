<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnEmailGetorTableCestrosDeCustos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centros_de_custos', function (Blueprint $table) {
            $table->string('email')->after('ativo');
            $table->string('gestor')->after('ativo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('centros_de_custos', function (Blueprint $table) {
            $table->dropColumn('email');
            $table->dropColumn('gestor');
        });
    }
}
