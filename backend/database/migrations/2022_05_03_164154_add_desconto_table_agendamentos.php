<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDescontoTableAgendamentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('agendamentos', function (Blueprint $table) {
            $table->decimal('desconto', 8,2)->nullable()->default(null);
        });
        
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('agendamento_id')->nullable()->default(null)->references('id')->on('agendamentos');
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
            $table->dropColumn('desconto');
        });
    }
}
