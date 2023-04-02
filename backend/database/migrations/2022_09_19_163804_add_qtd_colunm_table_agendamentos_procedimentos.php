<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQtdColunmTableAgendamentosProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_procedimentos', function (Blueprint $table) {
            $table->integer('qtd_procedimento')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos_procedimentos', function (Blueprint $table) {
            $table->dropColumn('qtd_procedimento');
        });
    }
}
