<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaturamentoConveniosAddColmunsCodigoProcedimentoConvenio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
        //     $table->string('codigo')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
        //     $table->dropColumn('codigo');
        // });
    }
}
