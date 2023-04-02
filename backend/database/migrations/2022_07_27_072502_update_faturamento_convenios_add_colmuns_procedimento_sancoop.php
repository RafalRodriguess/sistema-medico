<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFaturamentoConveniosAddColmunsProcedimentoSancoop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->integer('sancoop_cod_procedimento')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos_instituicoes_convenios', function (Blueprint $table) {
            $table->dropColumn('sancoop_cod_procedimento');
        });
    }
}
