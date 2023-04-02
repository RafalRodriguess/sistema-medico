<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendamentoAtendimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamento_atendimentos', function (Blueprint $table) {
            $table->foreignId('agendamento_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamento_atendimentos', function (Blueprint $table) {
            $table->foreignId('agendamento_id')->nullable(false)->change();
        });
    }
}
