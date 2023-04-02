<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmEstornadoTableAgendamentosProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_procedimentos', function (Blueprint $table) {
            $table->boolean('estornado')->default(false)->after('valor_atual');
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
            $table->dropColumn('estornado');
        });
    }
}
