<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableAgendamentosIncAcompanhante extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->integer('acompanhante')->nullable()->default('0')->comment('1 -> sim, 0 -> não');
            $table->string('acompanhante_relacao')->nullable();
            $table->string('acompanhante_nome')->nullable();
            $table->string('acompanhante_telefone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            //
        });
    }
}
