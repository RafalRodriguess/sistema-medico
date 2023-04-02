<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmOrcamentoIdTableContasReceber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->foreignId('odontologico_id')->nullable()->default(null)->references('id')->on('odontologicos_paciente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('contas_receber', function (Blueprint $table) {
            $table->dropColumn('odontologico_id');
        });
    }
}
