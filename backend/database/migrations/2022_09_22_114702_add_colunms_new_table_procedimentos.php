<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmsNewTableProcedimentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->decimal('valor_custo', 8,2)->nullable()->default(0);
            $table->tinyInteger('n_cobrar_agendamento')->nullable()->default(0);
        });
        
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->decimal('valor_custo', 8,2)->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('procedimentos', function (Blueprint $table) {
            $table->dropColumn('valor_custo');
            $table->dropColumn('n_cobrar_agendamento');
        });
        Schema::table('odontologico_itens_paciente', function (Blueprint $table) {
            $table->dropColumn('valor_custo');
        });
    }
}
