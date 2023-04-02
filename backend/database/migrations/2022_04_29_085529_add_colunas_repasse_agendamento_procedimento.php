<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunasRepasseAgendamentoProcedimento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos_procedimentos', function (Blueprint $table) {
            $table->string('tipo')->comment('dinheiro ou porcentagem')->nullable()->default(null);
            $table->decimal('valor_repasse', 8,2)->nullable()->default(null);
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
            $table->dropColumn('tipo');
            $table->dropColumn('valor_repasse');
        });
    }
}
