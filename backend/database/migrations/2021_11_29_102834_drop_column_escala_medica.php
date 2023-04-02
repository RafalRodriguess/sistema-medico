<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnEscalaMedica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalas_medicas', function (Blueprint $table) {
            $table->dropColumn('horario_inicio');
            $table->dropColumn('horario_termino');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalas_medicas', function (Blueprint $table) {
            $table->string('horario_inicio');
            $table->string('horario_termino');
        });
    }
}
