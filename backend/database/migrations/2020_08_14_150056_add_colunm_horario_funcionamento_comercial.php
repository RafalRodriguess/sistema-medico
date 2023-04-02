<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColunmHorarioFuncionamentoComercial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('horarios_funcionamento_comerciais', function (Blueprint $table) {
            $table->boolean('fechado')->nullable()->default(false)->after('full_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('horarios_funcionamento_comerciais', function (Blueprint $table) {
            $table->dropColumn('fechado');
        });
    }
}
